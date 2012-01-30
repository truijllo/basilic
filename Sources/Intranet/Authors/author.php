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

<h1>Author edition</h1>

<div class='inputForm'>
<form action='authorAction.php' name='authorForm'>
 <input type='hidden' name='action' value='NONE' />

<?php 
// If an $id parameter is provided, display and edit the corresponding author.
// Otherwise, edit a new author

// Get record id if it exists
if (isset($_GET) && isset($_GET["id"]))
  $id = $_GET["id"];

sqlConnect();

if (!empty($id))
{
  $result = sqlQuery("SELECT * FROM authors WHERE id=$id");
  if (mysql_num_rows($result)==0)
    die("Error - No author found with id $id - Aborting\n");
  $row=mysql_fetch_array($result);
}
else
{
  $fields=array("first","last","mail","org","tel","bureau","precisions","position","statut","url");
  foreach ($fields as $field)
    $row["$field"]="";
}

$t_obl = array("last","first");
$t_opt = array("mail","org","tel","bureau","precisions","position","statut","url");
$t_opt = array_merge($t_obl, $t_opt);
?>

<script language="javascript">
// Check that all required field are filled
function chk()
{
  d=document.authorForm;
  <?php
  for ($i=0; $i<count($t_obl); $i++)
    echo " if (d.$t_obl[$i].value==''){\n  window.alert('Warning!\\nYou must fill the field \"$t_obl[$i]\".');\n  d.$t_obl[$i].focus();\n  return false;\n }\n";
  ?>
  return true;
}
function add()
{
  a=chk();
  if (a)
    {
      document.authorForm.action.value="add";
      document.authorForm.submit();
    }
}
function update()
{
  a=chk();
  if (a)
    {
      document.authorForm.action.value="update";
      document.authorForm.submit();
    }
}
function del()
{
<?
  if (!basilic_rights("author")) echo "window.alert('Your access only permits putting this author as departed, not to completely supress it.\\n If there is an error, call your webmaster');\n";
  else {
?>
  a=window.confirm("Warning!\nAre you sure you want to DEFINITIVELY delete this record ?\nThis action removes the author from all publications");
  if (a)
    {
      document.authorForm.action.value="delete";
      document.authorForm.submit();
    }
<? } ?>
}
</script>

<?php
$lineNb=0;

function line($str, $width)
{
  global $lineNb;
  global $t_obl;
  global $row;
  global $images_path;
  
  echo " <div class='".(($lineNb==0)?"even":"odd")."'>\n";
  $lineNb = 1-$lineNb;

  // Optionnal or compulsory field
  if (in_array($str,$t_obl))
    {
      $optClass = "required";
      $imgSrc = "$images_path/required.png";
    }
  else
    {
      $optClass = "";
      $imgSrc = "$images_path/empty.png";
    }
  
  echo "  <span class='label $optClass'>$str &nbsp;\n";
  echo "   <img src='$imgSrc' width='6' height='12' alt=''/> &nbsp; </span>\n";
  echo "  <input name='$str' size='".min(50, $width)."' maxlength='$width' value='$row[$str]' />\n";
  switch($str)
  {
 	case "url":
  	  echo "http://....";
	  Break;
	case "tel":
	  echo "+33(0)3.90....";
	  break;
	case "mail":
	  echo "name<b>@</b>organization";
	  break;
  }
  echo " </div>\n";
}

function printOrg($class)
{
	global $row,$id,$images_path;
//	echo $row["org"];
	echo "<div class='$class'>";
	$resu=sqlQuery("select sigle from equip");
	echo "<span class='label '>Team";
	echo "<img src='$images_path/empty.png' width='6' height='12' alt=''/> &nbsp; </span>";
	echo "<select name='org' size='1'>";
	while($table=mysql_fetch_array($resu))
	{
		if ((empty($id) and ($table[0] == 'extern')) OR ($table[0]==$row["org"])) 
			echo "<option selected>$table[0]</option>";
		else
			echo "<option>$table[0]</option>";
	}
	echo "</select></div>\n";
}

function printPosition($class)
{
	global $row,$images_path;
//	echo $row["position"];
	echo "<div class='$class'>";
	$resu=sqlQuery("select ustatus,position_fr from status order by ordre");
	echo "<span class='label '>Fonction";
	echo "<img src='$images_path/empty.png' width='6' height='12' alt=''/> &nbsp; </span>";
	echo "<select name='position' size='1'>";
	while($table=mysql_fetch_assoc($resu))
	{
		if($table["ustatus"]==$row["position"])
			echo "<option value='$table[ustatus]' selected>$table[position_fr]</option>";
		else
			echo "<option value='$table[ustatus]'>$table[position_fr]</option>";
	}
	echo "</select>\n";
}

function printStatus($class)
{
	global $row,$images_path;
//	echo $row["statut"];
	echo "<div class='$class'>";
	echo "<span class='label '>Status";
	echo "<img src='$images_path/empty.png' width='6' height='12' alt=''/> &nbsp; </span>";
	echo "<select name='statut' size='1'>";
	if($row["statut"]=="actual")
		echo "<option selected>actual</option>";
	else
		echo "<option>actual</option>";
	if($row["statut"]=="departed")
		echo "<option selected>departed</option>";
	else
		echo "<option>departed</option>";
	echo "</select>\n";
}

echo " <input type='hidden' name='id' value='$id' />\n";

line("first", 30);
line("last", 30);
line("mail",50);
line("url", 100);
//printOrg("odd");
line("tel",20);
line("bureau",20);
printOrg("even");
printPosition("odd");
//line("position",30);
//line("project",100);
//line("statut",20);
line("precisions",30);
printStatus("even");
//line("url", 100);

echo " <div class='buttons'>\n";
if (!empty($id))
{
  echo "  <input type='button' value=' Update ' onClick='javascript:update();'> &nbsp;\n";
  echo "  <input style='color:red;' type='button' value=' Delete ' onClick='javascript:del();'>\n";
  echo "  <input type='button' value=' Cancel ' onClick='window.location.href=\"../intro.html\"';'> &nbsp;\n";
}
else
  echo "  <input type='button' value=' Add author ' onClick='javascript:add();'>\n";
echo " </div>\n";

echo "</form>\n";
echo " </div>\n\n";
echo "Fields in red must be filled\n\n";

if (!empty($id))
{
echo " <div class='information'>\n";
  echo "List of publications (id=$id) : <br />\n";

  $result = sqlQuery("SELECT id,bibTex,year,title from publis,publiauthors WHERE publiauthors.idAuthor=$id AND publiauthors.idPubli=publis.id ORDER BY year DESC");
  while ($result && $row=mysql_fetch_array($result))
    echo " <a href='$public_path/$row[year]/$row[bibTex]/' target='_top'>".$row["bibTex"]."</a> ".$row["title"]."<br />\n";
echo " </div>\n";
}

?>

</body>
</html>
