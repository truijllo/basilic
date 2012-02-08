<?php

$local_server 	= "@@ServerName@@";
$public_path 	= "@@PublicationsPath@@";
$intra_path 	= "@@BasilicIntranetPath@@";
$images_path	= "@@ImagesPath@@";
$css_path	= "@@CSSPath@@";
$database     = "@@mysqlDatabase@@";

// directory name for protected files
$protected 	= "private"; 
$abstract	= "abstract.html";
$hdr 		= "Habilitation &agrave; Diriger des Recherches";
$rap_c 		= "Rapport de Contrat";

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
    error_log($message.context(), 3, $adminNot, "Subject:Publication server error\n");
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

function intranetSqlConnect()
{
  connectToSql("@@mysqlIntranetUser@@", "@@mysqlIntranetPassword@@");
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

function get_extension($file)
{
    $compressedExtensions[] = "gz";
    $compressedExtensions[] = "bz";
    $compressedExtensions[] = "bz2";
    $compressedExtensions[] = "zip";
    $compressedExtensions[] = "z";

    $extension = explode('.', $file);

    $index = count($extension)-1;
    $ext = strtolower($extension[$index]);

    while (in_array($ext, $compressedExtensions))
      {
        $index--;
        $ext = strtolower($extension[$index]);
      }
  	
    return $ext;
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
	// put here your condition to acces to admin pages
	// default : test the remote IP address
	// $local=(substr($_SERVER["REMOTE_ADDR"],0,strlen("123.456.789")) == "123.456.789");
	
	// another way based on session identification and depending on the way it is called
	switch ($case) {
		case "author":		# right to suppress authors from DB
		case "project":		# right to suppress projects from DB
		case "team":		# right to edit/suppress teams from DB
			$isAdmin = (substr($_SERVER["REMOTE_ADDR"], 0, strlen("@@AdminIpAddr@@")) == "@@AdminIpAddr@@");
			if ($isAdmin) return true;
			else return false;
			break;
		case "access":		# right to access backoffice pages
		default:
			$isLocal = (substr($_SERVER["REMOTE_ADDR"], 0, strlen("@@RemoteAddr@@")) == "@@RemoteAddr@@");
			if ($isLocal) return true;
			else return false;
	}
}

?>
