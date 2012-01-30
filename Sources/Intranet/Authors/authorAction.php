<?
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");
?>
<html>
 <head>
  <link rel="stylesheet" href="<?echo $css_path;?>/backoffice.css" type="text/css" />
 </head>
 <body>

<?php
if (isset($_GET))
{
  if (isset($_GET["id"])) $id = $_GET["id"];
  if (isset($_GET["action"])) $action = $_GET["action"];
}

if (empty($action))
{
  die("Internal error - no action provided");
}

$first    = $_GET["first"];
//$last     = strtoupper($_GET["last"]);
$last     = $_GET["last"];
$mail     = $_GET["mail"];if (empty($mail)) $mail="";
$org      = $_GET["org"];if (empty($org)) $org="";
$tel      = $_GET["tel"];if (empty($tel)) $tel="";
$bureau   = $_GET["bureau"];if (empty($bureau)) $bureau="";
$precisions = $_GET["precisions"];if (empty($precisions)) $precisions="";
$position = $_GET["position"];if (empty($position)) $position="0";
//$project  = $_GET["project"];if (empty($project)) $project="0";
$statut   = $_GET["statut"];if (empty($statut)) $statut="actual";
$url      = $_GET["url"];if (empty($url)) $url="";

intranetSqlConnect();

if ($action=="add")
{
  // Check author does not exist yet
  $result = sqlQuery("SELECT id, first, last FROM authors WHERE last like '$last' AND first like '$first' ORDER BY last,first ASC");
  if (mysql_num_rows($result) == 0)
    {
      //if (empty($url))
	//$result = sqlQuery("INSERT INTO authors (first, last) VALUES ('$first', '$last')");
      //else
	$result = sqlQuery("INSERT INTO authors (first,last,mail,org,tel,bureau,precisions,position,statut,url) VALUES ('$first','$last','$mail','$org','$tel','$bureau','$precisions','$position','$statut','$url')");
      $id = mysql_insert_id();
    }
  else
    {
      echo "  Author is allready in database<br/>\n";
      $result = 1;
    }
}
else if ($action=="update")
  $result = sqlQuery("UPDATE authors SET first='$first', last='$last',mail='$mail',org='$org',tel='$tel',bureau='$bureau',precisions='$precisions',position='$position',statut='$statut', url='$url' WHERE id=$id");
else if ($action=="delete")
  $result = sqlQuery("DELETE FROM authors WHERE id=$id");
else
{
  $result = 0;
  $action = "perform action $action";
}

echo "<div=class'actionResult'>\n";

echo "<h1>Result of your command</h1>\n";

if ($result)
  echo "  $action successfully performed";
else
  echo "Error : Unable to $action";

echo " - <a href='author.php?id=$id'>Back</a>";
echo "</div>\n";

?>
