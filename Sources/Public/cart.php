<?
session_start();
require_once("utils.php");

$begin =  "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n
<html>\n
  <head>\n
  	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n
	<link rel=\"stylesheet\" href=\"$css_path/main.css\" type=\"text/css\">\n
	<title>Selection</title>
  </head>\n
<body>";

$close = "<script type=\"text/javascript\" language=\"JavaScript\">\n
window.setTimeout('self.close();',1100);\n
</script>\n";

if (!empty($_GET["add"])) $add = $_GET["add"]; else $add=-1;
if (!empty($_GET["del"])) $del = $_GET["del"]; else $del=-1;
if (!empty($_GET["empty"])) $empty = $_GET["empty"]; else $empty=-1;


function filter($val) {
	global $del;
	
	return ($val != $del);
}

if ($add > 0) { // add publi to cart

	print $begin;
	print $close;
	if ($_SESSION["cart"] == "") {
	  echo "<p class='SousTitrePage'>La publication n°($add) a &eacute;t&eacute; ajout&eacute;e &agrave; votre s&eacute;lection</p>";
	  $_SESSION["cart"] = $add;
	}
	else  {
	  $tab = explode(",",$_SESSION[cart]);
	  if (!array_search("$add",$tab)) {
	  	$_SESSION["cart"] .= ",$add";
		echo "<p class='SousTitrePage'>La publication n°($add) a &eacute;t&eacute; ajout&eacute;e &agrave; votre s&eacute;lection</p>";
	  }
	  else 
		echo "<p class='SousTitrePage'>La publication n°($add) est d&eacute;j&agrave; dans votre s&eacute;lection</p>";
	}
	echo "<p class='Texte' style='text-align:right;'><small>cette fen&ecirc;tre va se fermer automatiquement</small></p>";
}

else {  // print selected publications

	if ($empty > 0) { // empty list
	  $_SESSION["cart"] = "-1";
	}
	if ($del > 0) { // delete id
	  $tab = explode(",",$_SESSION["cart"]);
	  $newTab = array_filter($tab, "filter");
	  //print_r($newTab);
	  $_SESSION["cart"] = implode(",",$newTab);
	}
	
	if (empty($_SESSION["cart"])) $_SESSION["cart"] = "-1";
	header("Location: $public_path/?search=$_SESSION[search]&Hide=$_SESSION[Hide]&list=$_SESSION[cart]");
}
	

