<?php
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/no_way.php");
?>
<html>
<head>
 <title>Basilic backOffice - Team</title>
</head>
<frameset cols="190,*" frameborder="YES">
  <frame name="menu" src="menuTeam.php">
  <frame name="edit" src="../intro.html">
</frameset>
</html>
