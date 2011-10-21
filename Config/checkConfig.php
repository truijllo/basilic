<!--
  This file is part of the BasilicPlus system
  Version 1.0.2b, packaged on February 22nd, 2007.
  
  http://lsiit.u-strasbg.fr/Software/BasilicPlus
  
  BasilicPlus is based on Basilic and like 
  Basilic  is  free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published
  by the Free Software Foundation; either version 2 of the License,
  or (at your option) any later version.
  
  BasilicPlus  is  distributed  in the hope that it will be useful, but
  WITHOUT  ANY  WARRANTY ; without  even  the  implied  warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
  General Public License for more details.
  
  You should have received a copy of the GNU General Public License
  along with BasilicPlus; if not, write to the Free Software Foundation
  Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
-->
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//en' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
 <title>BasilicPlus : Configuration test page</title>
 <link rel='shortcut icon' href='Images/basilic.ico' />
 <link rel='stylesheet' href='CSS/basilic.css' type='text/css' />
</head>

<body>

<div class="content">

<div class="center">
 <img src="Images/basilicPlus.jpg"/>
 <h1>Configuration test page</h1>
</div>

<h2>php configuration</h2>

<p>
 <img src="Images/php.png" style="vertical-align:middle" alt="php" width="91" height="51" /> &nbsp; php is
 <?php echo "up and running.\n"; ?>
</p>

<?php

function printError($message)
{
  global $error;
  echo "<p class='error'>\n<b>Error</b><br/>\n";
  echo "$message\n";
  echo "</p>\n";
  $error = true;
}

function printWarning($message)
{
  global $warning;
  echo "<p class='warning'>\n<b>Warning</b><br/>\n";
  echo "$message\n";
  echo "</p>\n";
  $warning = true;
}

if (empty($_GET["install"]))
{
include("Config/include.php");
if ($basilicIncludeOk)
  echo "<p>\n Includes from current directory are ok.\n</p>\n";
else
{
  printError("<code>include</code> from current directory does not work properly.");

  echo "<p>\nCheck your php configuration:\n</p>\n";

  echo "The PHP <code>include_path</code> should contain \".\" (path to current directory)\n";
  echo "This is tuned in the <code>php.ini</code> configuration file (see the\n";
  echo "<a href='http://www.php.net/manual/en/ini.sect.path-directory.php#ini.include-path'>manual</a>) or in\n";
  echo "the apache <code>httpd.conf</code> configuration file with code like\n";
  echo "<pre>\nphp_value include_path \".:/usr/local/lib/php:/path/to/documentroot/\"\n</pre>\n";
  echo "If you don't want to change this for security reasons, the <code>BasilicPlus</code> scripts <code>include</code>\n";
  echo "and <code>require</code> directives should be modified. All the included php script should be moved to a place\n</p>\n";
  echo "where they can be included. Relative paths (such as ../../publi.php>) in <code>include</code> directives should be modified accordingly.\n";

  echo "<p>\n An enabled <code>safe_mode</code> may also be an explanation.\n</p>\n";
  die();
}

if ("@@mysqlIntranetPassword@@" == "Put the mySQL intranet password you choosed here")
{
  echo "<p>\n Your php configuration seems to be ok, you can proceed to the mySQL configuration: Create the\n";
  echo "database, the tables and the users as explained in the <a href='install.html'>install page</a>,\n";
  echo "and change the mySQL parameters in <code>configure</code> accordingly\n";
  echo "(database and users names, passwords). Then run <code>configure</code> again and reload this page.\n</p>\n";
  die();
}




echo "\n\n<h2>mySQL configuration</h2>\n";

echo "<p>Testing connections to databases:<br/>\n";

if (!function_exists("mysql_connect"))
{
  printError("Unable to find <code>mysql_connect</code> function. You should install the <code>php-mysql</code> package and restart your web server.");
  die();
}

if ($link = mysql_connect("@@mysqlHost@@", "@@mysqlPublicUser@@", "@@mysqlPublicPassword@@"))
{
  echo "Connection to <code>@@mysqlHost@@</code> as user <code>@@mysqlPublicUser@@</code> ok<br/>\n";
  if (mysql_select_db("@@mysqlDatabase@@", $link))
    echo "Selection of <code>@@mysqlDatabase@@</code> database ok<br/>\n";
  else
    printError("Unable to select database <code>@@mysqlDatabase@@</code>. Check mySQL configuration (see <a href=\"install.html#mysql\">install.html</a>).");
}
else
  printError("Unable to connect to <code>@@mysqlHost@@</code> as user <code>@@mysqlPublicUser@@</code>. Check mySQL user configuration (see <a href=\"install.html#mysql\">install.html</a>)");
mysql_close();


if ($link = mysql_connect("@@mysqlHost@@", "@@mysqlIntranetUser@@", "@@mysqlIntranetPassword@@"))
{
  echo "Connection to <code>@@mysqlHost@@</code> as user <code>@@mysqlIntranetUser@@</code> ok<br/>\n";
  if (mysql_select_db("@@mysqlDatabase@@", $link))
    echo "Selection of <code>@@mysqlDatabase@@</code> database ok<br/>\n";
  else
    printError("Unable to select database <code>@@mysqlDatabase@@</code>. Check mySQL configuration (see <a href=\"install.html#mysql\">install.html</a>).");
}
else
  printError("Unable to connect to <code>@@mysqlHost@@</code> as user <code>@@mysqlIntranetUser@@</code>. Check mySQL user configuration (see <a href=\"install.html#mysql\">install.html</a>)");
mysql_close();

echo "</p>\n";

if (isset($error))
  die();

require("Intranet/utils.php");
intranetSqlConnect();

/*
if (empty($testDeleteTable))
{
  echo "Make sure the <code>mysqlIntranetUser</code> has <code>delete</code> priviledges on the database.\n";
  echo "Delete queries are not tested in order to preserve a possible existing database.\n";
  echo "<p>If this is your first <code>Basilic</code> installation, you can <a href='.?testDeleteTable=true'>click here</a>";
  echo "to actually test delete queries. <b>Attention</b>, all basilic databases will be deleted !!</p>\n";
}
else
{
  echo "Testing delete queries : all basilic tables are deleted : ";
  $dqueries[] = "DELETE FROM publis";
  $dqueries[] = "DELETE FROM authors";
  $dqueries[] = "DELETE FROM docs";
  $dqueries[] = "DELETE FROM publiauthors";
  $dqueries[] = "DELETE FROM publidocs";
  foreach ($dqueries as $dquery)
    if (!sqlQuery($dquery))
      {
	printError("Unable to perform query: <code>$dquery</code>");
	die();
      }
  echo "ok<br/>\n";
}
*/

echo "<small>Be carefull that DB is here modified, if something goes wrong before delete queries, there might be some lines to delete</small><br/>\n";
echo "Testing insert queries : ";
$iqueries[] = "INSERT into publis (type, bibTex, year, title) VALUES ('Misc', 'BasilicTest04', '2004', 'Basilic, an Automated Bibliography Server')";
$iqueries[] = "INSERT into authors (first, last, url) VALUES ('BasilicTestQueryFirst', 'Basilic', 'http://artis.imag.fr/Software/Basilic')";
$iqueries[] = "INSERT into docs (type, source, size) VALUES ('IMG', 'basilicTestQueryInsert.test', '12345')";
$iqueries[] = "INSERT INTO publiauthors (idAuthor, idPubli, rank) VALUES ('1', '65000', '0')";
$iqueries[] = "INSERT INTO publidocs (idDoc, idPubli) VALUES ('1', '65000')";
foreach ($iqueries as $iquery)
  if (!sqlQuery($iquery))
  {
    printError("Unable to perform query: <code>$iquery</code>");  
    die();
  }
echo "ok<br/>\n";

echo "Testing update queries : ";
$uqueries[] = "UPDATE publis SET title='BasilicPlus' WHERE bibTex='BasilicTest04'";
$uqueries[] = "UPDATE authors SET url='http://artis.imag.fr/Software' WHERE first='BasilicTestQueryFirst'";
$uqueries[] = "UPDATE docs SET size='54321' WHERE source='basilicTestQueryInsert.test'";
$uqueries[] = "UPDATE publiauthors SET idAuthor='2' WHERE idPubli='65000'";
$uqueries[] = "UPDATE publidocs SET idDoc='2' WHERE idPubli='65000'";
foreach ($uqueries as $uquery)
  if (!sqlQuery($uquery))
  {
    printError("Unable to perform query: <code>$uquery</code>");
    die();
  }
echo "ok<br/>\n";

mysql_close();
sqlConnect();

echo "Testing select queries for public user : ";
$squeries[] = "SELECT * FROM publis";
$squeries[] = "SELECT * FROM authors";
$squeries[] = "SELECT * FROM docs";
$squeries[] = "SELECT * FROM publiauthors";
$squeries[] = "SELECT * FROM publidocs";
$squeries[] = "SELECT * FROM equip";
$squeries[] = "SELECT * FROM months";
$squeries[] = "SELECT * FROM projects";
$squeries[] = "SELECT * FROM publiequip";
$squeries[] = "SELECT * FROM status";
foreach ($squeries as $squery)
  if (!sqlQuery($squery))
  {
    printError("Unable to perform query: <code>$squery</code>");
    die();
  }
echo "ok<br/>\n";

mysql_close();
intranetSqlConnect();

echo "Testing delete queries : ";
$dqueries[] = "DELETE FROM publis WHERE bibTex='BasilicTest04'";
$dqueries[] = "DELETE FROM authors WHERE first='BasilicTestQueryFirst'";
$dqueries[] = "DELETE FROM docs WHERE source='basilicTestQueryInsert.test'";
$dqueries[] = "DELETE FROM publiauthors WHERE idPubli='65000'";
$dqueries[] = "DELETE FROM publidocs WHERE idPubli='65000'";
foreach ($dqueries as $dquery)
  if (!sqlQuery($dquery))
  {
    printError("Unable to perform query: <code>$dquery</code>");
    die();
  }
echo "ok<br/>\n";



echo "\n\n<h2><code>convert</code> configuration</h2>\n";

#echo "Convert command is <code>@@ConvertCommand@@</code><br/>\n";
$path = ereg_replace("(.*)/[^/]*$","\\1", "$_SERVER[SCRIPT_FILENAME]");

$source = "Images/basilicPlus.jpg";
if (!is_file("$path/$source"))
{
  printError("Source image (<code>$path/$source</code>) cannot be found");
  die();
}

$thumbnail = "Images/basilic-thumb.jpg";
if (is_file("$path/$thumbnail"))
  echo "Thumbnail image (<code>$path/$thumbnail</code>) already exists. Remove this file to force its re-creation.<br/>\n";
else
{
  echo "Trying to create thumbnail from <code>$source</code><br/>\n";
  echo "Command is:\n<pre>\n@@ConvertCommand@@ -geometry 80x60 $path/$source $path/$thumbnail\n</pre>\n";
  exec("@@ConvertCommand@@ -geometry 80x60 $path/$source $path/$thumbnail", $output, $returnVar);
  if ($returnVar == 0)
    echo "Thumbnail successfully created.<br/>\n";
  else
    {
      printError("Error during thumbnail creation : return value is $returnVar");
      if (!empty($output))
      {
	echo ", command output is ";
	print_r($output);
      }
      echo "<br/><br/>\n";
      echo "Check that your convert program is properly installed and can be executed by the web server.<br/>\n";
      echo "For your information:<br/>\n";
      echo "Current <code>\$PATH</code> is <code>";
      system('echo $PATH');
      echo "</code><br/>\n";
      echo "<code>which @@ConvertCommand@@</code> returns:\n<pre>\n";
      system("which \"@@ConvertCommand@@\"");
      echo "</pre>\n";
      die();
    }
}
if (is_file("$path/$thumbnail"))
    echo "<img src='$thumbnail' alt='basilic' width='80' height='42'/><br/>\n";
else
{
    printError("The thumbnail file ($thumbnail) does not exist.");
    die();
}

if (@@EnableHtmlDoc@@) {
    
    echo "\n\n<h2><code>htmldoc</code> configuration</h2>\n";
    
    #echo "HTMLdoc command is <code>@@HtmlDocCommand@@</code><br/>\n";
    $path = ereg_replace("(.*)/[^/]*$","\\1", "$_SERVER[SCRIPT_FILENAME]");
    
    $source = "install.html";
    if (!is_file("$path/$source"))
    {
      printError("Source file (<code>$path/$source</code>) cannot be found");
      die();
    }
    
    $pdfFile = "Images/install.pdf";
    if (is_file("$path/$pdfFile"))
      echo "PDF file (<code>$path/$pdfFile</code>) already exists. Remove this file to force its re-creation.<br/>\n";
    else
    {
      echo "Trying to create PDF output from <code>$source</code><br/>\n";
      echo "Command is:\n<pre>\n@@HtmlDocCommand@@ -t pdf --size a4 --no-toc $path/$source --outfile $path/$pdfFile\n</pre>\n";
      exec("@@HtmlDocCommand@@  -t pdf --size a4 --no-toc $path/$source --outfile $path/$pdfFile", $output, $returnVar);
      if ($returnVar == 0)
        echo "PDF file successfully created.<br/>\n";
      else
        {
          printError("Error during PDF file creation : return value is $returnVar");
          if (!empty($output))
          {
            echo ", command output is ";
            print_r($output);
          }
          echo "<br/><br/>\n";
          echo "Check that your htmldoc program is properly installed and can be executed by the web server.<br/>\n";
          echo "For your information:<br/>\n";
          echo "Current <code>\$PATH</code> is <code>";
          system('echo $PATH');
          echo "</code><br/>\n";
          echo "<code>which @@HtmlDocCommand@@</code> returns:\n<pre>\n";
          system("which \"@@HtmlDocCommand@@\"");
          echo "</pre>\n";
          echo "<p>This function can be disabled by changing var EnableHtmlDoc to false in file configure";
          die();
        }
    }
}


echo "\n\n<h2>File hierarchy verification</h2>\n";

echo "<p>\n Apache DOCUMENT_ROOT is <code>$_SERVER[DOCUMENT_ROOT]</code><br/>\n";
if ("@@DocumentRoot@@" != "$_SERVER[DOCUMENT_ROOT]")
  {
    echo " You use a custom DOCUMENT_ROOT variable: <code>@@DocumentRoot@@</code><br/>\n";
  } 
echo " Apache SERVER_NAME is <code>$_SERVER[SERVER_NAME]</code><br/>\n</p>\n";

echo "<p>\n Your main publication URL will be <b><code>http://$_SERVER[HTTP_HOST]@@PublicationsPath@@</code></b>.<br/>\n";
echo " It will contain index pages and publication associated directories, sorted by years.<br/>\n";
echo " Files will be copied in <code>@@DocumentRoot@@@@PublicationsPath@@</code>, which should be writable.<br/>\n";
echo " This can be changed using <code>PublicationsPath</code> in <code>configure</code>.</p>\n\n";

echo "<p>\n The intranet backoffice pages will be installed in <b><code>http://$_SERVER[HTTP_HOST]@@BasilicIntranetPath@@</code></b><br/>\n";
echo " Files will be copied in <code>@@DocumentRoot@@@@BasilicIntranetPath@@</code>, which should be writable.<br/>\n";
echo " Edit <code>BasilicIntranetPath</code> in <code>configure</code> to change this.</p>\n\n";

echo "<p>\n css files will be copied in <b><code>@@DocumentRoot@@/@@CSSPath@@</code></b> (edit <code>CSSPath</code> to change)<br/>\n";
echo " Image files will be copied in <b><code>@@DocumentRoot@@/@@ImagesPath@@</code></b> (edit <code>ImagesPath</code>)</p>\n";

echo "\n\n<h3>Checking file conflicts</h3>\n";

function checkFileConflicts($dir, $targetDir)
{
  global $warning;

  $targetPath = "@@DocumentRoot@@/$targetDir";
  $dirMessage="Files cannot be copied there. Change directory permissions (may be reset after installation).\n";

  while (!file_exists("$targetPath"))
    {
      $targetPath = ereg_replace("(.*)/[^/]*$","\\1", "$targetPath");
      $dirMessage="Will not be able to create <code>@@DocumentRoot@@/$targetDir</code>.\n
                   You should change directory permissions or create this directory yourself.";
    }

  if (!is_writable("$targetPath"))
    {
      printError("Directory <code>$targetPath</code> is not writable. $dirMessage");
      die();
    }

  $d = dir("$dir");
  while ($f = $d->read())
    if ($f != "." && $f != ".." && is_file("$dir/$f") && file_exists("@@DocumentRoot@@/$targetDir/$f"))
      {
	exec("diff $dir/$f @@DocumentRoot@@/$targetDir/$f", $output, $res);

	if ($res != 0)
	  printWarning("File <code>$targetDir/$f</code> already exists. See the <a href=\"Config/diff.php?file=$f&old=$dir&new=@@DocumentRoot@@/$targetDir\">diff</a>.");
      }
}

$warning = false;
checkFileConflicts("Public", "@@PublicationsPath@@");
checkFileConflicts("CSS", "@@CSSPath@@");
checkFileConflicts("Images", "@@ImagesPath@@");
checkFileConflicts("Intranet", "@@BasilicIntranetPath@@");
checkFileConflicts("Intranet/Authors", "@@BasilicIntranetPath@@/Authors");
checkFileConflicts("Intranet/Publications", "@@BasilicIntranetPath@@/Publications");
checkFileConflicts("Intranet/Project", "@@BasilicIntranetPath@@/Project");
checkFileConflicts("Intranet/Team", "@@BasilicIntranetPath@@/Team");

if ($warning)
{
  echo "Some conflicts were detected.<br/>\n";
  echo "They may come from a previous <code>BasilicPlus</code> installation or from changes you did since the previous installation. See the <b>diff</b>.<br/>\n";
  echo "Conflicting files will be renamed with a <code>.basilic.bak</code> extension during installation.<br/>\n";
#  echo "If you customized these files since the previous BasilicPlus installation, you should merge your modifications after installation.<br/>\n";
}
else
  echo "No conflict detected.<br/>\n";


echo "\n\n<h2>Local browsers detection</h2>\n";

echo "Your browser ip address is $_SERVER[REMOTE_ADDR]<br/>\n";

$intranetMask = substr($_SERVER["REMOTE_ADDR"], 0, strlen("@@netaddr@@"));
if ("@@netaddr@@" != "$intranetMask")
{
  printWarning("Your IP address does not match the <code>netaddr</code> defined in <code>configure</code>.<br/>\n
           (Values are <code>$intranetMask</code> and <code>@@netaddr@@</code>)<br/>\n
           This IP mask is used to determine which machines are considered as local, and hence will be able to modify the database.<br/>\n
           Unless you implemented a different local detection mechanism in <code>Sources/Public/header.php</code>, \n
           you must correct the <code>RemoteAddr</code> mask in <code>configure</code>, and then re-run <code>configure</code>.");
}
else
  echo " <code>netaddr</code> IP mask (@@netaddr@@) is ok.<br/>\n";


echo "\n\n<h2>Lab name</h2>\n";

if ("@@LabName@@" == "MyLab")
  printWarning("Change the <code>LabName</code> value in <code>configure</code>.<br/>\n
           (replace <code>MyLab</code> by the name of your laboratory/team).\n");
else
  echo " The laboratory name that will appear in the index pages is <b><code>@@LabName@@</code></b>.\n";


echo "\n\n<h2>Admin e-mail and problem notification</h2>\n";

echo "The <code>AdminEMail</code> value defined in <code>configure</code> is <b><code>@@AdminEMail@@</code></b>.<br/>\n";
echo "This address will appear at the bottom of the <code>BasilicPlus</code> web pages, so that users can report problems\n";
echo "or ask questions. Change it if needed.\n";

echo "<p>\nThe <code>AdminNotification</code> value defined in <code>configure</code> is <b><code>@@AdminNotification@@</code></b>.<br/>\n";
echo "This value defines the way <code>BasilicPlus</code> problems are reported:<br/>If it contains an '@', it is considered as an email\n";
echo "address and problems will be reported by email (php must be properly configured to be able to send emails).\n";
echo "Otherwise, it is considered as a file, where error logs will be appended. Provide a full path and make sure the directory is writable.\n";
echo "Change this value if needed.\n</p>\n";

$message="This message was generated by the web server to test the BasilicPlus admin notification configuration.\n\n";
$message.="Congratulation, your system checking is probably complete and you can now install BasilicPlus on your web server.\n";

echo "The <code>AdminNotification</code> is now tested :<br/>\n";

sendMessage($message);

if (strstr("@@AdminNotification@@", '@'))
{
  echo "An e-mail has been sent to BasilicPlus system administrator (<code>@@AdminNotification@@</code>). Check that you received it.<br/>\n";
  echo "If it is not the case, check you php e-mail configuration. E-mails are sent using the <code>error_log(msg,1)</code> php function.\n";
}
else
{
  echo "Message has been appended to BasilicPlus log file (<code>@@AdminNotification@@</code>). Check that this file was created and filled.<br/>\n";
  echo "If it is not the case, check that the destination directory is writable. Messages are appended using the <code>error_log(msg, 3)</code> php function.\n";
}

  
  
echo "\n\n<h2>All tests successfully passed</h2>\n";

echo "If no error message is displayed in this page, and all warnings have been checked, you can now install
      the <code>BasilicPlus</code> scripts on your server.<br/>\n";

echo "If you changed a value, rerun <code>configure</code> and reload this page as usual. Otherwise,<br/>";

$file="$path/Images/.installok.txt";
$f=fopen($file,"w");
fwrite($f, "All configuration tests passed");
fclose($f);

echo "<p>&nbsp; &nbsp; &nbsp; <a href='?install=yes'>&raquo; Install BasilicPlus scripts &laquo;</a></p>";
}


else


{
$path = ereg_replace("(.*)/[^/]*$","\\1", "$_SERVER[SCRIPT_FILENAME]");

// This is a simple security test. Do not overpass : solve configuration problems first. 
if (!file_exists("$path/Images/.installok.txt"))
{
  printError("All configuration tests were not sucessfull. Run <code>configure</code>
  and see the results in <a href='checkConfig.php'><code>checkConfig.php</code></a>");
  die(); 
}

echo "\n\n<h2>File installation</h2>\n";

function copyFile($src, $target)
{
  if (!file_exists($target))
    {
      umask(0);
      if (copy($src, $target))
	chmod($target, 0644);
      else
	printError("Unable to copy <code>$src</code> to <code>$target</code>");
    }
}

function makeDir($dir)
{
  if (!file_exists($dir))
    {
      umask(0);
      if (mkdir("$dir", 0755))
	echo "Creating directory $dir<br/>\n";
      else
	printError("Unable to create directory $dir - Aborting");
    }
}

function createDir($dir)
{
  if (!file_exists($dir))
    {
      $upDir = ereg_replace("(.*)/[^/]*$","\\1", "$dir");
      createDir($upDir);
      makeDir("$dir");
    }
}

function copyFiles($sourceDir, $targetDir)
{
  $targetDir = "@@DocumentRoot@@/$targetDir";
  createDir("$targetDir");
  echo "Copy of script files...<br/>\n";
  
  $d = dir("$sourceDir");
  while ($f = $d->read())
    if (($f[0] != ".") && (is_file("$sourceDir/$f")))
      {
	if (file_exists("$targetDir/$f"))
	  rename("$targetDir/$f", "$targetDir/$f.basilic@@VERSION@@.bak");
	copyFile("$sourceDir/$f", "$targetDir/$f");
      }
}

$error=0;
copyFiles("Public", "@@PublicationsPath@@");
copyFiles("CSS", "@@CSSPath@@");
copyFiles("Images", "@@ImagesPath@@");
copyFiles("Intranet", "@@BasilicIntranetPath@@");
copyFiles("Intranet/Authors", "@@BasilicIntranetPath@@/Authors");
copyFiles("Intranet/Publications", "@@BasilicIntranetPath@@/Publications");
copyFiles("Intranet/Project", "@@BasilicIntranetPath@@/Project");
copyFiles("Intranet/Team", "@@BasilicIntranetPath@@/Team");

// echo "<br/>\nCreating Year directories...<br/>\n";
// umask(0);
// for ($year=1980; $year<2010; $year++)
// {  
  // makeDir("@@DocumentRoot@@@@PublicationsPath@@/$year");
  // $file="@@DocumentRoot@@@@PublicationsPath@@/$year/index.php";
  // $f=fopen($file,"w");
  // fwrite($f, "<"."?php \$year=$year; include(\"../index.php\"); ?".">");
  // fclose($f);
  // chmod($file, 0644);
// }

makeDir("@@DocumentRoot@@@@PublicationsPath@@/Garbage");

if ($error)
  die("You should change directory permissions and reload this page. Permissions can be reset after installation.\n");

echo "<p>All files successfully installed.<br/>\n";
echo "You can look at the <a href='@@PublicationsPath@@'>main publication page</a> or the <a href='@@BasilicIntranetPath@@'>intranet edition pages</a>.</p>\n";

echo "<p>Go to the <a href='install.html#populating'>install page</a> to see how to populate the database from a bibtex file.</p>\n";
}
?>

</div>

</body>
</html>
