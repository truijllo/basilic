<?php
require_once("utils.php");
include("header.php");

// Defines the $lg variable
require("getLanguage.php");

switch($lg)
{
	/* TODO traduce in german and spanish */
	case "de":
	case "en":
	$txtNoway="You are not allowed to access these pages.";
	break;
	case "fr":
	$txtNoway="Vous n'&ecirc;tes pas autoris&eacute; &agrave; acc&eacute;der &agrave; ces pages.";
	break;
}
	

echo "<br/><br/><br/><br/>";
echo "<p align=\"center\">$txtNoway</p>";

include("footer.php");
?>
