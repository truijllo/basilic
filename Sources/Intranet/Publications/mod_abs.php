<?
    	echo "<div class='information'>\n";
	require("../updatePubliDocs.php");
	$chemin="$_SERVER[DOCUMENT_ROOT]$public_path/$publi[year]/$publi[bibTex]/" ;
	intranetSqlConnect();
	$file = $chemin.$abstract;
  	if (!empty($_POST["abstractForm"]))	$abstractForm=stripslashes($_POST["abstractForm"]);
	
	if ($abstractForm != "") {
	  if (is_file($file)) {
	    // save file before overwrite
	    rename($file,$file.".old");
	  }
	  $f = fopen($file, "w");
	  fwrite($f, $abstractForm);
	  fclose($f);
	  chmod($file, 0644);
	  if (!is_file("$file"))
	    error("File $abstract could not be created");
	  else updatePubliDocs($id);
	}
	echo "</div>\n";
?>

