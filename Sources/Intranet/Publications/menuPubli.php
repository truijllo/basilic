<?php
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");
?>
<html>
<head>
  <meta http-equiv='pragma' content='no-cache'>
  <meta http-equiv='expires' content='0'>
  <link rel="stylesheet" href="<?echo $css_path;?>/backoffice.css" type="text/css" />
</head>

<body>
<?php
sqlConnect();
$year="";
$updateYear="";
$yearFile="";
if (isset($_GET))
{
  if (!empty($_GET['year'])) $year=$_GET["year"];
  if (!empty($_GET['yearFile'])) $yearFile=$_GET["yearFile"];
  if (!empty($_GET['yearMAJ'])) $updateYear=$_GET["yearMAJ"];
}
?>

<div class='menu'>

<h2>Publications</h2>



<div class='submenu'>
  <h3>Add a publication</h3>  
  <form action="publi.php">
    <input type="button" value="Add" onClick="parent.edit.location.href='publi.php';">
  </form>
<?php /*</div>

<div class='submenu'>*/?>
  <h3>Edit a publication</h3>
  <form action="menuPubli.php" name="yearForm">
    Select a year<br/>
    <select name="year" size="1" onChange='document.forms["yearForm"].submit();'>
     <option value=""> &nbsp; --- </option>
<?php
 
mysql_select_db($database) or die("impossible d'acc&eacute;der &agrave; la base de donn&eacute;e basilic\n");
$yresult = sqlQuery("SELECT DISTINCT year from publis, equip, publiequip $tables WHERE publiequip.idEquip=equip.id and publiequip.idPubli=publis.id ORDER BY year DESC");
$i = 0;
while ($yresult && $yrow=mysql_fetch_array($yresult))
{
  $yearMem[$i] = $yrow;
  echo "     <option value='$yrow[year]'";
  if ($yrow["year"]==$year) echo " selected";
  echo ">$yrow[year]</option>\n";
  $i++;
}
?>
    </select>
  </form>

<?php
if ($year!="" or $yearFile!="") 
{
  if ($year!="") $y_var = $year; else $y_var = $yearFile;
  
  $result = mysql_query("SELECT DISTINCT publis.id,bibTex,year from publis,equip,publiequip WHERE year=$y_var and publiequip.idEquip=equip.id AND publiequip.idPubli=publis.id ORDER BY bibTex ASC");
  $i = 0;
  while ($result && $row=mysql_fetch_array($result)) {
    $publiMem[$i] = $row;
    $i++;
  }
}


if ($year!="")
{
  echo " <form action='publi.php' target='edit' name='publiForm'>\n";
  echo "  Publications $year<br/>\n";
  echo "  <select name='id' size='10' onChange='document.forms[\"publiForm\"].submit();'>\n";
  foreach ($publiMem as $row) {
    echo "   <option value='".$row["id"]."'>".$row["bibTex"]."</option>\n";
  }
  echo "  </select>\n";
  echo "  <br/>\n  <input type='submit' value='Edit'>\n";
  echo " </form>";
}
?>
</div>

<div class='submenu'>
  <h3>Manage Files/Abstracts</h3>
  <form action="menuPubli.php" name="yearFileForm">
	Select a year<br />
	<select name="yearFile" size="1" onChange='document.forms["yearFileForm"].submit();'>
     	<option value=""> &nbsp; --- </option>
<?
foreach ($yearMem as $yrow)
{
  echo "     <option value='$yrow[year]'";
  if ($yrow["year"]==$yearFile) echo " selected";
  echo ">$yrow[year]</option>\n";
}
?>
	</select>
  </form>

<? if (!empty($yearFile)) {
	echo "<form action='publiFiles.php' target='edit' name='publiFileForm' method='POST'>\n";
  	echo "  Publications $yearFile<br/>\n";
	echo "  <select name='id' size='10' onChange='document.forms[\"publiFileForm\"].submit();'>\n";
  echo "publiMem:"; print_r($publiMem);
	foreach ($publiMem as $row)
    	  echo "   <option value='".$row["id"]."'>".$row["bibTex"]."</option>\n";
  	echo "  </select>\n";
  	echo "  <br/>\n  <input type='submit' value='Edit'>\n";
	echo "</form>\n";	
   }
?> 
<br />
<input type="button" value="Refresh" onClick="window.location.href='menuPubli.php';">
 
</div>
</div>

<?php include("../commonMenu.php") ?>

</body>
</html>
