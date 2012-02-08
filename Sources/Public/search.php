<?php
require_once("utils.php");
switch($lg)
{
	case "sp":
	$txtEquip="Equipo";
	$txtProjet="Proyecto";
	$txtAuteur="Autor";
	$txtOr="o";
	$txtAnd="y";
	$txtTitre="T&iacute;tulo";
	$txtAnnee="A&ntilde;o";
	$txtDisplay="Visualizaci&oacute;n";
	$txtClassRapActi="Clasificaci&oacute;n seg&uacute;n los informes de actividades";
	$txtList="Lista de las publicaciones";
	$txtGallery="Galer&iacute;a de im&aacute;genes";
	$txtBibTex="Formato BibText";
	$txtXml="XML (formato CNRS)";
/*	$txtReports="Incluir los informes de DEA, los manuales...";
	$txtProceed="Fijar que las publicaciones vinculadas a conferencias";
	$txtArticle="Fijar que las publicaciones vinculadas a art&iacute;culos";
	$txtBook="Fijar que las publicaciones vinculadas a libros";
	$txtAll_p="Fijar todas publicaciones";
	$txtThese="Fijar todas las tesis"; */
	
	// TODO: ‡ traduire en spagniole
	$txtOnlyFullText="Cacher les r&eacute;f&eacute;rences d&eacute;munies du texte int&eacute;gral";
	$txtOptExclude="N'afficher que les types de publication suivants:";
	$txtOptBooks="Les ouvrages (Books, InBooks, Booklets et inCollections)";
	$txtOptArticles="Les Articles";
	$txtOptConf="Les conf&eacute;rences (Proceedings et inProceedings)";
	$txtOptMisc="Les publications diverses (misc)";
	$txtOptThesis="Les Th&eacute;ses et HDR (PhdThesis et MastersThesis)";
	$txtOptContrat="Les rapports de recherche (TechReports)";
	$txtOptInterne="Les rapports internes (manuals et unpublished)";

	$txtToutes="Todas";
	$imgChercher="buscar.png";
	$txtBetween="o en medio";
	$txtAnd="y";
	$txtAllF="Todas";
	$txtAllM="Todos";
	$txtOptionAff="<img src='$local_server$images_path/fleche_bas.gif'> Opci&oacute;n de fijaci&oacute;n <img src='$local_server$images_path/fleche_bas.gif'>";
	$txtOptionAffOff="<img src='$local_server$images_path/fleche_haut.gif'> Enmascarar las opciones de fijaci&oacute;n <img src='$local_server$images_path/fleche_haut.gif'>";
	break;
	case "de":
	$txtEquip="Team";
	$txtProjet="Projekt";
	$txtAuteur="Autor(en)";
	$txtOr="oder";
	$txtAnd="und";
	$txtTitre="W&uuml;rter des Titels";
	$txtAnnee="Jahr";
	$txtDisplay="Anschlagformat";
//	$txtClassRapActi="Klassierung nach den T&auml;tigkeitsberichten";
//	$txtList="Liste der Publikationen";
	$txtClassRapActi="Liste der Publikationen";
	$txtGallery="Bildgalerie";
	$txtBibTex="BibTex-Format";
	$txtXml="XML (CNRS-Format)";
/*	$txtReports="Die dea-Berichte, die Handb&uuml;cher einschlieﬂen...";
	$txtProceed="Anzeigen, daﬂ die mit Konferenzen verbundenen Ver&ouml;ffentlichungen";
	$txtArticle="Anzeigen, daﬂ die mit Artikeln verbundenen Ver&ouml;ffentlichungen";
	$txtBook="Anzeigen, daﬂ die mit den B&uuml;chern verbundenen Ver&ouml;ffentlichungen";
	$txtAll_p="Alle Ver&ouml;ffentlichungen anzeigen";
	$txtThese="Alle Thesen anzeigen"; */

	$txtOnlyFullText="Publikationen ohne Ganztext nicht zeigen";
	$txtOptExclude="Nur diese Publikationenart listen:";
	$txtOptBooks="Werke (Books, InBooks, Booklets und inCollections)";
	$txtOptArticles="Artikel";
	$txtOptConf="Konferenzen (Proceedings und inProceedings)";
	$txtOptMisc="Unterschiedliche Publikationen (misc)";
	$txtOptThesis="Thesen (PhdThesis und MastersThesis)";
	$txtOptContrat="Forschungsberichte (TechReports)";
	$txtOptInterne="interne Berichte (manuals und unpublished)";

	$txtToutes="Alle";
	$imgChercher="suchen.png";
	$txtBetween="oder zwischen";
	$txtAnd="und";
	$txtAllF=$txtAllM="Alle";
	$txtOptionAff="<img src='$local_server$images_path/fleche_bas.gif'> erweiterte Suche <img src='$local_server$images_path/fleche_bas.gif'>";
	$txtOptionAffOff="<img src='$local_server$images_path/fleche_haut.gif'> einfache Suche <img src='$local_server$images_path/fleche_haut.gif'>";
	break;
	case "fr":
	$txtEquip="Equipe";
	$txtProjet="Projet";
	$txtAuteur="Auteur(s)";
	$txtOr="ou";
	$txtAnd="et";
	$txtTitre="Mot(s) du titre";
	$txtAnnee="Ann&eacute;e";
	$txtDisplay="Formats d'affichage";
//	$txtList="Liste des publications";
	$txtClassRapActi="Liste des publications";
	$txtGallery="Galerie d'images";
	$txtBibTex="Format BibTeX";
	$txtXml="XML (format CNRS)";
/*	$txtReports="Afficher les rapports de DEA, les manuels...";
	$txtProceed="Afficher que les publications li&eacute;es &agrave; des conf&eacute;rences";
	$txtArticle="Afficher que les publications li&eacute;es &agrave; des articles";
	$txtBook="Afficher que les publications li&eacute;es &agrave; livres";
	$txtAll_p="Afficher toutes les publications";
	$txtThese="Afficher toutes les th&egrave;ses";*/
	$txtToutes="Toutes";
	
	$txtOnlyFullText="Cacher les r&eacute;f&eacute;rences d&eacute;munies du texte int&eacute;gral";
	$txtOptExclude="N'afficher que les types de publication suivants:";
	$txtOptBooks="Les ouvrages (Books, InBooks, Booklets et inCollections)";
	$txtOptArticles="Les Articles";
	$txtOptConf="Les conf&eacute;rences (Proceedings et inProceedings)";
	$txtOptMisc="Les publications diverses (misc)";
	$txtOptThesis="Les Th&eagrave;ses et HDR (PhdThesis et MastersThesis)";
	$txtOptContrat="Les rapports de recherche (TechReports)";
	$txtOptInterne="Les rapports internes (manuals et unpublished)";

	$imgChercher="chercher.png";
	$txtBetween="ou entre";
	$txtAnd="et";
	$txtAllF="Toutes";
	$txtAllM="Tous";
	$txtOptionAff="<img src='$local_server$images_path/fleche_bas.gif'> Recherche avanc&eacute;e <img src='$local_server$images_path/fleche_bas.gif'>";
	$txtOptionAffOff="<img src='$local_server$images_path/fleche_haut.gif'> Recherche simple <img src='$local_server$images_path/fleche_haut.gif'>";
	break;
	case "en":
	$txtEquip="Team";
	$txtProjet="Project";	
	$txtAuteur="Author(s)";
	$txtOr="or";
	$txtAnd="and";
	$txtTitre="Title Keyword(s)";
	$txtAnnee="Year";
	$txtDisplay="Posting formats";
//	$txtClassRapActi="Classification according to management reports";
//	$txtList="Publication list";
	$txtClassRapActi="Publication list";
	$txtGallery="Image gallery";
	$txtBibTex="BibTex format";
	$txtXml="XML (CNRS format)";
/*	$txtReports="Show master thesis, manuals...";
	$txtProceed="Show that the publications bound to conferences";
	$txtArticle="Show that the publications bound to articles";
	$txtBook="Show that the publications bound to books";
	$txtThese="Show all the thesis";
	$txtAll_p="Show all publications"; */

	$txtOnlyFullText="Hide references without full text";
	$txtOptExclude="Display only this types of publication:";
	$txtOptBooks="Books, InBooks, Booklets and inCollections";
	$txtOptArticles="Articles";
	$txtOptConf="Proceedings and inProceedings";
	$txtOptMisc="Misc publications";
	$txtOptThesis="PhdThesis and MastersThesis";
	$txtOptContrat="TechReports";
	$txtOptInterne="Manuals and unpublished";
	
	$txtToutes="Any";
	$imgChercher="search.png";
	$txtBetween="or between";
	$txtAnd="and";
	$txtAllF=$txtAllM="All";
	$txtOptionAff="<img src='$local_server$images_path/fleche_bas.gif'> Advanced search <img src='$local_server$images_path/fleche_bas.gif'>";
	$txtOptionAffOff="<img src='$local_server$images_path/fleche_haut.gif'> Simple search <img src='$local_server$images_path/fleche_haut.gif'>";
	break;
}
?>

<script language="javascript">
function check_author()
{
  a = document.searchform.author.value;
  espace=/(\w.+)\s(\w.+)/;
  if (a.search(espace)!=-1) 
  	document.searchform.searchType_author.disabled=false;
  else 
  	document.searchform.searchType_author.disabled=true;
}
function check_text()
{
  t = document.searchform.title.value;
  espace=/(\w.+)\s(\w.+)/;
  if (t.search(espace)!=-1) 
  	document.searchform.searchType_title.disabled=false;
  else 
  	document.searchform.searchType_title.disabled=true;
}
</script>

<?
echo "<center><div class='search' style=\"font-size:small\">\n";
echo " <form name='searchform' action='$local_server$public_path/.' method='get'>\n";

if($_SESSION["search"]!='2')
{
echo "<br><table style='font-size:small' align='center' border='0' cellspacing='8'>\n";

// Author
echo "<tr>\n";
  echo "<td align='right'>\n";
    echo "   <b>$txtAuteur:</b> </td><td colspan='2'> <input type='text' size='40' name='author' value=".'"'.$author.'"'." onBlur='javascript:check_author();'/>\n";
  echo "</td><td>";
    echo "  <select name='searchType_author' ";
    if (count(explode(" ",$author))<=1) echo "disabled=disabled"; 
    echo ">\n";
    echo "  <option value='OR' ";
    if ($searchType_author == "OR") echo "selected";
    echo ">$txtOr</option>\n";
    echo "  <option value='AND' ";
    if ($searchType_author == 'AND') echo "selected";
    echo ">$txtAnd</option>\n";
    echo "  </select>\n";
  echo "</td>\n";
echo "</tr>\n";

// Word of Title
echo "<tr>\n";
  echo "<td align='right'>\n";
    echo "   <b>$txtTitre:</b> </td><td colspan='2'> <input type='text' size='40' name='title' value='$title' onBlur='javascript:check_text();'/>\n";
  echo "</td><td>";
    echo "  <select name='searchType_title' ";
    if (empty($searchType_title) or (count(explode(" ",$title))<=1)) echo "disabled=disabled"; 
    echo ">\n";
    echo "  <option value='OR' ";
    if ($searchType_title == "OR") echo "selected";
    echo ">$txtOr</option>\n";
    echo "  <option value='AND' ";
    if ($searchType_title == 'AND') echo "selected";
    echo ">$txtAnd</option>\n";
    echo "  </select>\n";
  echo "</td>\n";
echo "</tr>\n";

// Team
echo "<tr>\n";
  echo "<td align='right'>\n";
    echo " <b>$txtEquip:</b>  <select name='team' size='1'>\n";
    echo " <option value='toutes'>$txtAllF</option>";
    $resu=sqlQuery("select sigle,id from equip where is_lab='yes'");
    while($table=mysql_fetch_array($resu))
    {
	echo $team."|".$table[0];
	if($team==$table[1])
		echo " <option value='$table[1]' selected >$table[0]</option>\n";
	else
		echo " <option value='$table[1]'>$table[0]</option>\n";
    }
    echo " </select>\n";
  echo "</td>\n";

// Year or year interval	
//echo "<p>\n";

  echo "<td colspan='3' align='right'>\n";
    echo "<b>$txtAnnee:</b>  <select name='year'>\n";
    echo "    <option value='-1'";
    if (!isset($year))
      echo " selected='selected'";
    echo ">$txtToutes</option>\n";
    $y=date("Y");
    #while ($y>=1989) Limited to 2000 cause DB is incomplete before
    while ($y>1989)
    {
      echo "    <option value='$y'";
      if ($y==$year)
      echo " selected='selected'";
      echo ">$y</option>\n";
      $y--;
    }
    echo "   </select>\n";
    echo " <b>$txtBetween</b> <input type='text' size='4' name='annee1' value='$annee1' /><b>$txtAnd</b> <input type='text' size='4' name='annee2'value='$annee2' />";
  echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";

// Display mode
echo "   <p>\n";
  echo "   <b>$txtDisplay:</b> <select name='display'>\n";
//  echo "     <option value='list'".(($display=="list")?" selected='selected'":"").">$txtList</option>\n";
  echo "     <option value='rap'".(($display=="rap")?" selected='selected'":"").">$txtClassRapActi</option>\n";
//  echo "     <option value='gallery'".(($display=="gallery")?" selected='selected'":"").">$txtGallery</option>\n";
  echo "     <option value='bibtex'".(($display=="bibtex")?" selected='selected'":"").">$txtBibTex</option>\n";
// XML, dÈsactivÈ
//  if(isset($_SESSION[logged]) && $_SESSION[login] == "frey") 
//    echo "     <option value='xml'".(($display=="xml")?" selected='selected'":"").">$txtXml</option>\n";
  echo "   </select>\n";
echo "</p>";

echo "<p>\n";
while(list($key,$val)=each($option))
{
	$valeur.="$key=$val&";
}

}
// advanced search
if($_GET["aff"]!=1)
  {
	
	$valeur.="search=$_SESSION[search]&Hide=$_SESSION[Hide]&aff=1&display=$display&Affimg=$_SESSION[Affimg]&author=$author&title=$title&team=$team&year=$year&annee1=$annee1&annee2=$annee2&project=$project&opt_file=$opt_file";
	echo "<a href='$local_server$public_path/.?$valeur'>$txtOptionAff</a><br>";
  } 
else
  {
	$valeur.="search=$_SESSION[search]&Hide=$_SESSION[Hide]&aff=0&display=$display&Affimg=$_SESSION[Affimg]&author=$author&title=$title&team=$team&year=$year&annee1=$annee1&annee2=$annee2&project=$project";
	echo "<a href='$local_server$public_path/.?$valeur'>$txtOptionAffOff</a><br><br>";

// asking for Project
echo " <b>$txtProjet:</b> <select name='project' size='1'>\n";
	echo " <option value='tous'>$txtAllM</option>\n";
	$resu=sqlQuery("select id,prname from projects");
	while($table=mysql_fetch_array($resu))
	{
	  if($project==$table[0])
		echo " <option value='$table[0]' selected>$table[1]</option>\n";
	  else
		echo " <option value='$table[0]'>$table[1]</option>\n";
	}
	echo " </select>";
echo "<br />\n";

// Only display references with integral text
echo "<p>$txtOnlyFullText  <input type='checkbox' name='opt_file' value='true'";
if (!empty($_GET['opt_file'])) echo " checked='checked'";
echo "/></p>\n";
	
// select specific Types to display
echo "<table width=100%  style=\"font-family:arial;font-size:12px;\">";
echo "<tr><td colspan=2 align=center><b>$txtOptExclude</b></td></tr>";
echo "<tr><td align=right width=140><input type='checkbox' name='opt_books' value='true' ";if (!empty($_GET['opt_books'])) echo"checked='checked'";echo "/></td>";
  echo "<td>$txtOptBooks\n</td></tr>";
echo "<tr><td align=right><input type='checkbox' name='opt_articles' value='true' ";if (!empty($_GET['opt_articles'])) echo"checked='checked'";echo "/></td>";
  echo "<td>$txtOptArticles\n</td></tr>";
echo "<tr><td align=right><input type='checkbox' name='opt_conf' value='true' ";if (!empty($_GET['opt_conf'])) echo"checked='checked'";echo "/></td>";
  echo "<td>$txtOptConf\n</td></tr>";
echo "<tr><td align=right><input type='checkbox' name='opt_misc' value='true' ";if (!empty($_GET['opt_misc'])) echo"checked='checked'";echo "/></td>";
  echo "<td>$txtOptMisc\n</td></tr>";
echo "<tr><td align=right><input type='checkbox' name='opt_thesis' value='true' ";if (!empty($_GET['opt_thesis'])) echo"checked='checked'";echo "/></td>";
  echo "<td>$txtOptThesis\n</td></tr>";
echo "<tr><td align=right><input type='checkbox' name='opt_contrat' value='true' ";if (!empty($_GET['opt_contrat'])) echo"checked='checked'";echo "/></td>";
  echo "<td>$txtOptContrat\n</td></tr>";
echo "<tr><td align=right><input type='checkbox' name='opt_interne' value='true' ";if (!empty($_GET['opt_interne'])) echo"checked='checked'";echo "/></td>";
  echo "<td>$txtOptInterne\n</td></tr>";
echo "</table>"; 

/*
echo "<table width=100%  style=\"font-family:arial;font-size:12px;\">
      <tr align='left'>";
echo "<td><input type='checkbox' name='all_p' value='true'";if (!empty($all_p)) echo"checked='checked'";echo "/>$txtAll_p\n</td>";
echo "<td><input type='checkbox' name='proc' value='true'";if (!empty($proc)) echo"checked='checked'";echo "/>$txtProceed\n</td>";
echo "</tr><tr align='left'>";
echo "<td><input type='checkbox' name='arti' value='true'";if (!empty($arti)) echo"checked='checked'";echo "/>$txtArticle\n</td>";
echo "<td><input type='checkbox' name='book' value='true'";if (!empty($book)) echo"checked='checked'";echo "/>$txtBook\n</td>";
echo "</tr><tr align='left'>";
echo "<td><input type='checkbox' name='full' value='true'";if (!empty($full)) echo"checked='checked'";echo "/>$txtReports\n</td>";
echo "<td><input type='checkbox' name='thes' value='true'";if (!empty($thes)) echo"checked='checked'";echo "/>$txtThese\n</td>";
echo "</tr></table>"; */
}
echo "   <input type='image' style='margin-top:10px' src='$local_server$images_path/$imgChercher' />\n";
echo "   <input type='hidden' name='search' value=1 />\n";
echo "   <input type='hidden' name='Hide' value='$_SESSION[Hide]' />\n";
echo "   <input type='hidden' name='aff' value='$_GET[aff]' />\n";
//if($_GET["aff"]!=1)	echo "   <input type='hidden' name='display' value='$display' />";
echo " </form>\n";
echo "</div></center>\n\n";
?>
