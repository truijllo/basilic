<?
  echo "<div class='information'>\n";
  require("../updatePubliDocs.php");
  $chemin="$_SERVER[DOCUMENT_ROOT]$public_path/$publi[year]/$publi[bibTex]/" ;
  intranetSqlConnect();
  if (!empty($_POST["group"]))	$group=$_POST["group"];

  $res_upd = sqlQuery("SELECT * from docs WHERE id=$id_doc");
  $docs = mysql_fetch_array($res_upd);
  switch ($group) {
    case "public":
    	rename($chemin."$protected/$docs[source]", $chemin.$docs['source']);
	break;
    case "private":
   	if (!is_dir($chemin."$protected/")) {
		mkdir($chemin."$protected/", 0755);
	}
	if (!is_file($chemin."$protected/.htaccess")) {
	  // Creation of .htaccess file 
	  $file = $chemin."$protected/.htaccess";
	  echo "Creating .htaccess file in $chemin$protected/<br />\n";
	  copy("$_SERVER[DOCUMENT_ROOT]$intra_path/htaccess_private", $file);
	  if (!is_file("$file"))
	    error("File .htaccess could not be created");
	}
    	rename($chemin.$docs['source'], $chemin."$protected/$docs[source]");
	break;
  }
  updatePubliDocs($id);
  echo "</div>\n";
?>
