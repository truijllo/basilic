<?php 
session_start();
require_once("utils.php");?>
<head>
    <?
  # Header stylesheet
  echo " <link rel='stylesheet' href='$local_server$css_path/header.css' type='text/css' />\n";
  ?>
</head>
<form action="telecharger_publi_2.php" method="POST">
<table width="100%" border=0 cellspacing=10>
<tr align="center"><td colspan="2"><b>Download Publications</b></td></tr>
<tr><td width=70% style="text-align: right;">BibTex format</td><td><input type="radio" name="format" value="bibtex"/></td></tr>
<tr><td style="text-align: right;">Activity report</td><td><input type="radio" name="format" value="rap" /></td></tr>
<? if (@@EnableHtmlDoc@@) {?>
<tr><td style="text-align: right;">PDF</td><td><input type="radio" name="format" value="pdf" checked/></td></tr>
<tr><td style="text-align: right;">PostScript</td><td><input type="radio" name="format" value="ps"/></td></tr>
<? } ?>
<!-- <tr><td class=texte style="text-align: right;">format CNRS (XML)<td><input type="radio" name="format" value="xml" /> -->
<tr align="center"><td colspan="2"><input type="submit" value="submit" /></td></tr>
</table>
<input type="hidden" name="option" value="<?php echo"$_GET[option]"?>">
</form>
