<?php
$lg=$_GET['lg'];
SetCookie("preferedLanguage","$lg", NULL, '/');
$hote=$_SERVER["SERVER_NAME"];
$page=$_GET['page'];
header("Location: http://$hote$page");
?>
