<?php
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");
?>
<html>
<head>
 <title>Basilic backOffice - Project</title>
</head>
<frameset cols='190,*' frameborder='YES'>
  <frame name='menu' src='menuProject.php'>
  <frame name='edit' src='../intro.html'>
</frameset>
</html>";
