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

<h1>Team edition</h1>

<div class='inputForm'>
<form action='teamAction.php' name='teamForm'>
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
  $result = sqlQuery("SELECT * FROM equip WHERE id=$id");
  if (mysql_num_rows($result)==0)
    die("Error - No equip found with id $id - Aborting\n");
  $row=mysql_fetch_array($result);
}
else
{
  $fields=array("sigle","description");
  foreach ($fields as $field)
    $row["$field"]="";
}

$t_obl = array("sigle","description");
//$t_opt = array("url");
//$t_opt = array_merge($t_obl, $t_opt);
$t_opt=t_obl;
?>

<script language="javascript">
// Check that all required field are filled
function chk()
{
  d=document.teamForm;
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
      document.teamForm.action.value="add";
      document.teamForm.submit();
    }
}
function update()
{
  a=chk();
  if (a)
    {
      document.teamForm.action.value="update";
      document.teamForm.submit();
    }
}
function del()
{
  a=window.confirm("Warning!\nAre you sure you want to DEFINITIVELY delete this record ?");
  if (a)
    {
      document.teamForm.action.value="delete";
      document.teamForm.submit();
    }
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
/*  if($str=="password")
	echo " <input type='password' name='$str' size='".min(50, $width)."' maxlength='$width' value='$row[$str]' />\n";
  else */
  	echo "  <input name='$str' size='".min(50, $width)."' maxlength='$width' value='$row[$str]' />\n";
  echo " </div>\n";
}

echo " <input type='hidden' name='id' value='$id' />\n";

line("sigle", 30);
line("description", 100);

echo " <div class='buttons'>\n";
if (!empty($id))
{
  echo "  <input type='button' value=' Update ' onClick='javascript:update();'> &nbsp;\n";
  echo "  <input style='color:red;' type='button' value=' Delete ' onClick='javascript:del();'>\n";
  echo "  <input type='button' value=' Cancel ' onClick='window.location.href=\"../intro.html\"';'> &nbsp;\n";
}
else
  echo "  <input type='button' value=' Add team ' onClick='javascript:add();'>\n";
echo " </div>\n";

echo "</form>\n";
echo " </div>\n\n";
echo "Fields in red must be filled\n\n";
?>

</body>
</html>
