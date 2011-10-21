<?php
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");
?>
<html>
<head>
  <meta http-equiv='pragma' content='no-cache'>
  <meta http-equiv='expires' content='-1'>
  <link rel="stylesheet" href="<?echo $css_path;?>/backoffice.css" type="text/css" />
</head>

<body>

<div class='menu'>

<h2>Project</h2>

<div class='submenu'>
  <h3>Add a project</h3>
  <form action="project.php" target="edit" name="addProject">
    <input type="button" value="Add" onClick="parent.edit.location.href='project.php';">
  </form>
</div>

<div class='submenu'>
 <h3>Edit project</h3>
 <form action="project.php" target="edit" name="editProject">
  <select name="id" size="10" onChange='document.forms["editProject"].submit();'>
  <?php
   sqlConnect();
   $aresult = sqlQuery("SELECT id,prname, prabbrv FROM projects order by id");
   while ($aresult && $arow = mysql_fetch_array($aresult)) {
  	if ($arow["prname"] != "None")
     	  echo "  <option value='".$arow["id"]."'>".$arow["prabbrv"]."</option>\n";
   }
  ?>
  </select>
  <br />
  <br />
  <input type="button" value="Refresh" onClick="window.location.href='menuProject.php';">
 </form>
</div>

</div>

<?php include("../commonMenu.php") ?>

</body>
</html>
