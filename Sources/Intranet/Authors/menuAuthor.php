<?php
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");
?>
<html>
<head>
  <meta http-equiv='pragma' content='no-cache'>
  <meta http-equiv='expires' content='-1'>
  <link rel="stylesheet" href="<?echo $css_path;?>/backoffice.css" type="text/css" />
</head>

<body>

<div class='menu'>

<h2>Author</h2>

<div class='submenu'>
  <h3>Add an author</h3>
  <form action="author.php" target="edit" name="addAuthor">
    <input type="button" value="Add" onClick="parent.edit.location.href='author.php';">
  </form>
</div>

<div class='submenu'>
 <h3>Edit author</h3>
 <form action="author.php" target="edit" name="editAuthors">
  <select name="id" size="18" onChange='document.forms["editAuthors"].submit();'>
  <?php
   sqlConnect();
   $aresult = sqlQuery("SELECT DISTINCT id, first, last FROM authors WHERE is_author='oui' ORDER BY last ASC");
   while ($aresult && $arow = mysql_fetch_array($aresult)) {
     if (strlen($arow[last].$arow[first])>24) {
     	$firsts = explode("-",$arow[first]);
	$tmp_first = "";
	foreach($firsts as $first) {
	  $tmp_first .= substr($first,0,1);
	  $tmp_first .= "-";
	}
     	echo "  <option value='".$arow["id"]."'>".$arow["last"]." ".substr($tmp_first,0,strlen($tmp_first)-1).".</option>\n";
     }
     else echo "  <option value='".$arow["id"]."'>".$arow["last"]." ".$arow["first"]."</option>\n";
   }
  ?>
  </select>
  <br />
  <br />
  <input type="button" value="Refresh" onClick="window.location.href='menuAuthor.php';">
 </form>
</div>

</div>

<?php include("../commonMenu.php") ?>

</body>
</html>
