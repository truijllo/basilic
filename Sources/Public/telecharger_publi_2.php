<?php
require_once("utils.php");
sqlConnect();
require("publiUtils.php");
require("fonction_tel.php");

$option=$_POST["option"];
$tab=explode(";",$option);
while(list($key,$val)=each($tab))
{
	// special case with list which takes more than one result
	if (!strncmp("list",$val,4)) {
	  $tab1[0] = "list"; $tab1[1] = substr($val,5,strlen($val));
	}
	else {
	  $tab1=explode(",",$val);
	}
	$tab2[$tab1[0]]=$tab1[1];
}

$option=$tab2;

switch($_POST["format"])
{
	case "bibtex":
		$result = bibQueryResults($option);
		//exec("rm -f publi.txt");
		if (is_file("publi.txt"))
		{ 
		  if (!unlink("publi.txt")) die ("Erreur d'accès au fichier temporaire");
		}
		while($result && $tmp=mysql_fetch_assoc($result))
		{
			$fd = fopen("publi.txt","a");
			$res=printBibTex($tmp,0);
			fwrite($fd,$res);
			fwrite($fd,"}");
			fwrite($fd, "\n");
			fclose($fd);	
		}
		$file="publi.txt";
   		// Envoi des Headers HTTP : recharge / type binaire / taille du fichier
		   header("Pragma: no-cache");
		   header("Expires: 0");
		   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		   header("Cache-Control: no-cache, must-revalidate");
		   header("Content-Type: octet-stream");
		   header("Content-Length: ".filesize($file));
		// Envoi du Header HTTP : Nom du fichier
		   header("Content-Disposition: attachment; filename=".$file);
		// Ouverture du fichier
		   $lecture = fopen($file,"r");
		   While(!feof($lecture))
		   {
		    // Lecture du fichier
		    $line = fread($lecture,255);
		    // Envoi du fichier vers le navigateur
		    print($line);
		    flush();
//		    ob_clean();
		   }
		break;
	case "rap":
		$option["display"]="rap";
		$result = bibQueryResults($option);
		//exec("rm -f publi.txt");
		if (is_file("publi.txt"))
		{ 
		  if (!unlink("publi.txt")) die ("Erreur d'accès au fichier temporaire");
		}
		$year="1";
		while($result && $tmp=mysql_fetch_assoc($result))
		{
			$fd = fopen("publi.txt","a");
			if($year!=$tmp["year"]) {
				fwrite($fd,"\n\nAnnee:$tmp[year]\n\n");
				$class = "";
			}
			$year=$tmp["year"];
			$res=printRap_Aff($tmp);
			fwrite($fd,$res);
			fwrite($fd, "\n");
			fclose($fd);	
		}
		$file="publi.txt";
   		// Envoi des Headers HTTP : recharge / type binaire / taille du fichier
		   header("Pragma: no-cache");
		   header("Expires: 0");
		   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		   header("Cache-Control: no-cache, must-revalidate");
		   header("Content-Type: octet-stream");
		   header("Content-Length: ".filesize($file));
		// Envoi du Header HTTP : Nom du fichier
		   header("Content-Disposition: attachment; filename=".$file);
		// Ouverture du fichier
		   $lecture = fopen($file,"r");
		   While(!feof($lecture))
		   {
		    // Lecture du fichier
		    $line = fread($lecture,255);
		    // Envoi du fichier vers le navigateur
		    Print($line);
                    flush();
//		    ob_clean();
		   }
		break;
	case "ps":
	case "pdf":
		$option["display"]="rap";
		$result = bibQueryResults($option);
		//exec("rm -f publi.txt");
		if (is_file("publi.html"))
		{ 
		  if (!unlink("publi.html")) die ("Erreur d'accès au fichier temporaire");
		}
		$year="1";
		if (!empty($option[author])) $title_addon = " de ".strtoupper(substr($option["author"],0,1)).strtolower(substr($option["author"],1,strlen($option["author"])));
		$fd = fopen("publi.html","a");
		fwrite($fd,"\n\n<html>\n<head>\n<title>Publications$title_addon</title>\n</head>\n<body>\n<h1>Publications$title_addon</h1>\n");

		while($result && $tmp=mysql_fetch_assoc($result))
		{
			if($year!=$tmp["year"]) {
				fwrite($fd,"\n<br><h2>Publications en $tmp[year]</h2>\n");
				$class = "";
			}
			$debug  = 0;
			$year=$tmp["year"];
			$res=printHTML_Aff($tmp);
			fwrite($fd,$res);
			fwrite($fd, "\n");
		}
		fwrite($fd,"\n\n</body></html>\n\n");
		fclose($fd);

		system("@@HtmlDocCommand@@ -t $_POST[format] --size a4 --fontspacing 1.1 --fontsize 8 --textfont sans --header ___ --footer __1 --no-strict --no-title --no-links --no-toc publi.html --outfile publi.$_POST[format]");
		$file="publi.$_POST[format]";
   		// Envoi des Headers HTTP : recharge / type binaire / taille du fichier
		   header("Pragma: no-cache");
		//   header("Expires: 0");
		   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		   header("Cache-Control: no-cache, must-revalidate");
		   header("Content-Type: octet-stream");
		   header("Content-Length: ".filesize($file));
		   header("Content-type: application/$_POST[format]");
		// Envoi du Header HTTP : Nom du fichier
		   header("Content-Disposition: attachment; filename=".$file);
		// Ouverture du fichier
		   $lecture = fopen($file,"r");
		   While(!feof($lecture))
		   {
		    // Lecture du fichier
		    $line = fread($lecture,255);
		    // Envoi du fichier vers le navigateur
		    Print($line);
                    flush();
//		    ob_clean();
		   }
		break;
	case "xml":
		$result = bibQueryResults($option);
		exec("rm -f publi.txt");
		$year="1";
		while($result && $tmp=mysql_fetch_assoc($result))
		{
			$fd = fopen("publi.txt","a");
			printXML_Aff($tmp,$fd);
			fclose($fd);
		//	ob_end_clean();
		}
		$file="publi.txt";
   		// Envoi des Headers HTTP : recharge / type binaire / taille du fichier
		   header("Pragma: no-cache");
		   header("Expires: 0");
		   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		   header("Cache-Control: no-cache, must-revalidate");
		   header("Content-Type: octet-stream");
		   header("Content-Length: ".filesize($file));
		// Envoi du Header HTTP : Nom du fichier
		   header("Content-Disposition: attachment; filename=".$file);
		// Ouverture du fichier
		   $lecture = fopen($file,"r");
		   While(!feof($lecture))
		   {
		    // Lecture du fichier
		    $line = fread($lecture,255);
		    // Envoi du fichier vers le navigateur
		    Print($line);
                    flush();
//		    ob_clean();
		   }
		break;
}

?>
