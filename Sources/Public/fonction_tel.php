<?php
require_once("utils.php");

function escapeXMLCharsAff($str)
{
  $ascii = array("&", ">", "<");
  $xml   = array("&amp;amp;", "&amp;lt;", "&amp;gt;");

  return str_replace($ascii, $xml, $str);
}

function printXML_Aff($row,$fd)
{
  global $public_path;
  if (!$row["id"]) { die ("No publication id given"); }

  if (($row["entry"]=="Misc") || ($row["entry"]=="Manual") ||
      ($row["entry"]=="Unpublished") || ($row["entry"]=="Booklet"))
    return;

  //$variable.="<pre>\n";
  $variable.="<DESC_REF>\n";
  
  $variable.="  <AUTEURS>\n";

  $aresult = sqlQuery("SELECT first, last, url FROM authors, publiauthors WHERE authors.id = publiauthors.idAuthor AND publiauthors.idPubli=$row[id] ORDER BY rank");
  while ($arow = mysql_fetch_array($aresult))
    $variable.="   <AUT_REF>$arow[last] $arow[first]</AUT_REF>\n";
  mysql_free_result ($aresult);
  $variable.="  </AUTEURS>\n";
  
  $variable.="  <TITR_REF>".escapeXMLCharsAff($row[title])."</TITR_REF>\n";
  $variable.="  <ANN_PUB>$row[year]</ANN_PUB>\n";

  if ($row["number"] || $row["volume"] || $row["pages"] || $row["series"])
    {
      $variable.="  <COLL_REF>\n";
      if ($row["number"]) { $variable.="   <NUM>$row[number]</NUM>\n"; }
      if ($row["series"]) { $variable.="   <COL_LIB>".escapeXMLCharsAff($row[series])."</COL_LIB>\n"; }
      if ($row["volume"]) { $variable.="   <VOL>$row[volume]</VOL>\n"; }
      if ($row["pages"])  { $variable.="   <PAG>$row[pages]</PAG>\n"; }
      $variable.="  </COLL_REF>\n";
    }

   switch($row["entry"])
    {
    case "Article":
      $variable.="  <INFO_PUB>\n";
      $variable.="   <REVUE>".escapeXMLCharsAff($row[journal])."</REVUE>\n";
      $variable.="  </INFO_PUB>\n";
      $typeP="ART";
      $typeS="ACL";
      break;
      
    case "InProceedings":
      $variable.="  <INFO_COL>\n";
      $variable.="   <TEXT_COL>".escapeXMLCharsAff($row[booktitle])."</TEXT_COL>\n";
      $variable.="  </INFO_COL>\n";
      $typeP="COL";
      $typeS="ACT";
      break;
      
    case "Proceedings":
    case "Book":
      $variable.="  <INFO_OUV>\n";
      $variable.="   <EDIT>".escapeXMLCharsAff($row[publisher])."</EDIT>\n";
      $variable.="  </INFO_OUV>\n";
      $typeP="OUV";
      $typeS="RCH";
      break;
      
    case "PhdThesis":
    case "MastersThesis":
      $variable.="  <INFO_TRU>\n";
      $variable.="   <UNIV_TRU>".escapeXMLCharsAff($row[school])."</UNIV_TRU>\n";
      $variable.="  </INFO_TRU>\n";
      $typeP="TRU";
      if ($row["entry"] == "MastersThesis")
	$typeS="MEM";
      else
	$typeS="THN";
      break;
      
    case "TechReport":
      $variable.="  <INFO_RAP>\n";
      $variable.="   <TEXT_RAP>$row[number], ".escapeXMLCharsAff($row[institution])."</TEXT_RAP>\n";
      $variable.="  </INFO_RAP>\n";
      $typeP="RAP";
      $typeS="REC";
      break;

    case "InCollection":
    case "InBook":
      $variable.="  <INFO_COV>\n";
      if ($row["booktitle"]) $variable.="   <TITR_OUV>".escapeXMLCharsAff($row[booktitle])."</TITR_OUV>";
      if ($row["editor"])    $variable.="   <EDIT_OUV>".escapeXMLCharsAff($row[editor])."</EDIT_OUV>";
      if ($row["publisher"]) $variable.="   <TEXT_OUV>".escapeXMLCharsAff($row[publisher])."</TEXT_OUV>";
      $variable.="  </INFO_COV>\n";
      $typeP="COV";
      break;

    default:
      $typeP="Unknown";
      break;
    }
   
  $variable.="  <THEM_SCI>\n";
  $variable.="   <CN_SCI>\n    <SEC_SCI>7</SEC_SCI>\n   </CN_SCI>\n";
  if ($row["keywords"]) $variable.="   <MC_LIB>".escapeXMLCharsAff($row[keywords])."</MC_LIB>\n";
  if ($row["note"]) $variable.="   <MC_LIB>".escapeXMLCharsAff($row[note])."</MC_LIB>\n";
  $variable.="  </THEM_SCI>\n";

  $variable.="  <INFO_REF>\n";
  $variable.="   <COD_EQU>ENSPS</COD_EQU>\n";
  $variable.="   <TYPE_P>$typeP</TYPE_P>\n";
  if (!empty($typeS)) $variable.="   <TYPE_S>$typeS</TYPE_S>\n";
  $variable.="   <TYPE_L>$row[entry]</TYPE_L>\n";
  $variable.="   <STATUT>PUB</STATUT>\n";
  $variable.="   <ANN_PROD>$row[year]</ANN_PROD>\n";
  $variable.="   <ID_ORIG>ENSPS-$row[bibTex]</ID_ORIG>\n";
  $variable.="  </INFO_REF>\n";

  $variable.="  <INFO_URL>\n";
  $variable.="   <URL_TEXTE>http://$_SERVER[HTTP_HOST]$public_path/$row[year]/$row[bibTex]</URL_TEXTE>\n";
  $variable.="   <URL_IMAGE>http://$_SERVER[HTTP_HOST]$public_path/$row[year]/$row[bibTex]</URL_IMAGE>\n";
  $variable.="  </INFO_URL>\n";

  $variable.=" </DESC_REF>\n\n";
  fwrite($fd,$variable);
}





function printName_Aff($name,$last,$url,$bool)
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
			$nom1="<a href=\"$addr\" target=\"_blank\">".$nom."</a>";
		}
		if($bool==1)echo "$nom1 ";
		$n.="$nom, ";
	}
	return substr($n,0,strlen($n)-2);
}

function printRAP_Aff($row)
{
	global $class;
	global $local_server, $public_path;
	$id=$row["id"];
	$path="$local_server$public_path/$row[year]/$row[bibTex]";
	$resu=sqlQuery("select  first,last,url from (authors A join publiauthors PA on A.id=PA.idAuthor) where PA.idPubli='$row[id]' ORDER BY rank ASC");

	$nba=mysql_num_rows($resu);
	$i=1;
	while($ligne=mysql_fetch_array($resu))
	{
		$pos=$i;
		$name["$pos"]=mb_strtoupper($ligne["first"]);
		$last["$pos"]=mb_strtoupper($ligne["last"]);
		$url["$pos"]=$ligne["url"];
		$i++;
	}
	
	$resu=sqlQuery("select `range` from publis where id='$row[id]'");
	$pt=mysql_fetch_array($resu);

	// "Rap" reference algorithm
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
			$aff.="\nWorks or collaborations\n";
			break;
			case 2:
			$aff.="\nPeer-reviewed publications in international Journals\n";
			//$aff.="\nArticles dans des revues internationales &agrave; comit&eacute; de lecture\n";
			break;
			case 3:
			$aff.="\nPeer-reviewed publications in national Journals\n";
			//$aff.="\nArticles dans des revues nationales &grave; comit&eacute; de lecture\n";
			break;
			case 4:
			$aff.="\nPeer-reviewed talks in international events\n";
			//$aff.="\nCommunications &agrave; des manifestations internationales &agrave; comit&eacute; de lecture\n";
			break;
			case 5:
			$aff.="\nPeer-reviewed talks in national events\n";
			//$aff.="\nCommunications &agrave; des manifestations nationales &agrave; comit&eacute; de lecture\n";
			break;
			case 6:
			$aff.="\nVarious talks and publications\n";
			//$aff.="\nCommunications et publications diverses\n";
			break;
			case 7:
			$aff.="\nContractual reports\n";
			//$aff.="\nRapports de contrats\n";
			break;
			case 8:
			$aff.="\nInternal reports\n";
			//$aff.="\nRapports internes\n";
			break;
			case 9:
			$aff.="\nThesis and academic qualification\n";
			//$aff.="\nTh&egrave;ses et habilitations\n";
			break;
		}
	}
	$aff.="\n";
	
	$nom=printName_Aff($name,$last,$url,0);
	switch($row['entry'])
	{
		case "Article":
			if($pt["range"]=="national")
			{
				$titre="[3-".$tmp."]";
			}
			else
			{
				$titre="[2-".$tmp."]";
			}
			$aff.="$titre	$row[title]\n		$nom $docs\n		$row[journal] $row[publisher] ";
			if (!empty($row[pages])) 	$aff.= "pages $row[pages] ";
			if (!empty($row[volume])) 	$aff.= "vol. $row[volume] ";
			if (!empty($row[number]))	$aff.= "num. $row[number] ";
			$aff .= " - $row[month] $row[year]";
			break;
		case "InProceedings":
			if($pt["range"]=="national")
			{
				$titre="[5-".$tmp."]";
			}
			else
			{
				$titre="[4-".$tmp."]";
			}
			$aff.="$titre	$row[title]\n		$nom\n		$row[booktitle] $row[publisher] $row[series] ";
			if (!empty($row[pages])) 	$aff.="pages $row[pages] ";
			if (!empty($row[volume])) 	$aff.="vol. $row[volume] ";
			if (!empty($row[number])) 	$aff.="num. $row[number] ";
			$aff.="$row[editor] $row[organization] $row[address] - $row[month] $row[year]";
			break;
		case "InBook":
			$titre="[1-".$tmp."]";
			$aff.="$titre	$row[title]\n		$nom\n		$row[publisher] chap. $row[chapter] pages $row[pages] $row[series] $row[address] ";
			if (!empty($row[volume])) $aff.="vol. $row[volume] ";
			if (!empty($row[number])) $aff.="num. $row[number] ";
			$aff.="$row[edition] $row[type] $row[proceedings] - $row[month] $row[year]";
			break;
		case "Book":
			$titre="[1-".$tmp."]";
			$aff.="$titre	$row[title]\n		$nom\n		$row[publisher] $row[series] $row[address] ";
			if (!empty($row[volume])) $aff.="vol. $row[volume] ";
			if (!empty($row[number])) $aff.="num. $row[number] ";
			$aff.="$row[edition] - $row[month] $row[year]";
			break;
		case "PhdThesis":
		case "MastersThesis":
			$titre="[9-".$tmp."]";
			$aff="$titre	$row[title]\n		$nom\n";
			if (!empty($row[type]))		$aff .= "$row[type]\n";
			$aff .= "		$row[school] ";
			if (!empty($row[address]))	$aff .= "$row[address] - ";
			$aff .= "$row[month] $row[year]";
			break;
		case "TechReport":
			$titre="[7-".$tmp."]";
			$aff.="$titre	$row[title]\n		$nom\n		$row[institution] $row[address] ";
			if (!empty($row[number])) $aff.="num. $row[number] ";
			$aff.="$row[type] - $row[month] $row[year]";
			break;
		case "Misc":
			$titre="[6-".$tmp."]";
			$aff.="$titre	$row[title]\n		$nom\n";
			if (!empty($row[type]))		$aff .= "$row[type]\n";
			$aff .= "		$row[howpublished] - $row[month] $row[year]";
			break;
		case "Booklet":
			$titre="[1-".$tmp."]";
			$aff.="$titre	$row[title]\n		$nom\n		$row[howpublished] $row[address] - $row[month] $row[year]";
			break;
		case "InCollection":
			$titre="[1-".$tmp."]";
			$aff.="$titre	$row[title]\n		$nom\n		$row[booktitle] $row[publisher] $row[editor] ";
			if (!empty($row[pages])) $aff.="pages $row[pages] ";
			if (!empty($row[volume])) $aff.="vol. $row[volume] ";
			if (!empty($row[number])) $aff.="num. $row[number] ";
			$aff.="$row[series] ";
			if (!empty($row[chapter])) $aff.="chap. $row[chapter] ";
			$aff.="$row[address] $row[edition] $row[type] - $row[month] $row[year]";
			break;
		case "Manual":
			$titre="[8-".$tmp."]";
			$aff.="$titre	$row[title]\n		$nom\n		$row[organization] $row[address] $row[edition] - $row[month] $row[year]";
			break;
		case "Proceedings":
			if($pt["range"]=="national")
			{
				$titre="[5-".$tmp."]";
			}
			else
			{
				$titre="[4-".$tmp."]";
			}	
			$aff.="$titre	$row[title]\n		$nom\n		$row[editor] $row[series] ";
			if (!empty($row[pages])) $aff.="pages $row[pages] ";
			if (!empty($row[volume])) $aff.="vol. $row[volume] ";
			if (!empty($row[number])) $aff.="num. $row[number] ";
			$aff .= "$row[organization] $row[address] $row[publisher] - $row[month] $row[year]";
			break;
		case "Unpublished":
			$titre="8-".$tmp;
			$aff.="$titre	$row[title]\n		$nom\n		$row[month] $row[year]";
			break;
	}
	return $aff;
}

function printHTML_Aff($row)
{
	global $class;
	global $local_server, $public_path;
	$aff = "";
	$id=$row["id"];
	$path="$local_server/$public_path/$row[year]/$row[bibTex]";
	$resu=sqlQuery("select  first,last,url from (authors A join publiauthors PA on A.id=PA.idAuthor) where PA.idPubli='$row[id]' ORDER BY rank ASC");

	$nba=mysql_num_rows($resu);
	$i=1;
	while($ligne=mysql_fetch_array($resu))
	{
		$pos=$i;
		$name["$pos"]=mb_strtoupper($ligne["first"]);
		$last["$pos"]=mb_strtoupper($ligne["last"]);
		$url["$pos"]=$ligne["url"];
		$i++;
	}
	
	$resu=sqlQuery("select `range` from publis where id='$row[id]'");
	$pt=mysql_fetch_array($resu);

	// "Rap" reference algorithm
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
		$tmp=substr($last[1],0,1).substr($last[2],0,1).substr($last[3],0,1).substr($row[year],2,2);$nba=3;
		break;
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
			$aff.="\n<table width=100%><tr align=left><td><b>Works and collaborations</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>\n";
			//$aff.="\n<table width=100%><tr align=left><td><b>Ouvrage ou participation à des ouvrages</td></tr>
			break;
			case 2:
			$aff.="\n<table width=100%><tr align=left><td><b>Peer-reviewed publications in International Journals</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>\n";
			//$aff.="\n<table width=100%><tr align=left><td><b>Articles dans des revues internationales à comité de lecture</td></tr>
			break;
			case 3:
			$aff.="\n<table width=100%><tr align=left><td><b>Peer-reviewed publications in National Journals</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>\n";
			//$aff.="\n<table width=100%><tr align=left><td><b>Articles dans des revues nationales à comité de lecture</td></tr>
			break;
			case 4:
			$aff.="\n<table width=100%><tr align=left><td><b>Peer-reviewed talks in international events 
				</td></tr><tr height=2><td bgcolor=#003399></td></tr></table>\n";
			//$aff.="\n<table width=100%><tr align=left><td><b>Communications à des manifestations internationales à comité de lecture
			break;
			case 5:
			$aff.="\n<table width=100%><tr align=left><td><b>Peer-reviewed talks in national events
				</td></tr><tr height=2><td bgcolor=#003399></td></tr></table>\n";
			//$aff.="\n<table width=100%><tr align=left><td><b>Communications à des manifestations nationales à comité de lecture
			break;
			case 6:
			$aff.="\n<table width=100%><tr align=left><td><b>Various talks and publications</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>\n";
			//$aff.="\n<table width=100%><tr align=left><td><b>Communications et publications diverses</td></tr>
			break;
			case 7:
			$aff.="\n<table width=100%><tr align=left><td><b>Contractual reports</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>\n";
			//$aff.="\n<table width=100%><tr align=left><td><b>Rapports de contrats</td></tr>
			break;
			case 8:
			$aff.="\n<table width=100%><tr align=left><td><b>Internal reports</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>\n";
			//$aff.="\n<table width=100%><tr align=left><td><b>Rapports internes</td></tr>
			break;
			case 9:
			$aff.="\n<table width=100%><tr align=left><td><b>Thesis and academic qualification</td></tr>
				<tr height=2><td bgcolor=#003399></td></tr></table>\n";
			//$aff.="\n<table width=100%><tr align=left><td><b>Theses et habilitations</td></tr>
			break;
		}
	}
	$aff.="\n";
	
	$nom=printName_Aff($name,$last,$url,0);
	$titre 			= "<table width=100%>\n<tr align='left' valign='top'><td width=12% rowspan=3 align='center'><b>";
	$inter_ref_title 	= "</b><br /><br /></td><td width=88%>\n<b>";
	$inter_title_author 	= "</b>\n</td></tr><tr align='left'><td width=88%>\n<div class='authors'>";
	$inter_author_bib	= "\n</div></td></tr>\n<tr align='left'><td width=88%>\n";
	$inter_end		= "</td></tr></table>\n";
	switch($row['entry'])
	{
		case "Article":
			if($pt["range"]=="national")
			{
				$titre.="[3-".$tmp."]";
			}
			else
			{
				$titre.="[2-".$tmp."]";
			}
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom $inter_author_bib\n$row[journal] $row[publisher] ";
			if (!empty($row[pages])) 	$aff.= "pages $row[pages] ";
			if (!empty($row[volume])) 	$aff.= "vol. $row[volume] ";
			if (!empty($row[number]))	$aff.= "num. $row[number] ";
			$aff .= " - $row[month] $row[year]$inter_end";
			break;
		case "InProceedings":
			if($pt["range"]=="national")
			{
				$titre.="[5-".$tmp."]";
			}
			else
			{
				$titre.="[4-".$tmp."]";
			}
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n $inter_author_bib $row[booktitle] $row[publisher] $row[series] ";
			if (!empty($row[pages])) 	$aff.="pages $row[pages] ";
			if (!empty($row[volume])) 	$aff.="vol. $row[volume] ";
			if (!empty($row[number])) 	$aff.="num. $row[number] ";
			$aff.="$row[editor] $row[organization] $row[address] - $row[month] $row[year] $inter_end";
			break;
		case "InBook":
			$titre.="[1-".$tmp."]";
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n $inter_author_bib $row[publisher] chap. $row[chapter] pages $row[pages] $row[series] $row[address] ";
			if (!empty($row[volume])) $aff.="vol. $row[volume] ";
			if (!empty($row[number])) $aff.="num. $row[number] ";
			$aff.="$row[edition] $row[type] $row[proceedings] - $row[month] $row[year]$inter_end";
			break;
		case "Book":
			$titre.="[1-".$tmp."]";
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n $inter_author_bib $row[publisher] $row[series] $row[address] ";
			if (!empty($row[volume])) $aff.="vol. $row[volume] ";
			if (!empty($row[number])) $aff.="num. $row[number] ";
			$aff.="$row[edition] - $row[month] $row[year]$inter_end";
			break;
		case "PhdThesis":
		case "MastersThesis":
			$titre.="[9-".$tmp."]";
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n$inter_author_bib";
			if (!empty($row[type]))		$aff .= "$row[type]\n";
			$aff .= "		$row[school] ";
			if (!empty($row[address]))	$aff .= "$row[address] - ";
			$aff .= "$row[month] $row[year]$inter_end";
			break;
		case "TechReport":
			$titre.="[7-".$tmp."]";
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n $inter_author_bib $row[institution] $row[address] ";
			if (!empty($row[number])) $aff.="num. $row[number] ";
			$aff.="$row[type] - $row[month] $row[year]$inter_end";
			break;
		case "Misc":
			$titre.="[6-".$tmp."]";
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n$inter_author_bib";
			if (!empty($row[type]))		$aff .= "$row[type]\n";
			$aff .= "		$row[howpublished] - $row[month] $row[year]$inter_end";
			break;
		case "Booklet":
			$titre.="[1-".$tmp."]";
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n $inter_author_bib $row[howpublished] $row[address] - $row[month] $row[year]$inter_end";
			break;
		case "InCollection":
			$titre.="[1-".$tmp."]";
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n $inter_author_bib $row[booktitle] $row[publisher] $row[editor] ";
			if (!empty($row[pages])) $aff.="pages $row[pages] ";
			if (!empty($row[volume])) $aff.="vol. $row[volume] ";
			if (!empty($row[number])) $aff.="num. $row[number] ";
			$aff.="$row[series] ";
			if (!empty($row[chapter])) $aff.="chap. $row[chapter] ";
			$aff.="$row[address] $row[edition] $row[type] - $row[month] $row[year]$inter_end";
			break;
		case "Manual":
			$titre.="[8-".$tmp."]";
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n $inter_author_bib $row[organization] $row[address] $row[edition] - $row[month] $row[year]$inter_end";
			break;
		case "Proceedings":
			if($pt["range"]=="national")
			{
				$titre.="[5-".$tmp."]";
			}
			else
			{
				$titre.="[4-".$tmp."]";
			}	
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n $inter_author_bib $row[editor] $row[series] ";
			if (!empty($row[pages])) $aff.="pages $row[pages] ";
			if (!empty($row[volume])) $aff.="vol. $row[volume] ";
			if (!empty($row[number])) $aff.="num. $row[number] ";
			$aff .= "$row[organization] $row[address] $row[publisher] - $row[month] $row[year]$inter_end";
			break;
		case "Unpublished":
			$titre.="[8-".$tmp."]";
			$aff.="$titre $inter_ref_title $row[title]\n $inter_title_author $nom\n $inter_author_bib $row[month] $row[year]$inter_end";
			break;
	}
	return $aff;
}

?>
