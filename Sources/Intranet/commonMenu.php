<?
require_once("utils.php");
?>
<div class='commonMenu'>
 <p>
 &nbsp; Database :
 </p>
 <div style="margin-left:20px">
  <a href="<? echo $intra_path;?>/help.php" target="edit">&raquo; Help page <!--<img src='<?echo $images_path;?>/fr.png'>--><br />
  <a href="<? echo $intra_path;?>/Publications" target="_parent">&raquo; Edit a publication<br />
  <a href="<? echo $intra_path;?>/Authors" target="_parent">&raquo; Edit an author<br/>
  <?php
  if(basilic_rights("team"))
  	echo"<a href='$intra_path/Team' target='_parent'>&raquo; Edit a team<br />";
  ?>
  <a href="<? echo $intra_path;?>/Project" target="_parent">&raquo; Edit a project<br/>
  <br/>
  <a href="<? echo $public_path;?>" target="_parent">&raquo; Browse publications</a><br/>
  <a href="/" target="_parent">&raquo; Lab Home</a><br/>
 </div>
</div>
