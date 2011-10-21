<?php
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");
?>
<html>
<head>
 <title>Basilic backOffice - Authors</title>
</head>
<frameset cols="220,*" frameborder="YES">
  <frame name="menu" src="menuAuthor.php">
  <frame name="edit" src="../intro.html">
</frameset>
</html>
