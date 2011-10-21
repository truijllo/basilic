<?
require_once("../updatePubliDocs.php");
	
echo "<div class='information'>\n";

  // supress file
  $resu1=sqlQuery("select source,protect from docs where id=$id_doc");
  $infos=mysql_fetch_assoc($resu1);

  if ($infos[protect] == "public") $add_path = ""; else $add_path = "$protected/";
  $chemin = $_SERVER['DOCUMENT_ROOT']."$public_path"."/".$publi[year]."/".$publi[bibTex]."/".$add_path."\"".$infos[source]."\"";
  $exe="rm -f ".$chemin;
  exec($exe);
  if (is_file("$chemin"))
    error("Unable to remove $chemin");

  intranetSqlConnect();
  updatePubliDocs($id);

echo "</div>\n";
?>

