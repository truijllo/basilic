<?php
session_start();
require_once("../utils.php");

header("Content-Type: text/html; charset=utf-8");
if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");
?>
<html>
<head>
  <meta http-equiv='pragma' content='no-cache'>
  <meta http-equiv='expires' content='0'>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" href="<?echo $css_path;?>/backoffice.css" type="text/css" />
</head>

<body>

<h1>Publications with missing documents</h1>

<div style='text-align:left;margin-left:10%'>

<?php

require("../updatePubliDocs.php");
intranetSqlConnect();

if (isset($_GET))
{
  $year=$_GET["updateYear"];
  $author=$_GET["authorName"];
  $update=$_GET["update"];
}

$query="SELECT id,bibTex,year,title from publis";
if (!empty($year))
  $query.=" WHERE year=$year";

$query.=" ORDER BY year DESC";
$result = sqlQuery($query);

if (mysql_num_rows($result) == 0)
     echo "No publication found.";

// For each publication
while ($result && $row=mysql_fetch_array($result))
{
  $id=$row["id"];
  // echo "$row[id] $row[bibTex] $row[title] <br />\n";
		
  if (!empty($author))
    {
      $authorFound=0;
      $aresult = sqlQuery("SELECT DISTINCT id,first,last from authors,publiauthors WHERE publiauthors.idAuthor=authors.id AND publiauthors.idPubli=$id");
      while ($aresult && ($arow=mysql_fetch_array($aresult)) && (!$authorFound))
	$authorFound = ((strtolower($arow["first"]) == strtolower($author)) ||
			(strtolower($arow["last"]) == strtolower($author)));
      if (!$authorFound)
	continue;
    }
		
  // Update publication documents
  if (!empty($update))
    updatePubliDocs($id);

  // Parse associated documents
  $nbImages=0;
  $nbMovies=0;
  $nbAbstract=0;
  $nbPDFPS=0;
  $errorMessage="";
  
  $dresult = sqlQuery("SELECT type FROM docs,publidocs where docs.id=publidocs.idDoc AND publidocs.idPubli=$id");
  while ($dresult && $drow=mysql_fetch_array($dresult))
    {
      $type=$drow["type"];
      if ($type=="IMG") $nbImages++;
      else if ($type=="MOV") $nbMovies++;
      else if ($type=="ABS") $nbAbstract++;
      else if ($type=="PDF" || $type=="PS") $nbPDFPS++;
    }
  if ($nbPDFPS==0) $errorMessage.="No PDF or PS &nbsp; ";
  if ($nbImages==0) $errorMessage.="No image &nbsp; ";
  if ($nbAbstract==0) $errorMessage.="No abstract &nbsp; ";
  // if ($nbImages>12) $errorMessage.="Too many images ($nbImages&gt;12)! &nbsp; ";
  // if (($nbImages+$nbMovies)>18) $errorMessage.="Too many images/movies (".($nbImages+$nbMovies)."&gt;18)! &nbsp; ";

  if (!empty($errorMessage))
    {
      $href="$public_path/$row[year]/$row[bibTex]";
      echo "<a href='$href' target='_top'>$row[bibTex]</a> &nbsp; $errorMessage<br />\n";
    }
}
?>

<p>Done</p>

</div>
</body>
</html>
