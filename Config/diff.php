<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//en' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
 <title>BasilicPlus : Conflict solver</title>
 <link rel='shortcut icon' href='Images/basilic.ico' />
 <link rel='stylesheet' href='../CSS/basilic.css' type='text/css' />
</head>

<body>

<div class="content">

<div class="center">
 <img src="../Images/basilicPlus.jpg"/>
 <h1>File conflict solver</h1>
</div>

<?php
if (empty($_GET["file"]))
{
  die("No <code>file</code> provided");
}

if (empty($_GET["old"]))
{
  die("No <code>old</code> directory provided");
}

if (empty($_GET["new"]))
{
  die("No <code>new</code> directory provided");
}

echo "The old file is <code>$_GET[old]/$_GET[file]</code><br/>\n";
echo "The new file is <code>$_GET[new]/$_GET[file]</code><br/>\n";

echo "Here is the diff between the two files:\n<pre>\n";

system("diff ../$_GET[old]/$_GET[file] $_GET[new]/$_GET[file] | sed s%\"<\"%\"\&lt;\"%g | sed s%\">\"%\"\&gt;\"%g");

echo "\n</pre>\n<p><a href=\"../checkConfig.php\">Back</a></p>\n";

?>

</div>

</body>
</html>
