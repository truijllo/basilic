<?php
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");
?>
<html>
<head>
 <title>Basilic backOffice - Publications</title>
</head>
<frameset cols="190,*" frameborder="YES">
  <frame name="menu" src="menuPubli.php">
<? if (!empty($_GET[id])) 
  echo "  <frame name=\"edit\" src=\"publi.php?id=$_GET[id]\">";
else 
  echo "  <frame name=\"edit\" src=\"../intro.html\">";
?>
</frameset>
</html>
