<?php

$Basilic_version= "BasilicPlus 1.0.1b - 2007";
$Basilic_link	= "http://lsiit.u-strasbg.fr/Software/BasilicPlus";
$Basilic_name	= "BasilicPlus";
$admin_mail	= "@@AdminEMail@@";

$local_server 	= "@@ServerName@@";
$public_path 	= "@@PublicationsPath@@";
$intra_path 	= "@@BasilicIntranetPath@@";
$images_path	= "@@ImagesPath@@";
$css_path	= "@@CSSPath@@";

// directory name for protected files
$protected 	= "private";
$hdr 		= "Habilitation &agrave; Diriger des Recherches";
$rap_c 		= "Rapport de Contrat";



// ---------------------------------------
// OWN CUSTOMIZATION

$OWN_link = "http://path_to_your_home";
$OWN_name = "you company";
$this_year = date('Y');
$OWN_version = "$OWN_name - $this_year";
$OWN_logo = "$images_path/your_logo.png";

$frame = "frame.php";
// ----------------------------------------


function context()
{
  $text="\n\nContext :\n";
  $text.="On ".date("l j F Y G:i:s")."\n";
  $text.="SCRIPT_URL:      ".$_SERVER["SCRIPT_URL"]."\n";
  $text.="QUERY_STRING:    ".$_SERVER["QUERY_STRING"]."\n";
  $text.="SCRIPT_FILENAME: ".$_SERVER["SCRIPT_FILENAME"]."\n";
  $text.="REMOTE_ADDR :    ".$_SERVER["REMOTE_ADDR"]."\n";
  $text.="HTTP_REFERER :   ".$_SERVER["HTTP_REFERER"]."\n";
  $text.="\n------------------------------------------\n\n";
  return $text;
}

function sendMessage($message)
{
  $adminNot="@@AdminNotification@@";
  if (strstr($adminNot, '@'))
    error_log($message.context(), 1, $adminNot, "Subject:Publication server error\n");
  else
    error_log($message.context(), 3, $adminNot);
}

function JSPrintMsg($message)
{
        echo 'document.write("'.str_replace('"','\"',$message).'"); ';
        echo "\n";
}

function error($message)
{
  echo "<p>\n<span style='color:red;'><b>Error</b></span> : $message\n</p>\n";
  sendMessage($message);
  die();
}

function connectToSql($name, $password)
{
  $database	= "@@mysqlDatabase@@";
  $host		= "@@mysqlHost@@";

  if (!$link = mysql_connect($host, $name, $password))
    {
      sendMessage("Unable to connect to mySQL server\nHost=$host\nName=$name");
      die("Unable to connect to mySQL server. Administrator has been warned.");
    }

  if (!mysql_query("SET NAMES 'utf8'", $link))
    {
      sendMessage("Unable to modify charset");
      die("Unable to modify charset");
    }

  if (!mysql_select_db($database, $link))
    {
      sendMessage("Unable to select $database mySQL database");
      echo("Unable to select mySQL database. Administrator has been warned.");
      die("</body>\n</html>\n");
    }
}

function sqlConnect()
{
  connectToSql("@@mysqlPublicUser@@", "@@mysqlPublicPassword@@");
}

function sqlQuery($query)
{
  if ($result = mysql_query($query))
    return $result;
  else
    {
      sendMessage("Invalid sql query : $query");
      echo("Invalid Sql query. Administrator has been warned\n");
      // echo("Debug : Invalid Sql query : <br />\n<code>$query</code>\n");
      die("</body>\n</html>\n");
    }
}

function check_input($value)
{
// Stripslashes
        if (get_magic_quotes_gpc())
          {
          $value = stripslashes($value);
          }
        // Quote if not a number
        if (!is_numeric($value))
          {
          //$value = "'" . mysql_real_escape_string($value) . "'";
          $value =  mysql_real_escape_string($value) ;
          }
        // return "d\'aguanno";
        return $value;
}


function basilic_rights($case)
{
	global $id;
	// put here your condition to acces to restricted pages
	// simpliest : test the remote IP address
	// $local=(substr($_SERVER["REMOTE_ADDR"],0,strlen("123.456.789")) == "123.456.789");
	
	// another way based on session identification and depending on the way it is called
	switch ($case) {
		case "files":		# right to access private files
		case "edit":		# right to access to edition pages from publi page
			$isLocal = (substr($_SERVER["REMOTE_ADDR"], 0, strlen("@@RemoteAddr@@")) == "@@RemoteAddr@@");
			if ($isLocal) return true;
			else return false;
			break;
		default:
			return false;
	}
}


?>
