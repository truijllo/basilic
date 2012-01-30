<?php
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
?>
<html>
<head>
  <meta http-equiv='pragma' content='no-cache'>
  <meta http-equiv='expires' content='-1'>
  <link rel="stylesheet" href="<?echo $css_path;?>/backoffice.css" type="text/css" />
</head>

<body>

<div class='menu'>

<h2>Team</h2>

<div class='submenu'>
  <h3>Add a team</h3>
  <form action="team.php" target="edit" name="addTeam">
    <input type="button" value="Add" onClick="parent.edit.location.href='team.php';">
  </form>
</div>

<div class='submenu'>
 <h3>Edit team</h3>
 <form action="team.php" target="edit" name="editTeam">
  <select name="id" size="10" onChange='document.forms["editTeam"].submit();'>
  <?php
   sqlConnect();
   $aresult = sqlQuery("SELECT id, sigle FROM equip order by show_order DESC, sigle ASC ");
   while ($aresult && $arow = mysql_fetch_array($aresult))
     echo "  <option value='".$arow["id"]."'>".$arow["sigle"]." ".$arow["description"]."</option>\n";
  ?>
  </select>
  <br />
  <br />
  <input type="button" value="Refresh" onClick="window.location.href='menuTeam.php';">
 </form>
</div>

</div>

<?php include("../commonMenu.php") ?>

</body>
</html>
