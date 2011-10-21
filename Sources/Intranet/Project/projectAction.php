<?
session_start();
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

$name      = $_GET["prname"];
$abbrv     = $_GET["prabbrv"];
$shortdesc = $_GET["prshortdesc"];
$desc      = $_GET["prdesc"];
$link      = $_GET["prlink"];
$logo      = $_GET["logo"];

intranetSqlConnect();

if ($action=="add")
{
  // Check project does not exist yet
  $result = sqlQuery("SELECT id, prname, prabbrv FROM projects WHERE SOUNDEX(prname)=SOUNDEX('$name') AND SOUNDEX(prabbrv)=SOUNDEX('$abbrv') ORDER BY prname,prabbrv ASC");
  if (mysql_num_rows($result) == 0)
    {
      $result = sqlQuery("INSERT INTO projects (prname,prabbrv,prshortdesc,prdesc,prlink,prlogo) VALUES ('$name','$abbrv','$shortdesc','$desc','$link','$logo')");
      $id = mysql_insert_id();
    }
  else
    {
      echo " Poject is allready in database<br/>\n";
      $result = 1;
    }
}
else if ($action=="update")
  $result = sqlQuery("UPDATE projects SET prname='$name', prabbrv='$abbrv', prshortdesc='$shortdesc', prdesc='$desc', prlink='$link', prlogo='$logo' WHERE id=$id");
else if ($action=="delete")
  $result = sqlQuery("DELETE FROM projects WHERE id=$id");
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

echo " - <a href='project.php?id=$id'>Back</a>";
echo "</div>\n";

?>
