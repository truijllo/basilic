<?php

function printConference($row)
{
global $lg;
switch($lg)
{
	case "sp":
	$txtPhd="Tesis";
	$txtHDR="Research Supervision Diploma";
	$txtMaster="Tesis principal";
	$txtTechRep="Informe T&eacute;cnico ";
	$txtAll="Todas";
	break;
	case "de":
	$txtPhd="PhD These";
	$txtHDR="Forschung überwachung Diplom";
	$txtMaster="Vorlagenthese";
	$txtTechRep="Technischer Report";
	$txtAll="Alle";
	break;
	case "fr":
	$txtPhd="Th&egrave;se";
	$txtHDR="Habilitation &agrave; Diriger des Recherches";
	$txtMaster="Raport de DEA";
	$txtTechRep="Rapport de recherche";
	$txtAll="Toutes";
	break;
	case "en":
	$txtPhd="PhD thesis";
	$txtHDR="Research Supervision Diploma";
	$txtMaster="Masters thesis" ;
	$txtTechRep="Technical Report";
	$txtAll="All";
	break;
}
 switch($row["entry"])
    {
    case "InProceedings":
    	if(substr($row[location],strlen($row[location])-1,strlen($row[location]))=="]")
	    	$loc=substr($row[location],0,strlen($row[location])-1);
	else
		$loc=$row[location];
      if($row[booktitle]!="")
      	$champ="$row[booktitle]";
      if($row[proceedings]!="" and $row[proceedings] != $row[booktitle])
// ancienne ligne en dessous, sécurité pour éviter d'afficher 2x
//      if($row[proceedings]!="")
      	$champ.=", $row[proceedings]";
      if($loc!="")
      	$champ.=", $loc";
      if(substr($champ,0,1)==",")
      	$champ=substr($champ,2,strlen($champ));
      echo "$champ";
      break;
      
    case "Article":
      echo "$row[journal]";
      break;
      
    case "Book":
    case "InBook":
      echo $row["publisher"];
      break;
      
    case "PhdThesis":
      if ($row["type"] == "Habilitation &agrave; Diriger des Recherches") echo $txtHDR." - ".$row["school"];
      else echo $txtPhd." - ".$row["school"];
      break;

    case "MastersThesis":
      echo $txtMaster.' - '.$row["school"]." ";
      break;
      
    case "TechReport":
      echo "$txtTechRep $row[number], $row[institution]";
      break;
      
    case "InCollection":
      echo "$row[booktitle], $row[publisher]";
      break;

    default:
      echo $row["entry"];
      break;
    }
 
 if (!empty($row["volume"]))
	 echo ", Volume $row[volume]";
 if (!empty($row["number"]))
	echo ", Number $row[number]";
 if (!empty($row["pages"]))
	echo ", page $row[pages]";

  echo " - $row[month] $row[year]\n";
}

function printAuthors($id)
{
  $aresult = sqlQuery("SELECT * FROM authors,publiauthors where authors.id=publiauthors.idAuthor AND publiauthors.idPubli=$id ORDER BY rank ASC");

  $first=true;
  while ($aresult && $arow=mysql_fetch_array($aresult))
    {
      if (!$first)
	echo ",\n";
      if (!empty($arow["url"]))
	echo "   <a href='$arow[url]'>$arow[first] $arow[last]</a>";
      else
	echo "   $arow[first] $arow[last]";
      $first = false;
    }
}

# Called by displayPublication()
function printPubliLine($row)
{
  global $lg;
  global $local_server,$public_path;
  $id=$row["id"];
  $year=$row["year"];
  $bibtex=$row["bibTex"];
  $path="$local_server$public_path/$year/$bibtex";

//ajouter pour supprimer les image
if($_SESSION["Affimg"]==0)
{
  if (basilic_rights("files")) $criterion = ""; else $criterion = "AND protect='public'";
  // Retrieve associated documents
  $dresult = sqlQuery("SELECT type,source,sizeX,sizeY FROM docs,publidocs where docs.id=publidocs.idDoc AND publidocs.idPubli=$id AND (type='IMG') $criterion ORDER BY source ASC");
  
  while ($dresult && $drow=mysql_fetch_array($dresult))
    {
      $type=$drow["type"];
      $src=$drow["source"];
      if ($type=="IMG" && !isset($firstImage))
	{
	  $firstImage = 1;
	  $ratio=1.0*$drow["sizeX"]/$drow["sizeY"];
	  $h=60.0;
	  $w=$h*$ratio;
	  $pt=13;
	  if ($w>80)
	    {
	      $w=80;
	      $h=$w/$ratio;
	      $pt=13 + (60-$h)/2.0;
	    }

	  $w=floor($w);
	  $h=floor($h);
	  $pt=floor($pt)."px";

	  $thumb ="  <div style='padding-top:$pt'><a href='$path'>\n";
	  $thumb.="   <img src='$path/.thumbs/$src.jpg' width='$w' height='$h' alt='$bibtex'/></a>\n";
	  $thumb.="  </div>\n";
	}
    }

//fin suppression des images
}

if (basilic_rights("files")) $criterion = ""; else $criterion = "AND protect='public'";
// thumbs for files
 $dresult = sqlQuery("SELECT type,source FROM docs,publidocs where docs.id=publidocs.idDoc AND publidocs.idPubli=$id AND (type='PDF' OR type='PS' or type='TXT') $criterion ORDER BY type DESC,source ASC");
 while ($dresult && $drow=mysql_fetch_array($dresult))
 {
      $type=$drow["type"];
      $src=$drow["source"];

      if ($type=="PDF") $docs.="   <a href=\"$path/$src\"><img alt='[Download PDF version]' class='doc' src='$local_server$images_path/pdf.png' width='19' height='19' alt='$src' /></a>\n";
      if ($type=="PS")  $docs.="   <a href=\"$path/$src\"><img alt='[Download PS version]' class='doc' src='$local_server$images_path/ps.png' width='19' height='19' alt='$src' /></a>\n";
      if ($type=="TXT")  $docs.="   <a href=\"$path/$src\"><img alt='[Download TXT version]' class='doc' src='$local_server$images_path/txt.png' width='19' height='19' alt='$src' /></a>\n";
 }
    
  if (isset($thumb))
    {
      echo " <div class='thumb image'>\n";
      echo " $thumb";
      echo " </div>\n";
  echo " <div class='ref'>\n";
    }
  else
//    echo " <div class='thumb'></div>\n";
    echo " <div class='ref_nothumb'>\n";

  echo "  <div class='title'>\n   <a href=\"$path/\">\n    ".$row["title"]."\n   </a> &nbsp; \n"; 
  echo "$docs  </div>\n";

  // Search for authors
  echo "  <div class='authors'>\n";
  printAuthors($id);
  echo "\n  </div>\n";

  echo "  <div class='conf'>\n   ";
  printConference($row);
  echo "  </div>\n";
  echo " </div>\n\n";
}

function printGalleryLine($row)
{
  global $lg;
  global $local_server,$public_path;
  global $protected;
  $id=$row["id"];
  $year=$row["year"];
  $bibtex=$row["bibTex"];
  $path="$local_server$public_path/$year/$bibtex";

  echo " <div class='ref'>\n";
  echo "  <div class='title'>\n   <a href=\"$path/\">\n    ".$row["title"]."\n   </a>\n  </div>\n"; 

  // Search for authors
  echo "  <div class='authors'>\n";
  printAuthors($id);
  echo "\n  </div>\n";

  echo "  <div class='conf'>\n   ";
  printConference($row);
  echo "  </div>\n";

  echo " </div>\n\n";

  echo " <div class='imgMovies'>\n";

//ajouter pour suppriemr les image
if($_SESSION["Affimg"]==0)
{
  if (basilic_rights("files")) $criterion = ""; else $criterion = "AND protect='public'";
  // Retrieve associated documents
  $dresult = sqlQuery("SELECT type,source,sizeX,sizeY,protect FROM docs,publidocs where docs.id=publidocs.idDoc AND publidocs.idPubli=$id AND (type='IMG' OR type='MOV') $criterion ORDER BY type ASC,source ASC");
  
  while ($dresult && $drow=mysql_fetch_array($dresult))
    {
      if ($drow["protect"] == "public") $add_path = ""; else $add_path = "$protected/";
      $type=$drow["type"];
      $src=$drow["source"];

      if (($type=="IMG") || ($type=="MOV"))
	{
	  $ratio=1.0*$drow["sizeX"]/$drow["sizeY"];
	  $h=60.0;
	  $w=$h*$ratio;
	  $pt=13;
	  if ($w>80)
	    {
	      $w=80;
	      $h=$w/$ratio;
	      $pt=13 + (60-$h)/2.0;
	    }

	  if ($type=="MOV")
	    $class="movie";
	  else
	    $class="image";

	  $w=floor($w);
	  $h=floor($h);
	  $pt=floor($pt)."px";

	  echo "  <div class='thumb $class'><div style='padding-top:$pt'><a href='$path/$add_path$src'>\n";
	  echo "  <img src='$path/.thumbs/$src.jpg' width='$w' height='$h' alt='$src $sizeText'/></a></div></div>\n";
	}
    }
   //ajouter pour supprimer les image
   }
  echo " </div>\n\n";
}

function printBibTex($row,$bool)
{
  global $local_server,$public_path, $images_path;
  if (!$row["id"]) { die ("No publication id given"); }

  $aresult = sqlQuery("SELECT first, last, url FROM authors, publiauthors WHERE authors.id = publiauthors.idAuthor AND publiauthors.idPubli=$row[id] ORDER BY rank");

  while ($arow = mysql_fetch_array($aresult))
    {
      $firsts = explode("-",$arow[first]);
      $finalFirst = "";
      foreach ($firsts as $first) {
	if ($finalFirst == "")
	  $finalFirst .= substr($first,0,1).".";
	else 
	  $finalFirst .= "-".substr($first,0,1).".";
      }
	  
      if (!$authors)
	$authors = "$arow[last], ".$finalFirst;
      else
	$authors .= " and $arow[last], ".$finalFirst;
	
    }


  mysql_free_result ($aresult);

  $res = "@$row[entry]".'{'."$row[bibTex],\n";

  if ($row["entry"] == "Proceedings")
    $res .= "  editor       = ".'{'."$authors},\n";
  else
    $res .= "  author       = ".'{'."$authors},\n";
  
  $res .= "  title        = ".'{'."$row[title]},\n";
  
  if ($row["booktitle"])   { $res .= "  booktitle    = ".'{'."$row[booktitle]},\n"; }
  if ($row["journal"])     { $res .= "  journal      = ".'{'."$row[journal]},\n"; }
  if ($row["school"])      { $res .= "  school       = ".'{'."$row[school]},\n"; }
  if ($row["chapter"])     { $res .= "  chapter      = ".'{'."$row[chapter]},\n"; }
  if ($row["institution"]) { $res .= "  institution  = ".'{'."$row[institution]},\n"; }

  if ($row["number"])      { $res .= "  number       = ".'{'."$row[number]},\n"; }
  if ($row["series"])      { $res .= "  series       = ".'{'."$row[series]},\n"; }
  if ($row["volume"])      { $res .= "  volume       = ".'{'."$row[volume]},\n"; }
  if ($row["pages"])       { $res .= "  pages        = ".'{'."$row[pages]},\n"; }

  if ($row["month"])       { $res .= "  month        = ".'{'."$row[month]},\n"; }
  if ($row["year"])        { $res .= "  year         = ".'{'."$row[year]},\n"; }

  if ($row["editor"])      { $res .= "  editor       = ".'{'."$row[editor]},\n"; }
  if ($row["publisher"])   { $res .= "  publisher    = ".'{'."$row[publisher]},\n"; }
  if ($row["organization"]){ $res .= "  organization = ".'{'."$row[organization]},\n"; }
  if ($row["address"])     { $res .= "  address      = ".'{'."$row[address]},\n"; }
  if ($row["edition"])     { $res .= "  edition      = ".'{'."$row[edition]},\n"; }
  if ($row["note"])        { $res .= "  note         = ".'{'."$row[note]},\n"; }
  if ($row["type"])        { $res .= "  type         = ".'{'."$row[type]},\n"; }
  if ($row["optkey"])      { $res .= "  key          = ".'{'."$row[optkey]},\n"; }
  if ($row["keywords"])    { $res .= "  keywords     = ".'{'."$row[keywords]},\n"; }

  // added 2006/03/27
  if ($row["issn"])    	   { $res .= "  issn         = ".'{'."$row[issn]},\n"; }
  if ($row["doi"])    	   { $res .= "  doi          = ".'{'."$row[doi]},\n"; }
   
   if ( !$row[url] )
	  $res .= "  url          = ".'{'."$local_server$public_path/$row[year]/$row[bibTex]},\n";
   else
      	  $res .= "  url          = ".'{'."$row[url]\n";
  
  $pattern = array("é","è","ê","ë","á","à","â","ä","í","ì","î","ï",
		   "ó","ò","ô","ö","ú","ù","û","ü","ç","Ç","ñ","Ñ","&");
  $replace = array("\'e","\`e","\^e","{\\\"e}","\'a","\`a","\^a","{\\\"a}","\'{\i}","\`{\i}","\^{\i}","{\\\"{\i}}",
		   "\'o","\`o","\^o","{\\\"o}","\'u","\`u","\^u","{\\\"u}","\c{c}","\c{C}","\~{n}","\~{N}","\&");

  $res = str_replace($pattern, $replace, $res);
  if($bool==1) echo "<pre class='bibtex'>\n$res}\n</pre>\n\n";
  return $res;
}

function escapeXMLChars($str)
{
  $ascii = array("&", ">", "<");
  $xml   = array("&amp;amp;", "&amp;lt;", "&amp;gt;");

  return str_replace($ascii, $xml, $str);
}

function printXML($row)
{
  if (!$row["id"]) { die ("No publication id given"); }

  if (($row["entry"]=="Misc") || ($row["entry"]=="Manual") ||
      ($row["entry"]=="Unpublished") || ($row["entry"]=="Booklet"))
    return;

  echo "<pre>\n";
  echo " &lt;DESC_REF&gt;\n";
  
  echo "  &lt;AUTEURS&gt;\n";

  $aresult = sqlQuery("SELECT first, last, url FROM authors, publiauthors WHERE authors.id = publiauthors.idAuthor AND publiauthors.idPubli=$row[id] ORDER BY rank");
  while ($arow = mysql_fetch_array($aresult))
    echo "   &lt;AUT_REF&gt;$arow[last] $arow[first]&lt;/AUT_REF&gt;\n";
  mysql_free_result ($aresult);
  echo "  &lt;/AUTEURS&gt;\n";
  
  echo "  &lt;TITR_REF&gt;".escapeXMLChars($row["title"])."&lt;/TITR_REF&gt;\n";
  echo "  &lt;ANN_PUB&gt;$row[year]&lt;/ANN_PUB&gt;\n";

  if ($row["number"] || $row["volume"] || $row["pages"] || $row["series"])
    {
      echo "  &lt;COLL_REF&gt;\n";
      if ($row["number"]) { echo "   &lt;NUM&gt;$row[number]&lt;/NUM&gt;\n"; }
      if ($row["series"]) { echo "   &lt;COL_LIB&gt;".escapeXMLChars($row[series])."&lt;/COL_LIB&gt;\n"; }
      if ($row["volume"]) { echo "   &lt;VOL&gt;$row[volume]&lt;/VOL&gt;\n"; }
      if ($row["pages"])  { echo "   &lt;PAG&gt;$row[pages]&lt;/PAG&gt;\n"; }
      echo "  &lt;/COLL_REF&gt;\n";
    }

   switch($row["entry"])
    {
    case "Article":
      echo "  &lt;INFO_PUB&gt;\n";
      echo "   &lt;REVUE&gt;".escapeXMLChars($row["journal"])."&lt;/REVUE&gt;\n";
      echo "  &lt;/INFO_PUB&gt;\n";
      $typeP="ART";
      $typeS="ACL";
      break;
      
    case "InProceedings":
      echo "  &lt;INFO_COL&gt;\n";
      echo "   &lt;TEXT_COL&gt;".escapeXMLChars($row["booktitle"])."&lt;/TEXT_COL&gt;\n";
      echo "  &lt;/INFO_COL&gt;\n";
      $typeP="COL";
      $typeS="ACT";
      break;
      
    case "Proceedings":
    case "Book":
      echo "  &lt;INFO_OUV&gt;\n";
      echo "   &lt;EDIT&gt;".escapeXMLChars($row[publisher])."&lt;/EDIT&gt;\n";
      echo "  &lt;/INFO_OUV&gt;\n";
      $typeP="OUV";
      $typeS="RCH";
      break;
      
    case "PhdThesis":
    case "MastersThesis":
      echo "  &lt;INFO_TRU&gt;\n";
      echo "   &lt;UNIV_TRU&gt;".escapeXMLChars($row[school])."&lt;/UNIV_TRU&gt;\n";
      echo "  &lt;/INFO_TRU&gt;\n";
      $typeP="TRU";
      if ($row["entry"] == "MastersThesis")
	$typeS="MEM";
      else
	$typeS="THN";
      break;
      
    case "TechReport":
      echo "  &lt;INFO_RAP&gt;\n";
      echo "   &lt;TEXT_RAP&gt;$row[number], ".escapeXMLChars($row[institution])."&lt;/TEXT_RAP&gt;\n";
      echo "  &lt;/INFO_RAP&gt;\n";
      $typeP="RAP";
      $typeS="REC";
      break;

    case "InCollection":
    case "InBook":
      echo "  &lt;INFO_COV&gt;\n";
      if ($row["booktitle"]) echo "   &lt;TITR_OUV&gt;".escapeXMLChars($row[booktitle])."&lt;/TITR_OUV&gt;";
      if ($row["editor"])    echo "   &lt;EDIT_OUV&gt;".escapeXMLChars($row[editor])."&lt;/EDIT_OUV&gt;";
      if ($row["publisher"]) echo "   &lt;TEXT_OUV&gt;".escapeXMLChars($row[publisher])."&lt;/TEXT_OUV&gt;";
      echo "  &lt;/INFO_COV&gt;\n";
      $typeP="COV";
      break;

    default:
      $typeP="Unknown";
      break;
    }
   
  echo "  &lt;THEM_SCI&gt;\n";
  echo "   &lt;CN_SCI&gt;\n    &lt;SEC_SCI&gt;7&lt;/SEC_SCI&gt;\n   &lt;/CN_SCI&gt;\n";
  if ($row["keywords"]) echo "   &lt;MC_LIB&gt;".escapeXMLChars($row[keywords])."&lt;/MC_LIB&gt;\n";
  if ($row["note"]) echo "   &lt;MC_LIB&gt;".escapeXMLChars($row[note])."&lt;/MC_LIB&gt;\n";
  echo "  &lt;/THEM_SCI&gt;\n";

  echo "  &lt;INFO_REF&gt;\n";
  // echo "   &lt;COD_UNIT&gt;UMRxxxx&lt;/COD_UNIT&gt;\n"; filled automatically
  echo "   &lt;COD_EQU&gt;ENSPS&lt;/COD_EQU&gt;\n";
  echo "   &lt;TYPE_P&gt;$typeP&lt;/TYPE_P&gt;\n";
  if (!empty($typeS)) echo "   &lt;TYPE_S&gt;$typeS&lt;/TYPE_S&gt;\n";
  echo "   &lt;TYPE_L&gt;$row[entry]&lt;/TYPE_L&gt;\n";
  echo "   &lt;STATUT&gt;PUB&lt;/STATUT&gt;\n";
  echo "   &lt;ANN_PROD&gt;$row[year]&lt;/ANN_PROD&gt;\n";
  echo "   &lt;ID_ORIG&gt;ENSPS-$row[bibTex]&lt;/ID_ORIG&gt;\n";
  echo "  &lt;/INFO_REF&gt;\n";

  echo "  &lt;INFO_URL&gt;\n";
  echo "   &lt;URL_TEXTE&gt;$local_server$public_path/$row[year]/$row[bibTex]&lt;/URL_TEXTE&gt;\n";
  echo "   &lt;URL_IMAGE&gt;$local_server$publix_path/$row[year]/$row[bibTex]&lt;/URL_IMAGE&gt;\n";
  echo "  &lt;/INFO_URL&gt;\n";

  echo " &lt;/DESC_REF&gt;\n</pre>\n\n";
}

function string_to_table($string) {
    
    if ($_GET[debug] == "1") print "<p style=\"font-size:10px;\">DEBUG :<br>search string : ".$string."<br>\n";
    $pre_table = explode("\"", " ".stripslashes($string));
    for ($i = 0; $i < count($pre_table); $i++) {
        if ($i%2 == 0) { //string with spaces
            $table = explode(" ", $pre_table[$i]);
            for ($j = 0; $j < count($table); $j++)
                $res[] = $table[$j];
        }
        else    // string into brackets -> search as is
            $res[] = $pre_table[$i];
    }
    for ($k = 0; $k < count($res); $k++) {
        if ($res[$k] != "") $res2[] = $res[$k];
    }

    if ($_GET[debug] == "1") { print "table of authors : "; print_r($res2); print "</p>\n";}
    return $res2;
}

function bibQueryResults($option)
{
  global $searchType_title;
  global $searchType_author;
  
  // Perform query according to search fields
  $tables="publis, months";
  $criterion="WHERE publis.month=months.name";

  if (!empty($option["idAuthor"]))
    {
      $tables.=", publiauthors, authors";
      $criterion .= " AND authors.id=$option[idAuthor] AND publis.id=publiauthors.idPubli AND authors.id=publiauthors.idAuthor";
    }
  else
    if (!empty($option["author"]))
      {
	//$authors = explode(" ", $option[author]);
	$authors = string_to_table($option[author]);
	$aTable = 1;  // for numbering author table aliases
	$paTable = 1; // for numbering publiauthor table aliases
	$localTables = "";
	if (count($authors) == 1) {
	  // only one author given
	  $localCriterion .= " AND publis.id=publiauthors.idPubli AND publiauthors.idAuthor=authors.id AND authors.last='$authors[0]'";
	  if (!empty($option['first']))
                $first=$option['first'];
                $localCriterion .= " AND SOUNDEX(authors.first)=SOUNDEX('$first')";
	  $localTables .= ", publiauthors, authors";
	}
	else { 
	  // more authors given
	  if ($searchType_author == 'AND') 
	    {  // AND search
	    $localCriterion .= " AND (1";
	    foreach ($authors as $author) {
	      $localCriterion .= " AND publis.id = p$paTable.idPubli AND p$paTable.idAuthor = a$aTable.id AND a$aTable.last = '$author'";
	      $localTables .= ", authors AS a$aTable, publiauthors AS p$paTable";
	      $aTable++; $paTable++;
	    }
	  }
	  else 
	    {  // OR search
	    $localTables .= ", publiauthors, authors";
	    $localCriterion .= " AND publis.id=publiauthors.idPubli AND publiauthors.idAuthor=authors.id AND (0"; 
	    foreach ($authors as $author) {
	      $localCriterion .= " OR authors.last = '$author'";
	    }
	  }
	  $localCriterion .= ")";
	}
	$criterion .= $localCriterion;
	$tables .= $localTables;
      }

  if (!empty($option["title"]))
    {
        $localCriterion = " AND (";
	$titles = explode(" ", $option[title]);
	if (count($titles) == 1) 
	  { 
	    // only one keyword given
	    $localCriterion .= "0"; 
	    $searchType_title = 'OR';
	  }
	else
	{
	    if ($searchType_title == 'AND') $localCriterion .= "1";
	    else { $localCriterion .= "0"; $searchType_title = 'OR'; }
	}
	foreach ($titles as $title) {
	  $localCriterion .= " $searchType_title (title LIKE '%$title%' OR keywords LIKE '%$title%')";
	}
	$localCriterion .= ")";
	$criterion .= $localCriterion;
        //$criterion .= " AND (title like '%$option[title]%' OR keywords like '%$option[title]%')";
    }

  if (!empty($option["project"]))
  	if($option["project"]!="tous")
		$criterion .= " AND project = $option[project]";

  if (!empty($option["team"]))
  	if($option["team"]!="toutes") {
		$tables .= ", publiequip, equip";
		$criterion .= " AND equip.id=$option[team] AND publis.id=publiequip.idPubli AND equip.id=publiequip.idEquip";
	}
	else	
		$criterion .= " AND publis.team NOT LIKE 'extern'";

  if (!empty($option["bibtex"]))
    $criterion .= " AND bibTex='$option[bibtex]'";

  if (!empty($option["opt_file"]))
    {
      if (basilic_rights("files")) $addCrit = ""; else $addCrit = " AND protect='public' ";
      $tables.=", publidocs, docs";
      $criterion .= " AND publidocs.idPubli=publis.id AND publidocs.idDoc=docs.id AND (docs.type='PPT' OR docs.type='PDF' OR docs.type='PS' OR docs.type='TXT')".$addCrit;
    }

// customised publication List
  if (!empty($option["list"])) {
    $criterion .= " AND (0 ";
    $listIds = explode(",",$option["list"]);
    foreach ($listIds as $Id) {
      $criterion .= "OR publis.id=$Id ";
    }
    $criterion .= ")";
  }

// options d'affichages
  $opt_set = 0;
  if ($option["opt_books"] or $option["opt_articles"] or $option["opt_conf"] or $option["opt_misc"] or $option["opt_thesis"] or $option["opt_contrat"] or $option["opt_interne"]) $criterion .= " AND (";
  
  if ($option["opt_books"]) {$criterion .="entry = 'Book' or entry = 'inBook' or entry = 'Booklet' or entry = 'InCollection'"; $opt_set=1;}
  if ($option["opt_articles"]) {if ($opt_set) $criterion .= " or "; $criterion .="entry = 'Article'"; $opt_set=1;}
  if ($option["opt_conf"]) { if ($opt_set) $criterion .=" or "; $criterion .= "entry = 'Proceedings' or entry = 'InProceedings'"; $opt_set=1;}
  if ($option["opt_misc"]) { if ($opt_set) $criterion .=" or "; $criterion .= "entry = 'Misc'"; $opt_set=1;}
  if ($option["opt_thesis"]) { if ($opt_set) $criterion .=" or "; $criterion .="entry = 'PhdThesis' or entry = 'MastersThesis'"; $opt_set=1;}
  if ($option["opt_contrat"]) { if ($opt_set) $criterion .=" or "; $criterion .="entry = 'TechReport'"; $opt_set=1;}
  if ($option["opt_interne"]) { if ($opt_set) $criterion .=" or "; $criterion .="entry = 'Manual' or entry = 'Unpublished'"; $opt_set=1;}

  if ($option["opt_books"] or $option["opt_articles"] or $option["opt_conf"] or $option["opt_misc"] or $option["opt_thesis"] or $option["opt_contrat"] or $option["opt_interne"]) $criterion .= ")";

  if (!empty($option["year"]) && $option["year"]!="-1")
   $criterion .= " AND year='$option[year]'";

  if (!empty($option["annee1"]) && !empty($option["annee2"]))
   $criterion .= " AND year between '$option[annee1]' and '$option[annee2]'";

  if (empty($option["query"]))
  {
  	if($option["display"]=="rap" or empty($option["display"]))
	    $option["query"] = "SELECT DISTINCT publis.* FROM $tables $criterion ORDER BY year DESC, class_acti ASC, months.id DESC";
	else
	    $option["query"] = "SELECT DISTINCT publis.* FROM $tables $criterion ORDER BY year DESC, title ASC";
  }
 
 if ($_GET["debug"] == "1") print "<p style=\"font-size:10px;\">DEBUG (SQL query):<br>".$option["query"]."</p>\n";
  return sqlQuery($option["query"]);
}


//added function
function printName($name,$last,$url)
{
	while((list($key,$prenom)=each($name))&&(list($key1,$nom)=each($last))&&(list($key2,$addr)=each($url)))
	{
		$nom=mb_strtoupper($nom);
		$prenom=mb_strtoupper($prenom);
		if($prenom!='')
		{
			$nom=substr($prenom,0,1).".".$nom;
		}
		$deb=-1;
		$deb=strncmp($addr,"http",4);
		if($deb==0 && $addr!='')
		{
			$nom="<a href=\"$addr\" target=\"_blank\">".$nom."</a>";
		}
		echo "$nom ";
	}
}

// Activity Report display
function printRAP($row,$frame=True)
{
	global $class;
	global $local_server,$public_path, $images_path;
        global $protected;
	global $hdr, $rap_c;
	$id=$row["id"];
	$resu_lien=sqlQuery("select year,bibTex from publis where id='$row[id]'");
	$ligne_lien=mysql_fetch_assoc($resu_lien);
	$lien=$local_server."$public_path/".$ligne_lien[year].'/'.$ligne_lien[bibTex];
	$path="$local_server$public_path/$ligne_lien[year]/$ligne_lien[bibTex]";

	if (basilic_rights("files")) $criterion = ""; else $criterion = "AND protect='public'";
// thumbs for files
	 $dresult = sqlQuery("SELECT type,source,protect FROM docs,publidocs where docs.id=publidocs.idDoc AND publidocs.idPubli=$id AND (type='PDF' OR type='PS' or type='TXT') 		 $criterion ORDER BY type DESC,source ASC");
	 while ($dresult && $drow=mysql_fetch_array($dresult))
	 {
	      if ($drow["protect"] == "public") $add_path = ""; else $add_path = "$protected/";
	      $type=$drow["type"];
	      $src=$drow["source"];

	      if ($type=="PDF") $docs.="   <a href=\"$path/$add_path$src\"><img alt='[Download PDF version]' class='doc' src='$local_server$images_path/pdf.png' width='19' height='19' alt='$src' /></a>\n";
	      if ($type=="PS")  $docs.="   <a href=\"$path/$add_path$src\"><img alt='[Download PS version]' class='doc' src='$local_server$images_path/ps.png' width='19' height='19' alt='$src' /></a>\n";
	      if ($type=="TXT")  $docs.="   <a href=\"$path/$add_path$src\"><img alt='[Download TXT version]' class='doc' src='$local_server$images_path/txt.png' width='19' height='19' alt='$src' /></a>\n";
	 }

  $resu = sqlQuery("SELECT * FROM authors,publiauthors where authors.id=publiauthors.idAuthor AND publiauthors.idPubli=$id ORDER BY rank ASC");
//	$resu=sqlQuery("select  first,last,url from (authors A join publiauthors PA on A.id=PA.idAuthor) where PA.idPubli='$row[id]' order by rank");

	$nba=mysql_num_rows($resu);
	if($nba>=1)
	{
		$ligne=mysql_fetch_array($resu);
		$name[1]=mb_strtoupper($ligne["first"]);
		$last[1]=mb_strtoupper($ligne["last"]);
		$url[1]=$ligne["url"];
	}
	if($nba>=2)
	{
		$ligne=mysql_fetch_array($resu);
		$name[2]=mb_strtoupper($ligne["first"]);
		$last[2]=mb_strtoupper($ligne["last"]);
		$url[2]=$ligne["url"];
	}
	if($nba>=3)
	{
		$ligne=mysql_fetch_array($resu);
		$name[3]=mb_strtoupper($ligne["first"]);
		$last[3]=mb_strtoupper($ligne["last"]);
		$url[3]=$ligne["url"];
	}
	$i=1;
	while($ligne=mysql_fetch_array($resu))
	{
		$pos=3+$i;
		$name["$pos"]=mb_strtoupper($ligne["first"]);
		$last["$pos"]=mb_strtoupper($ligne["last"]);
		$url["$pos"]=$ligne["url"];
		$i++;
	}

	$resu=sqlQuery("select `range`, type from publis where id='$row[id]'");
	$pt=mysql_fetch_array($resu);

        switch($nba)
        {
         case "1":
	 	$tmp=substr($last[1],0,3).substr($row[year],2,2);
		break;
	case "2":
		$tmp=substr($last[1],0,1).substr($last[2],0,1).substr($row[year],2,2);
		break;
	case "3":
		$tmp=substr($last[1],0,1).substr($last[2],0,1).substr($last[3],0,1).substr($row[year],2,2);
		break;
	default :
		$tmp=substr($last[1],0,1).substr($last[2],0,1).substr($last[3],0,1).substr($row[year],2,2);$nba=3;break;
	}
	if($class!=$row['class_acti'])
		$acti="change";
	else
		$acti="continue";
	$class=$row[class_acti];
	if($acti=="change")
	{
		switch($row['class_acti'])
		{
			case 1:
			echo"<table width=100%><tr align=left><td><b>Works and collaborations</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>";
			break;
			case 2:
			echo"<table width=100%><tr align=left><td><b>Peer-reviewed publications in International Journals</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>";
			break;
			case 3:
			echo"<table width=100%><tr align=left><td><b>Peer-reviewed publications in National Journals</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>";
			break;
			case 4:
			echo"<table width=100%><tr align=left><td><b>Peer-reviewed talks in international events </td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>";
			break;
			case 5:
			echo"<table width=100%><tr align=left><td><b>Peer-reviewed talks in national events</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>";
			break;
			case 6:
			echo"<table width=100%><tr align=left><td><b>Various talks and publications</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>";
			break;
			case 7:
			echo"<table width=100%><tr align=left><td><b>Contractual reports</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>";
			break;
			case 8:
			echo"<table width=100%><tr align=left><td><b>Internal reports</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>";
			break;
			case 9:
			echo"<table width=100%><tr align=left><td><b>Thesis and academic qualification</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>";
			break;
		}
	}
	
	switch($row['entry'])
	{
		// parametrages selon la classe d'entrée
		case "Article":
			if($pt["range"]==national)
				$titre="3-".$tmp;
			else
				$titre="2-".$tmp;
			break;
		case "InProceedings":
			if($pt["range"]==national)
				$titre="5-".$tmp;
			else
				$titre="4-".$tmp;
			break;
		case "InBook":
			$titre="1-".$tmp;
			break;	
		case "Book":
			$titre="1-".$tmp;
			break;	
		case "PhdThesis":
			$titre="9-".$tmp;
			break;
		case "MastersThesis":
			$titre="9-".$tmp;
			break;
		case "TechReport":
			$titre="7-".$tmp;
			break;
		case "Misc":
			if ($pt["type"] == "$rap_c") $titre="7-".$tmp;
			else $titre="6-".$tmp;
			break;
		case "Booklet":
			$titre="1-".$tmp;
			break;
		case "InCollection":
			$titre="1-".$tmp;
			break;
		case "Manual":
			$titre="8-".$tmp;
			break;
		case "Proceedings":
			if($pt["range"]==national)
				$titre="5-".$tmp;
			else
				$titre="4-".$tmp;
			break;
		case "Unpublished":
			$titre="8-".$tmp;
			break;
	}

	echo"<table width=100%>
		<tr align='left' valign='top'><td width=12% rowspan=3 align='center'><b>[$titre]</b><br /><br />";
		// link to cart in standard view
		if ((!$_SESSION[Hide]) and ( $frame))
		  {
			echo "<a href=\"";
			if (empty($_GET["list"])) print "javascript:OuvrirPopup('$local_server$public_path/cart.php?add=$row[id]','Modification de la s&eacute;lection', 'resizable=no, location=no, width=500, height=80, menubar=no, status=no, scrollbars=no, menubar=no')\" title='Add this publication to your selection'><img src='$local_server$images_path/panier.gif' alt='[Add to your selection]'>";
			else print "$local_server$public_path/cart.php?del=$row[id]\" title='Remove this publication from your selection'><img src='$local_server$images_path/panier_off.jpg' alt='[Remove from your selection]'>";
			echo "</a>";
		  }
		print "</td><td width=88%>
		<a href='$lien'>$row[title]</a>  $docs</td></tr><tr align='left'><td width=88%>";
  		// Search for authors
		  echo "  <div class='authors'>\n";
		  printAuthors($id);
		  echo "\n  </div></td></tr>\n";
		//printName($name,$last,$url);
	echo "<tr align='left'><td width=88%>";
	
	switch($row['entry'])
	{
		// parametrages selon la classe d'entrée
		case "Article":
			echo "$row[journal]";
			if (!empty($row[publisher])) echo " $row[publisher]";	// rajouté le 25/04/2006
			if (!empty($row[pages])) echo " pages $row[pages]";	
			if (!empty($row[volume])) echo " vol. $row[volume]";	
			if (!empty($row[number])) echo " num. $row[number]";	
			echo " - $row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			if (!empty($row[issn])) echo "<br><small><i>issn: $row[issn]</i></small>";	
			if (!empty($row[doi])) echo "&nbsp;&nbsp;<small><i>doi: <a href=\"http://dx.doi.org/".$row[doi]."\">$row[doi]</a></i></small>";	
			break;
		case "InProceedings":
			echo" $row[booktitle]";
			if (!empty($row[publisher])) echo " $row[publisher]";	
			if (!empty($row[series])) echo " $row[series]";	
			if (!empty($row[pages])) echo " pages $row[pages]";	
			if (!empty($row[volume])) echo " vol. $row[volume]";	
			if (!empty($row[number])) echo " num. $row[number]";	
			if (!empty($row[editor])) echo " $row[editor]";	
			if (!empty($row[organization])) echo " $row[organization]";	
			if (!empty($row[address])) echo " $row[address]";	
			echo" - $row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			if (!empty($row[issn])) echo "<br><small><i>issn: $row[issn]</i></small>";	
			if (!empty($row[doi])) echo "&nbsp;&nbsp;<small><i>doi: <a href=\"http://dx.doi.org/".$row[doi]."\">$row[doi]</a></i></small>";	
			break;
		case "InBook":
			echo" $row[publisher], chap. $row[chapter], pages $row[pages]";
			if (!empty($row[series])) echo " $row[series]";	
			if (!empty($row[address])) echo " $row[address]";	
			if (!empty($row[volume])) echo " vol. $row[volume]";	
			if (!empty($row[number])) echo " num. $row[number]";	
			if (!empty($row[edition])) echo " $row[edition]";	
			if (!empty($row[type])) echo " $row[type]";	
			if (!empty($row[proceedings])) echo " $row[proceedings]";	
			echo" - $row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			break;	
		case "Book":
			echo" $row[publisher]";
			if (!empty($row[series])) echo " $row[series]";	
			if (!empty($row[address])) echo " $row[address]";	
			if (!empty($row[volume])) echo " vol. $row[volume]";	
			if (!empty($row[number])) echo " num. $row[number]";	
			if (!empty($row[edition])) echo " $row[edition]";	
			echo" - $row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			if (!empty($row[issn])) echo "<br><small><i>issn: $row[issn]</i></small>";	
			if (!empty($row[doi])) echo "&nbsp;&nbsp;<small><i>doi: <a href=\"http://dx.doi.org/".$row[doi]."\">$row[doi]</a></i></small>";	
			break;	
		case "PhdThesis":
		case "MastersThesis":
			if (!empty($row[type])) echo "$row[type]<br>";	
			echo"$row[school] - ";
			if (!empty($row[address])) echo "$row[address] - ";	
			echo"$row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			break;
		case "TechReport":
			echo" $row[institution]";
			if (!empty($row[address])) echo " $row[address]";	
			if (!empty($row[number])) echo " num. $row[number]";	
			if (!empty($row[type])) echo " $row[type]";	
			echo" - $row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			break;
		case "Misc":
			if (!empty($row[type])) echo "$row[type] - ";	
			if (!empty($row[howpublished])) echo " $row[howpublished]";	
			echo" - $row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			break;
		case "Booklet":
			if (!empty($row[howpublished])) echo " $row[howpublished]";	
			if (!empty($row[address])) echo " $row[address]";	
			echo" - $row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			break;
		case "InCollection":
			echo" $row[booktitle], $row[publisher]";
			if (!empty($row[editor])) echo " $row[editor]";	
			if (!empty($row[pages])) echo " pages $row[pages]";	
			if (!empty($row[volume])) echo " vol. $row[volume]";	
			if (!empty($row[number])) echo " num. $row[number]";	
			if (!empty($row[series])) echo " $row[series]";	
			if (!empty($row[chapter])) echo " chap. $row[chapter]";	
			if (!empty($row[address])) echo " $row[address]";	
			if (!empty($row[edition])) echo " $row[edition]";	
			if (!empty($row[type])) echo " $row[type]";	
			echo" - $row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			break;
		case "Manual":
			if (!empty($row[organization])) echo " $row[organization]";	
			if (!empty($row[address])) echo " $row[address]";	
			if (!empty($row[edition])) echo " $row[edition]";	
			echo" - $row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			break;
		case "Proceedings":
			if (!empty($row[editor])) echo " $row[editor]";	
			if (!empty($row[series])) echo " $row[series]";	
			if (!empty($row[pages])) echo " pages $row[pages]";	
			if (!empty($row[volume])) echo " vol. $row[volume]";	
			if (!empty($row[number])) echo " num. $row[number]";	
			if (!empty($row[organization])) echo " $row[organization]";	
			if (!empty($row[address])) echo " $row[address]";	
			if (!empty($row[publisher])) echo " $row[publisher]";	
			echo" - $row[month] $row[year]";
			//if (!empty($row[note])) echo "<br>$row[note]";	
			break;
		case "Unpublished":
			echo" - $row[month] $row[year]";
			//echo " $row[note]";
			break;

	}
	
	echo "</tr></table>";
}


function displayResults($result, $option, $frame=true)
{
  global $lg, $class;
  switch($lg)
  {
  	case "fr":
	$txtIn="en";
	$txtPubli="Publications";
	break;
	case "en":
	$txtIn="in";
	$txtPubli="Publications";
	break;
	case "sp":
	$txtIn="en";
	$txtPubli="Publicaciones";
	break;
	case "de":
	$txtIn="in";
	$txtPubli="Publikationen";
	break;
  }
  
  if (empty($option["pg"]))
    $option["pg"] = 1;

  if ($option["display"]=="gallery")
    echo "<div class='results gallery'>\n";
  else
    if ($option["display"]=="bibtex")
      echo "<div class='results bibtex'>\n";
    else
      echo "<div class='results list'>\n";

  if (empty($option["nbPerPage"])) $option["nbPerPage"]=10;
  if (empty($option["fullYear"]))  $option["fullYear"]=false;
  
  // Page limits
  if ($option["pg"]==-1)
    {
      $minCount=0;
      $maxCount=99999;
    }
  else
    {
      $minCount=($option["pg"]-1)*$option["nbPerPage"];
      $maxCount=$minCount+$option["nbPerPage"];
    }

  $count=-1;
  $addHR=false;
  $stopAtNextYear=false;
  $prevYear=-1;

  if ($option["display"]=="xml")
    {
      echo "<pre>\n &lt;?xml version=\"1.0\" encoding='ISO-8859-1'?&gt;\n";
      echo " &lt;PUBCNRS&gt;\n</pre>\n";
    }

  while ($result && $row=mysql_fetch_array($result))
    {
      $count++;
      if ($count<$minCount) continue;
      if ($count>=$maxCount)
	if ($option["fullYear"])
	  {
	    $stopAtNextYear = true;
	    $maxCount = 99999;
	  }
	else
	  break;

      if ($row["year"] != $prevYear)
	{
	  if ($stopAtNextYear) break;
	  $prevYear=$row["year"];

	  if (($option["display"]=="bibtex") || ($option["display"]=="xml"))
	    {
	      if ($addHR)		
		echo " <hr style=\"height: 5px;\"/>\n\n";
	    }
	  else
	    {
	  	// for RAP format, do as if class changes
	  	$class = -1;

	      if ($addHR)
		echo " <div class='interYear'>&nbsp;</div>\n\n";
	  echo "<div class='year'>$txtPubli $txtIn $prevYear </div>\n\n";
	    }
	  $addHR = false;
	}

      if ($addHR)
	echo " <hr />\n\n";
      else
	$addHR = true;

// gallery not used
//      if ($option["display"]=="gallery")
//	printGalleryLine($row);
//      else
	if ($option["display"]=="bibtex") {
	  printBibTex($row,1);}
	else
	  if ($option["display"]=="xml")
	    printXML($row);
	  else
//	    if ($option["display"]=="rap")
	      printRAP($row,$frame);
//	    else
// normal list not used	   
//	      printPubliLine($row);
    }

  if ($option["display"]=="xml")
    echo "<pre>&lt;/PUBCNRS&gt;</pre>\n";

  if ($addHR)
    echo " <hr />\n\n";

  echo "</div>\n";

  echo "<br style='clear:both'/>\n";
}


function displayPublications($option)
{
  require("utils.inc");
//  require("utils.php");
  sqlConnect();
  $result = bibQueryResults($option);
  displayResults($result, $option);
  mysql_close();
}

?>
