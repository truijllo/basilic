<?php
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");

# maximum upload file size
$MAX_UP_SIZE=5000000;

  if (!empty($_POST["id"]))	$id=$_POST["id"];
  if (!empty($_POST["action"])) $action=$_POST["action"];
  if (!empty($_POST["id_doc"])) $id_doc=$_POST["id_doc"];

?>
<html>
<head>
 <meta http-equiv='pragma' content='no-cache'>
 <meta http-equiv='expires' content='0'>
 <link rel="stylesheet" href="<?echo $css_path;?>/backoffice.css" type="text/css" />
</head>

<body>

<h1>Manage files and abstracts</h1>

<div class='inputForm'>
<?
// This page can be accessed by 5 means
// - With parameter action set to add to add a file associated to the publication
// - With parameter action set to update to change a file access
// - With parameter action set to remove to remove a file
// - With parameter action set to abstract to add an abstract to the publication
// - With no parameter action set - to list actual files

// Intro: BibTeX and Title
if (!empty($id)) 
{
  sqlConnect();

  $res = sqlQuery("select * from publis WHERE id=$id");
  if (mysql_num_rows($res) != 1)
    die("Error - Unable to retrieve publication data from this id ($id).");
  $publi = mysql_fetch_array($res);
  // introduction to publi
  echo "<div class='even'>\n";
  echo "<span class='label required'>$publi[bibTex]&nbsp;&nbsp;&nbsp;</span><b>Title :</b>\n";
  echo "$publi[title]\n";
  echo "</div>\n";
}
else
{
  echo "<p>Please select a publication first...</p>\n";
}

// Add new file
switch ($action) {
  case "add" 		: include("mod_add.php"); break;
  case "remove"		: include("mod_rem.php"); break;
  case "update"		: include("mod_upd.php"); break;
  case "abstract"	: include("mod_abs.php"); break;
}

# title before files list
if (!empty($id)) {
  $result = sqlQuery("select source,id,protect,type from docs D join publidocs P on D.id=P.idDoc where P.idPubli=$id");
  if (mysql_num_rows($result) < 1)
    print "<div class='information'><b>No actual files for this reference</b></div>\n";
  else
    print "<div class='information'><b>Actual files associated to this reference :</b></div>\n";

# table containing actual files attached
  $lineNb=0;
  $formNbr = 1;
  
  while ($row=mysql_fetch_array($result)) {

    echo " <div class='".(($lineNb==0)?"even":"odd")."'>\n";
    echo " <span class='label'>&nbsp;\n";
      $type = $row['type'];
      if ($type != "ABS" and $type != "INFO") echo "<img src='$local_server$images_path/".strtolower($type).".gif' border='0' />&nbsp;\n";
    echo "</span>\n";
    echo "<table border='0' width='70%' cellspacing='0' cellpadding='0'><tr><td width='65%'>\n";
    // file and remove buton
    echo "<form action='publiFiles.php' name='fileForm$formNbr' method='POST'>\n";
      echo "<table border='0' width='100%'><tr><td>\n";
      echo "<input type='hidden' name='action' value='remove'/>\n";
      echo "<input type='hidden' name='id' value='$id'/>\n";
      echo "<input type='hidden' name='id_doc' value='$row[id]'/>\n";
        if ($row['source'] == $abstract) echo "<small><b>Abstract</b></small>\n";
        else {
		echo "<small><a href='$local_server$public_path/$publi[year]/$publi[bibTex]/";
		if ($row['protect'] == "private") echo "$protected/";
		echo "$row[source]'>$row[source]</a></small>\n";
	}
      echo "</td><td align='right'>\n";
      echo "<input type='submit' value='Remove'>";
      echo "</td></tr></table>\n";
    echo "</form>";
    // access mode
    echo "</td><td align='right' width='35'>\n";
    echo "<form action='publiFiles.php' name='accessForm$formNbr' method='POST'>\n";
      echo "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr><td align='right'>\n";
      echo "<small>&nbsp;&nbsp;Access : </small>\n";
      echo "<input type='hidden' name='action' value='update'/>\n";
      echo "<input type='hidden' name='id' value='$id'/>\n";
      echo "<input type='hidden' name='id_doc' value='$row[id]'/>\n";
      echo "</td><td align='right'>\n";
      echo "<select name='group' size='1'";
        if ($row['source'] == $abstract) echo " disabled";
      echo " onChange='document.forms[\"accessForm$formNbr\"].submit();'>\n";
      echo "  <option value='private'";
        if ($row['protect'] == "private") echo " selected";
      echo ">private</option>\n";
      echo "  <option value='public'";
        if ($row['protect'] == "public") echo " selected";
      echo ">public</option>\n";
      echo "</td></tr></table>\n";
    echo "</form>\n";
    echo "</td></tr></table>\n";
    echo "</div>";
    $lineNb = 1-$lineNb;
    $formNbr++;
  }
  
// Upload part / add a new file
  echo " <div class='".(($lineNb==0)?"even":"odd")."'>\n";
  echo "<form action='publiFiles.php' method='POST' name='publiUpload' enctype='multipart/form-data'>";
    echo " <span class='label'>File to upload :</span>\n";
    echo " <input type='hidden' name='action' value='add'/>\n";
    echo " <input type='hidden' name='id' value='$id'/>\n";
    echo " <input type='hidden' name='protect' value='true'/>\n";
    echo " <input type='hidden' name='MAX_FILE_SIZE' value='$MAX_UP_SIZE'/>\n";
    echo " <input type='file' name='fichier1' size='40'>&nbsp;&nbsp;";
    echo " <input type='submit' value='send'>\n";
  echo "</form>\n";
  echo "</div>\n";
  $lineNb = 1-$lineNb;

// Abstract part
  $file="$_SERVER[DOCUMENT_ROOT]$public_path/$publi[year]/$publi[bibTex]/$abstract" ;
  if (is_file($file)) {
	$f = fopen($file, "r");
	$content = fread ($f, filesize($file));
	fclose($f);
  }
  else $content = "";

  echo "<div class='".(($lineNb==0)?"even":"odd")."'>\n";
  echo "<form action='publiFiles.php' method='POST' name='publiAbstract'>";
    echo " <span class='label'>Abstract :</span>\n";
    echo " <table border='0' cellspacing='0' cellpadding='0'>\n";
    echo "  <tr valign='top'><td>\n"; 
    echo "  <input type='hidden' name='action' value='abstract'/>\n";
    echo "  <input type='hidden' name='id' value='$id'/>\n";
    echo "  <textarea name='abstractForm' cols='60' rows='10'>$content</textarea>\n";
    echo "  </td><td>\n";
    echo "  &nbsp;<input type='submit' value='Update'>\n";
    echo "  </td></tr>\n";
    echo " </table>\n";
  echo "</form>\n";
  echo "</div>\n";
  $lineNb = 1-$lineNb;


if (!empty($id))
  echo "<center>See the publication's <a href='$local_server$public_path/$publi[year]/$publi[bibTex]' target='parent'>associated web page</a>. &nbsp; &nbsp;</center> ";
}
?>

</body>
</html>
