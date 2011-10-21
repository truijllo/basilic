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

<h1>Project edition</h1>

<div class='inputForm'>
<form action='projectAction.php' name='projectForm'>
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
  $result = sqlQuery("SELECT * FROM projects WHERE id=$id");
  if (mysql_num_rows($result)==0)
    die("Error - No project found with id $id - Aborting\n");
  $row=mysql_fetch_array($result);
}
else
{
  $fields=array("prshortdesc","prdesc","prname","prabbrv","prlink","prlogo");
  foreach ($fields as $field)
    $row["$field"]="";
}

$t_obl = array("prname","prabbrv");
$t_opt = array("prshortdesc","prdesc","prlink","prlogo");
$t_opt = array_merge($t_obl, $t_opt);
//$t_opt = $t_obl;
?>

<script language="javascript">
// Check that all required field are filled
function chk()
{
  d=document.projectForm;
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
      document.projectForm.action.value="add";
      document.projectForm.submit();
    }
}
function update()
{
  a=chk();
  if (a)
    {
      document.projectForm.action.value="update";
      document.projectForm.submit();
    }
}
function del()
{
<?
  if (!basilic_rights("project")) echo "window.alert('Your access does not permit to completely supress this project.\\n If there is an error, call your webmaster');\n";
  else {
?>
  a=window.confirm("Warning!\nAre you sure you want to DEFINITIVELY delete this record ?");
  if (a)
    {
      document.projectForm.action.value="delete";
      document.projectForm.submit();
    }
<? } ?>
}
</script>

<?php
$lineNb=0;

function line($str,$str_aff, $width)
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
  
  echo "  <span class='label $optClass'>$str_aff &nbsp;\n";
  echo "   <img src='$imgSrc' width='6' height='12' alt=''/> &nbsp; </span>\n";
  echo "  <input name='$str' size='".min(50, $width)."' maxlength='$width' value='$row[$str]' />\n";
  echo " </div>\n";
}

echo " <input type='hidden' name='id' value='$id' />\n";

line("prname","name", 100);
line("prabbrv","abrevation", 30);
line("prshortdesc","short desc",50);
line("prdesc","description",100);
line("prlink","link",128);
line("prlogo","logo (the path)",255);

echo " <div class='buttons'>\n";
if (!empty($id))
{
  echo "  <input type='button' value=' Update ' onClick='javascript:update();'> &nbsp;\n";
  echo "  <input style='color:red;' type='button' value=' Delete ' onClick='javascript:del();'>\n";
  echo "  <input type='button' value=' Cancel ' onClick='window.location.href=\"../intro.html\"';'> &nbsp;\n";
}
else
  echo "  <input type='button' value=' Add project ' onClick='javascript:add();'>\n";
echo " </div>\n";

echo "</form>\n";
echo " </div>\n\n";
echo "Fields in red must be filled\n\n";
?>

</body>
</html>
