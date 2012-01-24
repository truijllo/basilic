<?php
session_start();

require("getLanguage.php");
switch($lg)
{
	case "sp":
	$txtDocs="Im&aacute;genes y pel&iacute;culas";
	$txtSeeAlso="Vea tambi&eacute;n";
	$txtBibtex="Referencias de BibTex";
	$txtOtherPubli="Otras publicaciones en la base de datos";
	$txtDownload="Descargue la publicaci&oacute;n";
	break;
	case "de":
	$txtDocs="Bilder und Kinos";
	$txtSeeAlso="Sieh auch";
	$txtBibtex="BibTex Hinweise";
	$txtOtherPubli="Andere Publikationen in der Datenbank";
	$txtDownload="Downloaden Sie die Publikation";
	break;
	case "fr":
	$txtDocs="Images et films";
	$txtSeeAlso="Voir aussi";
	$txtBibtex="R&eacute;f&eacute;rences BibTex";
	$txtOtherPubli="Autres publications dans la base";
	$txtDownload="T&eacute;l&eacute;charger la publication";
	break;
	case "en":
	$txtDocs="Images and movies";
	$txtSeeAlso="See also";
	$txtBibtex="BibTex references";
	$txtOtherPubli="Other publications in the database";
	$txtDownload="Download the publication";	
	break;
}

# Publication id
if ((isset($_GET)) && (!empty($_GET['id'])))
  $id=$_GET["id"];

if (empty($id))
  die("No publication id given. Aborting");

require_once("utils.php");

sqlConnect();

# Retrieve publication data
$result = sqlQuery("SELECT * FROM publis WHERE publis.id=".$id);
$row = mysql_fetch_array($result) or die("Error : no publication found");

# Title and header
$title="$row[title]";
$css="$local_server$css_path/publi.css";
$metaKeywords=$row["keywords"];
$metaDescription=$row["title"];

include("header.php");
	
require("publiUtils.php");

echo "<h1>$row[title]</h1>\n\n";

echo "<div class='head'>\n";

# Authors
echo "<div class='author'>\n";
printAuthors($id);
echo "\n </div>\n";


# conference, publisher, journal, booktitle, etc
echo "<div class='conf'>\n  ";
printConference($row);
echo " </div>\n";

$year = $row["year"];
$bibtex = $row["bibTex"];
$publiDir="$year/$bibtex";
$path="$public_path/$publiDir";
$readFilePath="$_SERVER[DOCUMENT_ROOT]/$path";


if($_SESSION["Affimg"]==0)
{
if (basilic_rights("files")) $criterion = ""; else $criterion = "AND protect='public'";
# Associated documents.
$dresult = sqlQuery("SELECT publis.id,docs.type,source,size,sizeX,sizeY,year,bibtex,protect FROM publis,docs,publidocs where publis.id=publidocs.idPubli AND docs.id=publidocs.idDoc AND publidocs.idPubli=$id $criterion ORDER BY type ASC,source ASC");
while ($dresult && $drow=mysql_fetch_array($dresult))
{
  if ($drow["protect"] == "public") $add_path = ""; else $add_path = "$protected/";
  $type = $drow["type"];
  $src  = $drow["source"];

  $size = $drow["size"];
  if ($size>1000000)
    $sizeText="[".(floor(1*$size/100000)/10)."Mo]";
  else
    $sizeText="[".floor(1*$size/1000)."Ko]";

  if ($type=="PDF") $docs.="   <a href='$local_server$public_path/$drow[year]/$drow[bibtex]/$add_path$src'><img src='$local_server$images_path/pdf.png' alt='$src $sizeText'/></a> &nbsp;\n";
  if ($type=="PPT") $docs.="   <a href='$local_server$public_path/$drow[year]/$drow[bibtex]/$add_path$src'><img src='$local_server$images_path/ppt.png' alt='$src $sizeText'/></a> &nbsp;\n";
  if ($type=="PS")  $docs.="   <a href='$local_server$public_path/$drow[year]/$drow[bibtex]/$add_path$src'><img src='$local_server$images_path/ps.png' alt='$src $sizeText'/></a> &nbsp;\n";
  if ($type=="TXT")  $docs.="   <a href='$local_server$public_path/$drow[year]/$drow[bibtex]/$add_path$src'><img src='$local_server$images_path/txt.png' alt='$src $sizeText'/></a> &nbsp;\n";

  if (($type=="IMG") || ($type=="MOV"))
    {
      $ratio=1.0*$drow["sizeX"]/$drow["sizeY"];
      $h=60.0;
      $w=$h*$ratio;
      $pt=13;
      if ($w>80)
	{
	  $w=80;
	  $h=$w/$ratio;
	  $pt=13 + (60-$h)/2.0;
	}

      if ($type=="MOV")
	$class=" movie";

      $w=floor($w);
      $h=floor($h);
      $pt=floor($pt)."px";

     $imgMovies .= "  <div class='thumb$class'><div style='padding-top:$pt'><a href='$local_server$public_path/$drow[year]/$drow[bibtex]/$add_path$src'>\n";
     $imgMovies .= "  <img src='$path/.thumbs/$src.jpg' width='$w' height='$h' alt='$src $sizeText'/></a></div></div>\n";
    }

  if ($type=="INFO")
    {
    if (empty($info))
      $info=$src; // choose first info file
    else
      if (strstr($src,".$lg.html"))
	$info=$src; // replace if adequate language is found
    }
  
  if ($type=="ABS")
    if (empty($abstract))
      $abstract=$src; // chose first abstract
    else
      if (strstr($src,".$lg.html"))
	$abstract=$src; // replace if adequate language is found
}
}


# Associated documents : pdf, ps and ppt first
if (!empty($docs))
{
  echo "<div class='pdfps'>\n";
  echo "  $txtDownload : \n$docs";
  echo " </div>\n";
}

echo "</div>\n\n"; // head

# Abstract
if (!empty($abstract))
{
  echo "<div class='abstract'>\n";
  readfile("$readFilePath/$abstract");
  echo "\n</div>\n\n";
}

# Images and movies
if (!empty($imgMovies))
{
  echo "<h2>$txtDocs</h2>\n";
  echo "<div class='imgMovies'>\n";
  echo "$imgMovies";
  echo "</div>\n";
  echo "<div class='endthumbs'>&nbsp;</div>\n\n";
}

# See also
if (!empty($info))
{
  echo "<h2>$txtSeeAlso</h2>\n";
  echo "<div class='seeAlso'>\n";
  readfile("$readFilePath/$info");
  echo "</div>\n\n";
}

# BibTex
echo "<h2>$txtBibtex</h2>\n";
printBibTex($row,1);


# Other publication by the same authors
$aresult = sqlQuery("SELECT * FROM authors,publiauthors where authors.id=publiauthors.idAuthor AND publiauthors.idPubli=$id ORDER BY rank ASC");
while ($aresult && $arow=mysql_fetch_array($aresult))
{
  $aid=$arow["id"];
  $nresult = sqlQuery("SELECT COUNT(*) as nbpubli FROM publiauthors WHERE idAuthor=$aid");
  $nrow=mysql_fetch_array($nresult);
  $nb=$nrow["nbpubli"];
  if ($nb>1)
    $otherPubli .= " <a href='$local_server$public_path/index.php?idAuthor=$arow[id]'>&raquo; $arow[first] $arow[last]</a><br />\n";
}

if (!empty($otherPubli))
{
  echo "<h2>$txtOtherPubli</h2>\n";
  echo "<div class='otherPubli'>\n";
  echo "$otherPubli";
  echo "</div>\n\n";
}

# Edit and update buttons
# if ($local)
if (basilic_rights("edit"))
{
  echo "<div align='right'>\n";
  echo "  <a href='$local_server$intra_path/Publications/index.php?id=$id'><img src='$local_server$images_path/editPubli.png'/></a>\n";
  echo "</div>\n\n";
}

include("footer.php");
?>
