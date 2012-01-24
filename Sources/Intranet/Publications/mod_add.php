<?
  $infos = "";
  # error_reporting(E_ALL | E_ERROR | E_WARNING | E_PARSE);

  # If file was uploaded, it's size should be > 0
  if (!empty($_FILES['fichier1']['size'])) {
    // size, name and tmp name
    $f1_size = $_FILES['fichier1']['size'];
    $f1_name = $_FILES['fichier1']['name'];
    $f1_tmpname = $_FILES['fichier1']['tmp_name'];

    // Get extension (Remove compression extensions)
    $ext = get_extension($f1_name);

    // array containing accepted extensions
    $valides = array("jpeg","ppm","psd","tiff","xbm","xpm","mpg","mp4","wmv","qt","py","pps","html","bmp","jpg","gif","png","tif","pdf","ps","avi","mpeg","ppt","mov","txt");

    // some tests on size
    if ($f1_size > $MAX_UP_SIZE) $infos .= "- This file is to big (size limit is $MAX_UP_SIZE octets)\n";
    if (!in_array($ext,$valides)) $infos .= "- This file type ($ext) is not allowed\n";

    if ($infos == "") 
    {
    	echo "<div class='information'>\n";
	require("../updatePubliDocs.php");
	$chemin="$_SERVER[DOCUMENT_ROOT]$public_path/$publi[year]/$publi[bibTex]/" ;
	intranetSqlConnect();
	if ($_POST['protect'] == "true") {
		if (!is_dir($chemin."$protected/")) {
			if (mkdir($chemin."$protected/", 0755))
			  echo "Creating private directory<br />\n";
			else 
			  error("Unable to create private directory $chemin$protected/ - Aborting");
		}

		if (!is_file($chemin."$protected/.htaccess")) {
		  // Creation of .htaccess file 
		  $file = $chemin."$protected/.htaccess";
		  echo "Creating .htaccess file in $chemin$protected/<br />\n";
		  copy("$_SERVER[DOCUMENT_ROOT]$intra_path/Publications/htaccess_private", $file);
		  /*$f = fopen($file,"w");
		  fwrite($f, "<Limit GET POST>\norder Deny,Allow\n");
		  fwrite($f, "Allow from 130.79.90.\nAllow from 130.79.91.\nAllow from 130.79.72.\nAllow from 130.79.73\n");
		  fwrite($f, "Allow from 130.79.74.\nAllow from 130.79.75.\nDeny From ALL\n</Limit>\n");
		  fclose($f);
		  chmod($file, 0644);*/
		  if (!is_file("$file"))
		    error("File .htaccess could not be created");
		}

		copy($f1_tmpname, $chemin."$protected/$f1_name");
	}
	else copy($f1_tmpname, $chemin.$f1_name);
	updatePubliDocs($_POST['id']);
	echo "</div>\n";
    }
  } // endif (size not empty)
  else {
    $f1_size = $_FILES['fichier1']['size'];
    $f1_name = $_FILES['fichier1']['name'];
    $f1_tmpname = $_FILES['fichier1']['tmp_name'];
    $infos .= "- network error occured, please resend  (if this error persists contact your webmaster)<br/>\n";
  }

  if ($infos != "") {
    	echo "<div class='information'><font color='red'>Error</font>\n";
	echo $infos;
	echo "</div>\n";
  }
?>
