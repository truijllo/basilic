<?php
require_once("utils.php");

function sourceString($row)
{
  return "type='".$row["type"]."', source='".$row["source"]."'";
}

function sizeString($row)
{
  return "size='".$row["size"]."', sizeX='".$row["sizeX"]."', sizeY='".$row["sizeY"]."', protect='".$row["protect"]."'";
}

function updatePubliDocs($publiId)
{
  global $public_path, $local_server, $images_path;
  global $protected;
  // General configuration options
  $thumbImgGeometry = "80x60";
  $pubPath = "$public_path";
  $thumbDir=".thumbs";
  
  if ((!is_numeric($publiId)) || (intval($publiId)!=$publiId))
    error("Invalid publication id ($publiId) - Aborting");

  // Get all documents associated to this publication
  $result = sqlQuery("SELECT * FROM docs, publidocs WHERE publidocs.idPubli=$publiId AND publidocs.idDoc=docs.id");  
  $docInDataBase = array();
  $docSize = array();
  while ($result && $row=mysql_fetch_array($result))
    {
      $docInDataBase[$row["id"]]=sourceString($row);
      $docSize[$row["id"]]=sizeString($row);
    }
  
  $result = sqlQuery("SELECT year, bibTex FROM publis WHERE id=$publiId");
  
  if ($result)
    $row=mysql_fetch_array($result);
  else
    error("Unable to retrieve publication data for publi $publiId");

  $msgPath = "$row[year]/$row[bibTex]";
  $publiPath = "$_SERVER[DOCUMENT_ROOT]$pubPath/$msgPath";
  $msg = "<a href='$local_server/$pubPath/$msgPath' target='parent'>$row[bibTex]</a> &nbsp; ";

  $compressedExtensions[] = "gz";
  $compressedExtensions[] = "bz";
  $compressedExtensions[] = "bz2";
  $compressedExtensions[] = "zip";
  $compressedExtensions[] = "z";

  // Check that thumbnail directory exists
  if (!is_dir($publiPath))
    {
      if (empty($_GET["forceDirCreation"]))
	error("Publication directory $msgPath does not exist.");
      else
	{
	  umask(0);
	  $yearPath = "$_SERVER[DOCUMENT_ROOT]/$pubPath/$row[year]";
	  if (!is_dir($yearPath))
	    {
	      if (mkdir("$yearPath", 0755))
		echo "Creating year directory $row[year]<br/>\n";
	      else
		error("Unable to create year directory $yearPath - Aborting");

	      $file="$yearPath/index.php";
	      echo "Creating index.php in $row[year]<br/>\n";
	      $f=fopen($file,"w");
	      fwrite($f, "<"."?php \$year=$row[year]; include(\"../index.php\"); ?".">");
	      fclose($f);
	      chmod($file, 0644);

	      if (!is_file("$file"))
		error("File index.php could not be created");
	    }
	  mkdir($publiPath, 0777);
	  if (!is_dir($publiPath))
	    error("Error : directory $publiPath could not be created.");
	  else
	    echo ("Directory created for ");

	  $file="$publiPath/index.php";
	  $f=fopen($file,"w");
	  fwrite($f, "<"."?php \$id=$publiId; include(\"../../publi.php\"); ?".">");
	  fclose($f);
	  chmod($file, 0644);
	  if (!is_file($file))
	    error("Unable to create /$msgPath/index.php");
	}
    }
  
  if (!is_dir("$publiPath/$thumbDir"))
    mkdir("$publiPath/$thumbDir") or error("Unable to create $publiPath/$thumbDir directory");
  
  $thumbDirOk = (is_dir("$publiPath/$thumbDir")) && (is_writable("$publiPath/$thumbDir"));
  if (!$thumbDirOk)
    sendMessage("Thumbnail directory $msgPath/$thumbDir is not writeable");
  
  // two rounds first for standard dir and second for protected one
  $round = 1;
  $dir = dir($publiPath);
  $file = $dir->read();
  while ($file) {
    if ($round == 1) $add_path = ""; else $add_path = "$protected/";
    if ($file != "." && $file != ".." && is_file("$publiPath/$add_path$file"))
      {
	$entry["source"] = $file;
	$entry["size"] = filesize("$publiPath/$add_path$file");
	$entry["sizeX"] = "0";
	$entry["sizeY"] = "0";
	if ($round == 1) $entry["protect"] = "public"; else $entry["protect"]="private";
	$skipFile = 0;
	
	// Remove compression extensions 
	$extension = explode('.', $file);
      
	$index = count($extension)-1;
	$ext = strtolower($extension[$index]);
      
	while (in_array($ext, $compressedExtensions))
	  {
	    $index--;
	    $ext = strtolower($extension[$index]);
	  }

	switch ($ext)
	  {
	  case "bmp":
	  case "gif":
	  case "jpg":
	  case "jpeg":
	  case "png":
	  case "ppm":
	  case "psd":
	  case "tif":
	  case "tiff":
	  case "xbm":
	  case "xpm":
	    $entry["type"]="IMG";
	    $imgSize = getimagesize("$publiPath/$add_path$file");
	    if ($imgSize[0] * $imgSize[1] != 0)
	      {
		// Create thumbnail if needed
		$entry["sizeX"] = $imgSize[0];
		$entry["sizeY"] = $imgSize[1];
		$thumbName = $file.".jpg";

		if ($thumbDirOk && !is_file("$publiPath/$thumbDir/$thumbName") || filemtime("$publiPath/$add_path$file") > (filemtime("$publiPath/$thumbDir/$thumbName")))
		  {
		    echo "  $msg : Creating $msgPath/$thumbDir/$thumbName<br />\n";
//		    MAGICK_HOME=".getenv("MAGICK_HOME")."; export MAGICK_HOME;
		    exec("MAGICK_HOME=".getenv("MAGICK_HOME")."; export MAGICK_HOME; convert -geometry $thumbImgGeometry $publiPath/$add_path$file $publiPath/$thumbDir/$thumbName", $output, $returnVar);
		    if ($returnVar != 0)
		      {
			echo "Unable to create thumbnail for $msgPath/$add_path$file. Administrator has been warned";
			sendMessage("Unable to create thumbnail for $msgPath/$add_path$file error=$returnVar");
			break;
		      }
		  }
		// else
		// sendMessage("Thumbnail up to date for ".$msgPath.$file);
	      }
	    else
	      {
		echo "Unable to create thumbnail for $msgPath/$add_path$file. Administrator has been warned";
		sendMessage("Unable to determine image size for $msgPath/$add_path$file");
		break;
	      }
	    break;

	  case "mpg":
	  case "mpeg":
	  case "avi":
	  case "mp4":
	  case "mov":
	  case "wmv":
	  case "qt":
	    $entry["type"]="MOV";
	    $thumbName = $file.".jpg";
	    if ($thumbDirOk && !is_file("$publiPath/$thumbDir/$thumbName") || filemtime("$publiPath/$add_path$file") > (filemtime("$publiPath/$thumbDir/$thumbName")))
	      {
		echo "Thumbnail will soon be created for $msgPath/$add_path$file.<br/>\n";
		sendMessage("Thumbnail must be created for $msgPath/$add_path$file");
		if (!copy("$_SERVER[DOCUMENT_ROOT]$images_path/defaultThumb.jpg", "$publiPath/$thumbDir/$thumbName"))
		  sendMessage("Unable to copy default movie thumb for $msgPath/$add_path$file");
	      }

	    if (is_file("$publiPath/$thumbDir/$thumbName"))
	      {
		$imgSize = getimagesize("$publiPath/$thumbDir/$thumbName");
		if ($imgSize[0] * $imgSize[1] != 0)
		  {
		    $entry["sizeX"] = $imgSize[0];
		    $entry["sizeY"] = $imgSize[1];
		  }
	      }
	    else
	      {
		sendMessage("Unable to retrieve thumbnail size for $msgPath/$add_path$file");
		$entry["sizeX"] = 1;
		$entry["sizeY"] = 1;
	      }
	    break;

	  case "html":
	    if (($file == "abstract.html") || ($file == "abstract.fr.html") || ($file == "abstract.en.html"))
	      $entry["type"]="ABS";
	    else
	      if (($file == "info.html") || ($file == "info.fr.html") || ($file == "info.en.html"))
		$entry["type"]="INFO";
	      else
		$entry["type"]="UNKNOWN";
	    break;
	  
	  case "ps":
	    $entry["type"]="PS";
	    break;
	  
	  case "pdf": 
	    $entry["type"]="PDF";
	    break;
	  
	  case "py":
	  case "pps":
	  case "ppt":
	    $entry["type"]="PPT";
	    break;
	  case "txt":
	    $entry["type"]="TXT";
	    break;

	  case "php":
	  case "htaccess":
	    if ($file == "index.php" or $file == ".htaccess")
	      $skipFile=1;
	    break;
	    
	  case "old":
	    if ($file == "abstract.html.old")
	      $skipFile=1;
	    break;
	  default:
	    $entry["type"]="UNKNOWN";
	    break;
	  }

	if (!$skipFile)
	  {
	    if ($entry["type"] == "UNKNOWN")
	      {
		sendMessage("Unrecognized document format for file $publiPath/$add_path$file");
		echo "Unrecognized document format : $msgPath/$add_path$file<br/>\n";
	      }
	    else
	      {
		$alreadyPresent = array_search(sourceString($entry), $docInDataBase);

		if ($alreadyPresent)
		  {
		    // echo "DEBUG : remove from docInDatabase<br />\n";
		  unset($docInDataBase[$alreadyPresent]);
		    if (sizeString($entry) != $docSize[$alreadyPresent])
		      {
			sqlQuery("UPDATE docs SET ".sizeString($entry)." WHERE id='$alreadyPresent'");
		      }
		  }
		else
		  {
		    echo "  $msg : Adding $entry[source] in database<br />\n";
		    sqlQuery("INSERT INTO docs SET dt_create=now(), ".sourceString($entry).", ".sizeString($entry));
		    sqlQuery("INSERT INTO publidocs SET idPubli=$publiId, idDoc=".mysql_insert_id());
		  }
	      }
	  }
	  
      }
      $file = $dir->read();
      // if first round is over, read protected dir
      if ($round == 1 and !$file) {
        if (is_dir("$publiPath/$protected")) {
	  $round = 2;
          $dir->close();
          $dir = dir("$publiPath/$protected");
          $file = $dir->read();
	}
      }
  }
  $dir->close();

  // Remove unused documents
  foreach(array_keys($docInDataBase) as $docId)
    {
      // Delete these documents
      $src = ereg_replace(".*source='([^']*).*", "\\1", $docInDataBase["$docId"]);
      $type = ereg_replace(".*type='([^']*).*", "\\1", $docInDataBase["$docId"]);
      echo "  $msg : Removing $src from database<br />\n";
      if (($type=="MOV") || ($type=="IMG"))
	{
	  if (!is_file("$publiPath/$thumbDir/$src.jpg"))
	    sendMessage("Cannot remove $msgPath/$thumbDir/$src.jpg : it doesn't exist !");
	  else
	    {
	      exec("rm $publiPath/$thumbDir/$src.jpg");
	      if (is_file("$publiPath/$thumbDir/$src.jpg"))
		sendMessage("Possible not removed properly : file $msgPath/$thumbDir/$src.jpg");
	    }
	}
      sqlQuery("DELETE FROM docs WHERE id=$docId");
      sqlQuery("DELETE FROM publidocs WHERE idDoc=$docId");
    }
}

?>
