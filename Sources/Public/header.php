<?php
session_start();
# Header 
# XHTML compatibility has to be reviewed
//echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//$lg'\n";
//echo "    'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>\n";
//echo "<?xml version='1.0' encoding='iso-8859-1'\n";
//echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='fr' lang='$lg'>\n";
echo "<html>\n";
echo "<head>\n";

# Title of the page
if (!isset($title)) $title="@@LabName@@";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso8859-1\" />\n";
echo " <title>$title</title>\n";
     
# favicon
if (!isset($favicon)) $favicon="$local_server/favicon.ico";
echo " <link rel='shortcut icon' href='$favicon' />\n";

# Header stylesheet
echo " <link rel='stylesheet' href='$local_server$css_path/header.css' type='text/css' />\n";

# User defined parameters
if (!empty($css))   		echo " <link rel='stylesheet' href='$css' type='text/css' />\n";
if (!empty($metaKeywords))  	echo " <meta name='keywords' content='$metaKeywords' />\n";
if (!empty($metaDescription))  	echo " <meta name='description' content='$metaDescription' />\n";
echo "</head>\n
<body>
<SCRIPT language=javascript>
    function OuvrirPopup(page,nom,option) {
       window.open(page,nom,option);
    }
</SCRIPT>\n";

if($_SESSION["Hide"] == 0 or $_SESSION["Hide"]=="")
{
	# add here your own headers (lab logos, etc...)
}
else echo "<body>\n";

?>
