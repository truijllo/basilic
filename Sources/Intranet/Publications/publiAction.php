<?
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");
?>
<html>
<head>
 <meta http-equiv='pragma' content='no-cache'>
 <meta http-equiv='expires' content='0'>
 <link rel="stylesheet" href="<?echo $css_path;?>/backoffice.css" type="text/css" />
</head>

<body>

<h1>Results of your command</h1>

<?php

intranetSqlConnect();

function debug($message)
{
  echo "<span style='color:orange;font-weight:bold'>Debug &raquo;</span> $message<br/>\n";
}

if (isset($_GET))
{
  if (!empty($_GET["act"])) $action=$_GET["act"];
  if (!empty($_GET["id"])) $id=$_GET["id"];
}

if (empty($action)) error("No action given");
if ($action!="add" && empty($id)) error("No id given");

function createDirectory($str)
{
  debug("Creating directory $str...");
  umask(0);
  mkdir($str, 0777);
  debug("Testing directory $str exists");
  if (!is_dir($str))
    error("Error : directory $str could not be created.");
}

function getNextAvailableBibTex($bibTex)
{
  global $public_path;
  $newBibTex = $bibTex;
  $i = ord("a");
  while (is_dir("$_SERVER[DOCUMENT_ROOT]$public_path/$_GET[year]/$newBibTex"))
    {
      $newBibTex = $bibTex.chr($i);
      $i++;
    }
 debug("getNextAvailableBibTex : $newBibTex");
  return $newBibTex;
}

function computeBibTexKey()
{
  global $id;
  $authorsIds = split(",", $_GET["authorList"]);
  //print_r($authorsIds);
  if (count($authorsIds)==0)
    error("Author list is empty for publication $id");
  foreach ($authorsIds as $authorId)
    {
      $result = sqlQuery("SELECT last FROM authors WHERE id=$authorId");
      if (!$result)
	error("Unable to get name from author id $authorId. id=$id");
      $row=mysql_fetch_array($result);
      $last=$row["last"];
      if (count($authorsIds)==1)
	$key=strtoupper(substr($last,0,1)).strtolower(substr($last,1,2));
      else
	$key=$key.strtoupper(substr($last,0,1));
    }
  $key=strtr($key, "·‚‡‰¬¡¿ƒÈÍËÎ …»ÀÓÌÏÔŒÕÃœÛÙÚˆ‘”“÷˙˚˘¸€⁄Ÿ‹ÁC", "aaaaAAAAeeeeEEEEiiiiIIIIooooOOOOuuuuUUUUcC");
  $key=$key.substr($_GET["year"],2,2);
  debug("bibTex=<b>$key</b>");
  return $key;
}

function checkYearDirExists()
{
  global $public_path;
  $year = $_GET["year"];
  $mdir = "$public_path/$year";
  $dir  = "$_SERVER[DOCUMENT_ROOT]/$mdir";
  if (!is_dir($dir))
    {
      umask(0);
      if (mkdir("$dir", 0755))
	debug("Creating year directory $mdir");
      else
	error("Unable to create directory $mdir - Aborting");

      $file="$dir/index.php";
      debug("Creating $file");
      $f=fopen($file,"w");
      fwrite($f, "<"."?php \$year=$year; include(\"../index.php\"); ?".">");
      fclose($f);
      chmod($file, 0644);
    }
  
  // Check everything has been created
  if (!is_dir("$dir"))
    error("Directory $mdir does not exist");
  if (!is_file("$dir/index.php"))
    error("File $mdir/index.php does not exist");
}

function cmd($cmd)
{
  debug("exec cmd $cmd");
  exec($cmd, $output, $val);
  if ($val != 0)
    error("Error during command $cmd : return value is $val");
}

function LaTeXToIso($str)
{
  global $hdr, $rap_c;
  global $public_path;
  $search = array("\\\\\\'e","\\\\`e","\\\\^e","\\\\\\\"e","\\\\\\'a","\\\\`a","\\\\^a","\\\\\\\"a","\\\\\\'i","\\\\`i","\\\\^i","\\\\\\\"i",
		  "\\\\\\'o","\\\\`o","\\\\^o","\\\\\\\"o","\\\\\\'u","\\\\`u","\\\\^u","\\\\\\\"u","\\\\cc","\\\\cC","\\\\~n","\\\\~N","\\\\&");
  $replace = array("È","Ë","Í","Î","·","‡","‚","‰","Ì","Ï","Ó","Ô",
		   "Û","Ú","Ù","ˆ","˙","˘","˚","¸","Á","«","Ò","—","&");
  return ereg_replace("^ *", "", str_replace($search, $replace, $str));
}

$fields=array("issn","doi","location","proceedings","project","range","title","year","address","booktitle","chapter","edition","editor","howpublished","institution","journal","keywords","month","note","number","optkey","organization","pages","publisher","school","series","type","volume","com_lect");

echo "<div class='information'>\n";

if ($action=="add")
{
  checkYearDirExists();

  // Compute BibTex key, adding extra letters if needed
  $year = $_GET["year"];
  $bibTex = computeBibTexKey();
  $bibTex = getNextAvailableBibTex($bibTex);

  // Set publication data
  $query = "INSERT into publis (dt_create, dt_modif, class_acti,entry, bibTex";
  foreach($fields as $field)
    if (isset($_GET["$field"]))
      $query .= ", $field";
  
  switch($_GET['entry'])
  {
  	case "Article":if ($_GET["com_lect"]=="sans") $class=6; else if($_GET["range"]=="nationales") $class=3; else $class=2; break;
	case "InProceedings":if ($_GET["com_lect"]=="sans") $class=6; else if($_GET["range"]=="nationales") $class=5; else $class=4; break;
	case "InBook":$class=1; break;
	case "Book":$class=1; break;
	case "PhdThesis":$class=9;break;
	case "MastersThesis":$class=9;break;
	case "TechReport":$class=7;break;
	case "Misc":if ($_GET["type"]=="$rap_c") $class=7; else $class=6;break;
	case "Booklet":$class=1;break;
	case "InCollection":$class=1;break;
	case "Manual":$class=8;break;
	case "Proceedings":if($_GET["range"]=="nationales") $class=5; else $class=4; break;
	case "Unpublished":$class=8;break;
  }
  
  $query .= ") VALUES (now(), now(), '$class','$_GET[entry]', '$bibTex'";
  foreach($fields as $field)
    if (isset($_GET["$field"]))
      $query .= ", '".LaTeXToIso($_GET["$field"])."'";
  $query .= ")";
  debug($query);
  sqlQuery($query);

  $id=mysql_insert_id();

  // Link to authors
  $authorsIds = explode(",", $_GET["authorList"]);
  $rank = 1;
  foreach ($authorsIds as $authorId)
    {
      debug("INSERT INTO publiauthors (idAuthor, idPubli, rank) VALUES ($authorId, $id, $rank)");
      sqlQuery("INSERT INTO publiauthors (idAuthor, idPubli, rank) VALUES ($authorId, $id, $rank)");
      $rank++;
    }

  // Link to teams
  $teamIds = explode(",", $_GET["teamList"]);
    // extern publications will be marked to optimise SQL search
    if (count($teamIds) == 1 and $teamIds[0] == '13') {
  	debug("publi marked as extern");
	sqlQuery("UPDATE publis set dt_modif=now(), team='extern' WHERE id=$id");
    }
  $rank = 1;
  foreach ($teamIds as $teamId)
    {
      debug("INSERT INTO publiequip (idEquip, idPubli, rank) VALUES ($teamId, $id, $rank)");
      sqlQuery("INSERT INTO publiequip (idEquip, idPubli, rank) VALUES ($teamId, $id, $rank)");
      $rank++;
    }

  $publiPath="$_SERVER[DOCUMENT_ROOT]$public_path/$year/$bibTex";
  createDirectory($publiPath);

  $file="$publiPath/index.php";
  $f=fopen($file,"w");
  fwrite($f, "<"."?php \$id=$id; include(\"../../publi.php\"); ?".">");
  fclose($f);
  chmod($file, 0644);
  if (!is_file($file))
    error("Unable to create $publiPath/$year/$bibTex/index.php");
  else
    debug("$file has been created");
  debug("&lt;?php \$id=$id; include(\"../../publi.php\"); ?&gt;");

  echo "<p>\nPublication has been added to the database. Its associated directory is <code>$public_path/$year/$bibTex</code><br/>\n";
  echo "You should now add associated documents (pdf, ppt, ps, abstract, info, images, movies) to this publication.<br/>\n";
  echo "See the <a href='../usersguide.html' target='_top'>Publication help page</a> for details.</p>\n";

  echo "<p>\n<a href='publi.php?id=$id'>Back to publication edition</a><br/>\n";
  echo "<a href='$public_path/$year/$bibTex' target='_top'>See the publication associated page</a></p>\n";
}


else if ($action=="update")
{
  $previousBibTex = $_GET["previousBibTex"];
  $previousYear   = $_GET["previousYear"];
  $teamList	  = $_GET["teamList"];
  $previousTeamList = $_GET["previousTeamList"];
  
  $bibTex = computeBibTexKey();
  if ($bibTex==substr($previousBibTex,0,strlen($bibTex)))
    $bibTex=$previousBibTex; // Add the extra letter (XX00[a,b,c,..]) if it was present

  $year = $_GET["year"];
  if ($year != $previousYear)
    checkYearDirExists();

  if ($bibTex != $previousBibTex)
    {
      // Add possible extra letter if bibTex allready exists
      $bibTex = getNextAvailableBibTex($bibTex);
      debug("BibTex has changed : new is $bibTex");
      
      // At least one author has changed : remove previous authors...
      sqlQuery("DELETE FROM publiauthors WHERE idPubli=$id");
      // And insert new ones, in right order
      $authorsIds = explode(",", $_GET["authorList"]);
      $rank = 1;
      foreach ($authorsIds as $authorId)
	{
	  debug("INSERT INTO publiauthors (idAuthor, idPubli, rank) VALUES ($authorId, $id, $rank)");
	  sqlQuery("INSERT INTO publiauthors (idAuthor, idPubli, rank) VALUES ($authorId, $id, $rank)");
	  $rank++;
	}

      cmd("mv $_SERVER[DOCUMENT_ROOT]$public_path/$previousYear/$previousBibTex $_SERVER[DOCUMENT_ROOT]$public_path/$year/$bibTex");
      echo "BibTex has changed, your publication is now located in the <code>$public_path/$year/$bibTex</code> directory<br>\n";
      
      debug ("Publication directory moved to $public_path/$year/$bibTex");
      if (!is_dir("$_SERVER[DOCUMENT_ROOT]$public_path/$year/$bibTex"))
	error("Unable to move directory to $year/$bibTex - Administrator has been warned");
    }

  if ($previousTeamList != $teamList)
    {
      // At least one team has changed : remove previous teams...
      sqlQuery("DELETE FROM publiequip WHERE idPubli=$id");
      // And insert new ones, in the right order
      $teamIds = explode(",", $teamList);
      // extern publications will be marked to optimise SQL search
      if (count($teamIds) == 1 and $teamIds[0] == '13') {
  	  debug("publi marked as extern");
	  sqlQuery("UPDATE publis set dt_modif=now(), team='extern' WHERE id=$id");
      }
      else {
	  sqlQuery("UPDATE publis set dt_modif=now(), team='' WHERE id=$id");
      }
      $rank = 1;
      foreach ($teamIds as $teamId)
        {
	  debug("INSERT INTO publiequip (idEquip, idPubli, rank) VALUES ($teamId, $id, $rank)");
	  sqlQuery("INSERT INTO publiequip (idEquip, idPubli, rank) VALUES ($teamId, $id, $rank)");
	  $rank++;
	}
    }

  // Update publication data
  $query = "UPDATE publis SET dt_modif=now(), entry='$_GET[entry]', bibTex='$bibTex'";
  foreach($fields as $field) 
    if (isset($_GET["$field"]))
      $query .= ", $field='".LaTeXToIso($_GET[$field])."'";
    
  switch($_GET['entry'])
  {
  	case "Article":if ($_GET["com_lect"]=="sans") $class=6; else if($_GET["range"]=="nationales") $class=3; else $class=2; break;
	case "InProceedings":if ($_GET["com_lect"]=="sans") $class=6; else if($_GET["range"]=="nationales") $class=5; else $class=4; break;
	case "InBook":$class=1; break;
	case "Book":$class=1; break;
	case "PhdThesis":$class=9;break;
	case "MastersThesis":$class=9;break;
	case "TechReport":$class=7;break;
	case "Misc":if ($_GET["type"]=="$rap_c") $class=7; else $class=6;break;
	case "Booklet":$class=1;break;
	case "InCollection":$class=1;break;
	case "Manual":$class=8;break;
	case "Proceedings":if($_GET["range"]=="nationales") $class=5; else $class=4; break;
	case "Unpublished":$class=8;break;
  }

  $query .= ", class_acti='$class' WHERE id=$id";
  debug($query);
  sqlQuery($query);

  echo "<p>\nPublication has been updated.</p>\n";

  echo "<p>\n<a href='publi.php?id=$id'>Back to publication edition</a><br/>\n";
  echo "<a href='$public_path/$year/$bibTex' target='_top'>See the publication associated page</a></p>\n";
}


else if ($action=="delete")
{
  // Check Garbage dir exists
  $garbage="$public_path/Garbage";
  $garbageDir="$_SERVER[DOCUMENT_ROOT]$garbage";
  if (!is_dir($garbageDir))
    error("Garbage directory ($garbageDir) does not exists, cannot remove $_GET[previousBibTex]");

  debug("DELETE FROM publis WHERE id=$id");
  sqlQuery("DELETE FROM publis WHERE id=$id");
  debug("DELETE FROM publiauthors WHERE idPubli=$id");
  sqlQuery("DELETE FROM publiauthors WHERE idPubli=$id");
  debug("DELETE FROM publiequip WHERE idPubli=$id");
  sqlQuery("DELETE FROM publiequip WHERE idPubli=$id");
  // search for docs
  $res = sqlQuery("SELECT idDoc FROM docs, publidocs WHERE docs.id=publidocs.idDoc AND publidocs.idPubli=$id");
  while ($doc = mysql_fetch_array($res)) {
    debug("DELETE FROM docs WHERE id = $doc[0]");
    sqlQuery("DELETE FROM docs WHERE id = $doc[0]");
  }
  debug("DELETE FROM publidocs WHERE idPubli=$id");
  sqlQuery("DELETE FROM publidocs WHERE idPubli=$id");
  echo "Publication removed from database<br/>\n";

  // Move previous directory in Garbage/bibtex
  $previousDir="$_SERVER[DOCUMENT_ROOT]$public_path/$_GET[previousYear]/$_GET[previousBibTex]";
  // Delete previous deleted directory in Garbage with same BibTeX 
  if (is_dir("$garbageDir/$_GET[previousBibTex]")) {
	echo "<b>Warning</b>: The previous deleted directory $_GET[previousBibTex] has been suppressed in Garbage directory\n";
	cmd("rm -rf $garbageDir/$_GET[previousBibTex]");
  }
  cmd("mv $previousDir $garbageDir");
  echo "Previous publication directory has been moved in <code>$garbage</code><br/>\n";
}
else
  error("Internal error : unrecognized action $action.");


echo "</div>\n";
?>

</body>
</html>
