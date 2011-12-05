<?php
// This script is callable with following javascript code to include results in an external page:

// <script type="text/javascript" src="http://path_to_your_application/research/publications/direct_query.php?idAuthor=1128"/></script>

session_start();
if (isset($_GET["search"])) $_SESSION["search"]=$_GET["search"];
// PATCH !!
$_SESSION["search"]=1;
if (isset($_GET["Hide"])) $_SESSION["Hide"]=$_GET["Hide"];
if (isset($_GET["Affimg"])) $_SESSION["Affimg"]=$_GET["Affimg"];

// Defines the $lg variable
require("getLanguage.php");

switch($lg)
{
	case "sp":
	$txtIn="en";
	$txtNoResult="No se encontr&oacute; ningunas publicaciones";
	$txtCloseName="Faltas de ortograf&iacute;a conocidos posibles";
	$txtDisplayAll="Exhiba la lista completa";
	$txtEquip="Equipa";
	//$txtResearch_adv="Opci&oacute;n de fijaci&oacute;n";
	$txtBrowseOff="Volver al modo de la navegación";
	$txtBrowse="Tu selección";
	$txtResearch="Efectuar una b&uacute;squeda";
	$txtHidSear="Esconder el men&uacute; de b&uacute;squeda";
	$txtHide="Oculte los men&uacute;s";
	$txtShow="Muestre los men&uacute;s";
	$txtHideImg="Esconder las im&aacute;genes";
	$txtShowImg="Fijar las im&aacute;genes";
	$txtDownloadList="Cargar la lista";
	$txtEmptyCart="TODO";
	$txtOptionAffOff="Enmascarar las opciones de fijaci&oacute;n";
	break;
	case "de":
	$txtIn="in";
	$txtNoResult="Keine Publikationen wurden gefunden";
	$txtCloaseName="M&otilde;gliche Namensfehlbuchstabierungen";
	$txtDisplayAll="Die komplette Liste anzeigen";
	$txtPubli="Publikationen";
	$txtEquip="Team";
	//$txtResearch_adv="Plakatoption";
	$txtBrowseOff="zur Navigationsmethode";
	$txtBrowse="Ihre Vorwähler";
	$txtResearch="Die Suche ändern";
	$txtHidSear="Forschungsmen&uuml; verstecken";
	$txtHide="Druckbares Format";
	$txtShow="Standardformat";
	$txtHideImg="Bilder verstecken";
	$txtShowImg="Bilder anzeigen";
	$txtDownloadList="Die Liste einspeichern";
	$txtEmptyCart="Ihre Vorwähler leeren";
	$txtOptionAffOff="Plakatoptionen maskieren";
	break;
	case "fr":
	$txtIn="en";
	$txtNoResult="Aucun r&eacute;sultat n'a &eacute;t&eacute; trouv&eacute;.";
	$txtCloseName="Noms avec une orthographe proche ";
	$txtDisplayAll="Afficher toute la liste";
	$txtEquip="Equipe";
	//$txtResearch_adv="Option d'affichage";
	$txtBrowseOff="Retour au mode navigation";
	$txtBrowse="Votre s&eacute;lection";
	$txtResearch="Modifier la recherche";
	$txtHidSear="Cacher le menu de recherche";
	$txtHide="Format imprimable";
	$txtShow="Format standard";
	$txtHideImg="Cacher les images";
	$txtShowImg="Afficher les images";
	$txtDownloadList="Telecharger la liste";
	$txtEmptyCart="Vider votre s&eacute;lection";
	$txtOptionAffOff="Masquer les option d'affichage";
	break;
	case "en":
	$txtIn="in";
	$txtNoResult="No publications were found.";	
	$txtCloseName="Possible name misspellings";
	$txtDisplayAll="Display the complete list";
	$txtEquip="Team";
	//$txtResearch_adv="Option of posting";
	$txtBrowseOff="Back to navigation mode";
	$txtBrowse="Your selection";
	$txtResearch="Modify search";
	$txtHidSear="Hide the research menu";
	$txtHide="Printable format";
	$txtShow="Standard format";
	$txtHideImg="Hide the images";
	$txtShowImg="Show the images";
	$txtDownloadList="Download the list";
	$txtEmptyCart="Empty your selection";
	$txtOptionAffOff="Mask the options of posting";
	break;
}

if (!isset($annee1))	$annee1    = $_GET["annee1"];
if (!isset($annee2))	$annee2    = $_GET["annee2"];
if ($_GET["team"] != "") $team = $_GET["team"]; else $team = "toutes";
if (!isset($project))	$project   = $_GET["project"];
if (!isset($author))    $author    = $_GET["author"]; if (empty($author)) $author = $_SESSION['author'];
if (!isset($display))   $display   = $_GET["display"];
/*if (!isset($full))      $full 	   = $_GET["full"];
if (!isset($thes))      $thes 	   = $_GET["thes"];
if (!isset($all_p))	$all_p	   = $_GET["all_p"];
if (!isset($proc))      $proc 	   = $_GET["proc"];
if (!isset($arti))      $arti 	   = $_GET["arti"];
if (!isset($book))      $book 	   = $_GET["book"];*/
if (!isset($opt_file))     $opt_books    = $_GET["opt_file"];
if (!isset($opt_books))    $opt_books    = $_GET["opt_books"];
if (!isset($opt_articles)) $opt_articles = $_GET["opt_articles"];
if (!isset($opt_conf))     $opt_conf     = $_GET["opt_conf"];
if (!isset($opt_misc))     $opt_misc     = $_GET["opt_misc"];
if (!isset($opt_thesis))   $opt_thesis   = $_GET["opt_thesis"];
if (!isset($opt_contrat))  $opt_contrat  = $_GET["opt_contrat"];
if (!isset($opt_interne))  $opt_interne  = $_GET["opt_interne"];
if (!isset($idAuthor))  $idAuthor  = $_GET["idAuthor"];
if (!isset($nbPerPage)) $nbPerPage = $_GET["nbPerPage"];
if (!isset($pg))        $pg 	   = $_GET["pg"];
if (!isset($title))     $title 	   = $_GET["title"];
if (!isset($year))      $year 	   = $_GET["year"];  
if (!isset($list))      $list 	   = $_GET["list"];  
if (!isset($searchType_author)) $searchType_author = $_GET["searchType_author"];
if (!isset($searchType_title)) $searchType_title = $_GET["searchType_title"];

if (empty($pg)) $pg = 1;
if (empty($nbPerPage)) $nbPerPage = 20;

$option["annee1"]    = $annee1;
$option["annee2"]    = $annee2;
$option["team"]     = $team;
$option["project"]   = $project;
$option["author"]    = $author;
$option["display"]   = $display;
/*$option["full"]      = $full;
$option["thes"]      = $thes;
$option["all_p"]     = $all_p;
$option["proc"]      = $proc;
$option["arti"]      = $arti;
$option["book"]      = $book;*/
$option["opt_file"]	  = $opt_file;
$option["opt_books"]      = $opt_books;
$option["opt_articles"]   = $opt_articles;
$option["opt_conf"]       = $opt_conf;
$option["opt_misc"]       = $opt_misc;
$option["opt_thesis"] 	  = $opt_thesis;
$option["opt_contrat"]    = $opt_contrat;
$option["opt_interne"]    = $opt_interne;
$option["idAuthor"]  = $idAuthor;
$option["nbPerPage"] = $nbPerPage;
$option["pg"]        = $pg;
$option["title"]     = $title;
$option["year"]      = $year;
$option["list"]      = $list;

require("utils.php");
sqlConnect();

// Search for author name from idAuthor
if (!empty($idAuthor))
{
  $resultIdAuthor = sqlQuery("SELECT first, last FROM authors WHERE id=$idAuthor");
  $rowIdAuthor=mysql_fetch_array($resultIdAuthor);
  $author=$rowIdAuthor["last"];
  if ($_SESSION["search"] == "") $_SESSION["search"] = "0";
}

global $OWN_name;
$labName=$OWN_name;

if(!empty($team)&&$team!="toutes")
{
	$resultIdTeam = sqlQuery("SELECT sigle from equip WHERE id = $team");
	$rowIdTeam = mysql_fetch_array($resultIdTeam);
  	$subtitle="- $txtEquip $rowIdTeam[0]";
}

// supress every space caracter at the begining or at the end of the author value
// if not, it can generate a bad sql request
while ($author[0] == ' ') {
  $author = substr($author,1,strlen($author));
  $option[author] = $author;
}
while ($author[strlen($author)-1] == ' ') {
  $author = substr($author,0,strlen($author)-1);
  $option[author] = $author;
}

if (empty($author) or (count(explode(" ",$author)) > 1))
  $titleName=$labName;
else
{
	switch($lg)
	{
		case "sp":
    		$titleName="$author en $labName";
		break;
		case "de":
		$titleName="von $author bei $labName";
		break;
		case "fr":
		$titleName="de ".strtoupper(substr($author,0,1)).strtolower(substr($author,1,strlen($author)))." au $labName";
		break;
		case "en":
		$titleName="$author's";
		break;
  	}
}
switch($lg)
{
	case "sp":
	if ($display=="gallery")
		$title="Galer&iacute;a de im&aacute;genes de $titleName";
	else if (!empty($_GET['list']))
		$title="Your selection";
	else
		$title="Publicaciones de $titleName";
	break;
	case "de":
	if ($display=="gallery")
		$title="Galerie von Bildern $titleName";
	else if (!empty($_GET['list']))
		$title="Ihre Selection";
	else
		$title="Publikationen $titleName";
	break;
	case "fr":
	if ($display=="gallery")
		$title="Galerie d'images $titleName";
	else if (!empty($_GET['list']))
		$title="Votre s&eacute;lection";
	else
		$title="Publications $titleName";
	break;
	case "en":
	if ($display=="gallery")
		$title="$titleName image gallery";
	else if (!empty($_GET['list']))
		$title="Your selection";
	else
		$title="$titleName Publications";									
	break;
}
//if (!empty($author)) $title.=" with $labName";
//if (!empty($year) && ($year != -1)) $title .= " $txtIn $year";
     
$css="$local_server$css_path/listpubli.css";

// include("header_hide.php");

unset($subtitle);
unset($title); // interfers with form parameters
global $option_cherche;
$option["title"]="";
$option["title"]=$_GET["title"];
$title=$option["title"];
$option_cherche=$option;

// supress every space caracter at the begining or at the end of the title value
// if not, it can generate a bad sql request
while ($title[0] == ' ') {
  $title = substr($title,1,strlen($title));
  $option[title] = $title;
}
while ($title[strlen($title)-1] == ' ') {
  $title = substr($title,0,strlen($title)-1);
  $option[title] = $title;
}

require("query_publiUtils.php");

$result = bibQueryResults($option);
$nbPublis=mysql_num_rows($result);

// Search engine
while(list($key,$val)=each($option))
{
	$lp.="$key=$val&";
}
$lp=substr($lp,0,strlen($lp)-1);
//fin lien
//echo "<br>";

// echo "<br><br>";


function pageIndex()
{
  global $option_cherche;
  global $nbPublis;
  global $nbPerPage;
  global $pg;
  global $txtDisplayAll;
  global $txtEmptyCart;
  global $txtDownloadList;
  global $lg;
  global $local_server, $public_path, $frame;

  while(list($key,$val)=each($option_cherche))
  {
  	$tab.="$key,$val;";
  }
  $tab=substr($tab,0,strlen($tab)-1);

  // $res = "<div class='pageMenu'>\n";
  // $res = 'document.write("'+"<div class='pageMenu'>\n"+'");';
  // print 'document.write("'+"<div class='pageMenu'>\n"+'");';
  //print 'document.write("'."<div class='pageMenu'>".'");';
  JSPrintMsg("<div class='pageMenu'>");
  

  if ($nbPublis>$nbPerPage)
    {
      if (empty($_SERVER["QUERY_STRING"]))
	$param="?pg=";
      else
	{
	  $param=ereg_replace("&pg=[0-9]*", "", "&".$_SERVER["QUERY_STRING"]);
	  $param=ereg_replace("&[a-zA-Z]*=&", "&", $param);
	  $param=ereg_replace("&[xy]=0", "", $param);
	  if (empty($param))
	    $param="?pg=";
	  else
	    $param=ereg_replace("^&", "?", $param)."&pg=";
	}

      $href="  <a href='$local_server$public_path/$frame$param";



    }
  JSPrintMsg("</br>");
  JSPrintMsg("</div>");
}

$pgIndex=pageIndex();


// If no results were found
if ($nbPublis==0)
{
  JSPrintMsg("<div class='noresult'>");
  JSPrintMsg("<h2>$txtNoResult</h2>");;

  // Possible wrong author name spelling
  if (!empty($author))
    {
      $param = "";
      if (!empty($title))   $param .= "&amp;title=$title";
      if (!empty($year))    $param .= "&amp;year=$year";
      if (!empty($display)) $param .= "&amp;display=$display";
      if (!empty($full))    $param .= "&amp;full=$full";
      if (!empty($thes))    $param .= "&amp;full=$thes";
      if (!empty($all_p))    $param .= "&amp;full=$all_p";
      if (!empty($proc))    $param .= "&amp;proc=$proc";
      if (!empty($arti))    $param .= "&amp;arti=$arti";
      if (!empty($book))    $param .= "&amp;book=$book";
      $param .= "&amp;pg=-1";

      // Find names like author name
      $resultLike = sqlQuery("SELECT DISTINCT id,last,first FROM authors WHERE last LIKE '%$author%' AND last!='$author' ORDER BY last ASC");
      while ($resultLike && $row=mysql_fetch_array($resultLike))
	{
	  $name=$row["first"]." ".$row["last"];
	  $possibleNames .= "<li><a href='$local_server$public_path/index.php?idAuthor=$row[id]$param'>&raquo; $name</a></li>\n";
	  if (!empty($listids))
	    $listids .= ",".$row["id"];
	  else
	    $listids = $row["id"];
	}


      // And names that soundex like author name
      if (!empty($listids))
	$condition = "AND id NOT IN ($listids)";

      $resultSound = sqlQuery("SELECT DISTINCT id,last,first FROM authors WHERE SOUNDEX(last)=SOUNDEX('$author') $condition AND last!='$author' ORDER BY last ASC");
      $nbSoundex=mysql_num_rows($resultSound);
      while ($resultSound && $row=mysql_fetch_array($resultSound))
	{
	  $name=$row["first"]." ".$row["last"];
	  $possibleNames .= "<li><a href='$local_server$public_path/index.php?idAuthor=$row[id]$param'>&raquo; $name</a></li>\n";
	}

      if (!empty($possibleNames))
	{
	  JSPrintMsg("  $txtCloseName:<br />");
	  JSPrintMsg(" <ul style='list-style-type: none'>\n$possibleNames</ul>");
	}
    }
  JSPrintMsg("</div>");
}

if ($_SESSION["search"] != "") {
  displayResults($result, $option, false);
  JSPrintMsg($pgIndex);
}

// echo "<script type='text/javascript'>\n";
// echo "  document.forms[0].elements[0].focus();\n";
// echo "</script>\n\n";

if($_SESSION["Hide"]==0)
{
	//include("footer.php");
	JSPrintMsg("</div>");
}
?>
