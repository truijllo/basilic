<?php
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

$sigle = $_GET["sigle"];
$description  = $_GET["description"];

intranetSqlConnect();

if ($action=="add")
{
  // Check team does not exist yet
  $result = sqlQuery("SELECT id, sigle, description FROM equip WHERE SOUNDEX(sigle)=SOUNDEX('$sigle') AND SOUNDEX(description)=SOUNDEX('$description') ORDER BY sigle,description ASC");
  if (mysql_num_rows($result) == 0)
    {
      $result = sqlQuery("INSERT INTO equip (sigle,description) VALUES ('$sigle', '$description')");
      $id = mysql_insert_id();
    }
  else
    {
      echo "  team is allready in database<br/>\n";
      $result = 1;
    }
}
else if ($action=="update")
  $result = sqlQuery("UPDATE equip SET sigle='$sigle', description='$description' WHERE id=$id");
else if ($action=="delete")
  $result = sqlQuery("DELETE FROM equip WHERE id=$id");
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

echo " - <a href='team.php?id=$id'>Back</a>";
echo "</div>\n";

?>
