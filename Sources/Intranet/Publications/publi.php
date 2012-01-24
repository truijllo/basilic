<?php
session_start();
require_once("../utils.php");

if (!basilic_rights("access")) 
	header("Location: $public_path/noway.php");
?>
<html>
<head>
 <meta http-equiv='pragma' content='no-cache'>
 <meta http-equiv='expires' content='0'>
 <link rel="stylesheet" href="<?echo $css_path;?>/backoffice.css" type="text/css" />
</head>

<body>

<script language="javascript">
function changeEntry()
{
  entrySelect=document.publiForm.selectEntry;
  i=entrySelect.options[entrySelect.selectedIndex].value;
  if (i=="HDR") {location.href="publi.php?entry=PhdThesis&specialType=HDR";}
  else if (i=="Rapport de Contrat") {location.href="publi.php?entry=Misc&specialType=RC";}
  else {location.href="publi.php?entry="+i;}
}
function fillFromSelect()
{
  idx=document.publiForm.selectFill.selectedIndex;
  name=document.publiForm.selectFill.options[idx].value;
  if (document.publiForm.journal)   { document.publiForm.journal.value=name; }
  if (document.publiForm.booktitle) { document.publiForm.booktitle.value=name; }
}
function updateList()
{
  document.publiForm.action = 'publi.php';
  makeAuthorList();
  document.publiForm.submit();
}
function upAuthor()
{
  authorList = document.publiForm.authorSelect;
  for (i=1; i<authorList.length; i++)
    {
      if (authorList.options[i].selected == true )
	{
	  tmpTxt=authorList.options[i-1].text;
	  tmpValue=authorList.options[i-1].value;
	  authorList.options[i-1].text=authorList.options[i].text;
	  authorList.options[i-1].value=authorList.options[i].value;
	  authorList.options[i].text=tmpTxt;
	  authorList.options[i].value=tmpValue;
	  authorList.options[i].selected = false;
	  authorList.options[i-1].selected = true;
	}
    }
}
function downAuthor()
{
  authorList = document.publiForm.authorSelect;
  for (i=0; i<authorList.length-1; i++) {
    if (authorList.options[i].selected == true ) {
      tmpTxt=authorList.options[i+1].text;
      tmpValue=authorList.options[i+1].value;
      authorList.options[i+1].text=authorList.options[i].text;
      authorList.options[i+1].value=authorList.options[i].value;
      authorList.options[i].text=tmpTxt;
      authorList.options[i].value=tmpValue;
      authorList.options[i].selected = false;
      authorList.options[i+1].selected = true;
      break;
    }
  }
}
function delAuthor()
{
  authorList = document.publiForm.authorSelect;
  for ( i=(authorList.length-1); i>=0; i--) {
    if (authorList.options[i].selected == true ) {
      authorList.options[i] = null;
    }
  }
}
function selectAuthorFromList()
{
  if (document.publiForm.selectAuthor.selectedIndex >= 0)
    {
      val=document.publiForm.selectAuthor.options[document.publiForm.selectAuthor.selectedIndex].value;
      k=val.split(",");
      addauthor(k[0],k[2],k[1]);
      document.publiForm.selectAuthor.selectedIndex=-1;
    }
}
function addauthor(idAuthor, first, last)
{
  // window.alert("add "+idAuthor+"/"+first+"/"+last+" author");
  authorList = document.publiForm.authorSelect;
  authorListlen = authorList.length ;
  // Check it is not already in the list
  for(i=0;i<authorListlen;i++)
    {
      //window.alert("i="+authorList.options[i].value+" and author="+idAuthor);
      if (authorList.options[i].value==idAuthor)
	{
	  window.alert("This author is already in the list...");
	  return;
	}
    }
  // Add it if it is ok
  authorList.options[authorListlen]= new Option(first+" "+last, idAuthor);
}
function makeAuthorList()
{
  authors = document.publiForm.authorSelect;
  list="";
  for (i=0; i<authors.length; i++){
    if (i>0) list=list+",";
    list=list+""+authors.options[i].value;
  }
  document.publiForm.authorList.value=""+list;
}
function upTeam()
{
  teamList = document.publiForm.teamSelect;
  for (i=1; i<teamList.length; i++)
    {
      if (teamList.options[i].selected == true )
	{
	  tmpTxt=teamList.options[i-1].text;
	  tmpValue=teamList.options[i-1].value;
	  teamList.options[i-1].text=teamList.options[i].text;
	  teamList.options[i-1].value=teamList.options[i].value;
	  teamList.options[i].text=tmpTxt;
	  teamList.options[i].value=tmpValue;
	  teamList.options[i].selected = false;
	  teamList.options[i-1].selected = true;
	}
    }
}
function downTeam()
{
  teamList = document.publiForm.teamSelect;
  for (i=0; i<teamList.length-1; i++) {
    if (teamList.options[i].selected == true ) {
      tmpTxt=teamList.options[i+1].text;
      tmpValue=teamList.options[i+1].value;
      teamList.options[i+1].text=teamList.options[i].text;
      teamList.options[i+1].value=teamList.options[i].value;
      teamList.options[i].text=tmpTxt;
      teamList.options[i].value=tmpValue;
      teamList.options[i].selected = false;
      teamList.options[i+1].selected = true;
      break;
    }
  }
}
function delTeam()
{
  teamList = document.publiForm.teamSelect;
  for ( i=(teamList.length-1); i>=0; i--) {
    if (teamList.options[i].selected == true ) {
      teamList.options[i] = null;
    }
  }
}
function selectTeamFromList()
{
  if (document.publiForm.selectTeam.selectedIndex >= 0)
    {
      val=document.publiForm.selectTeam.options[document.publiForm.selectTeam.selectedIndex].value;
      k=val.split(",");
      addteam(k[0],k[1]);
      document.publiForm.selectTeam.selectedIndex=-1;
    }
}
function addteam(idTeam, sigle)
{
  // window.alert("add "+idTeam+"/"+first+"/"+last+" author");
  teamList = document.publiForm.teamSelect;
  teamListlen = teamList.length ;
  // Check it is not already in the list
  for(i=0;i<teamListlen;i++)
    {
      //window.alert("i="+teamList.options[i].value+" and team="+idTeam);
      if (teamList.options[i].value==idTeam)
	{
	  window.alert("This team is already in the list...");
	  return;
	}
    }
  // Add it if it is ok
  teamList.options[teamListlen]= new Option(sigle, idTeam);
}
function makeTeamList()
{
  teams = document.publiForm.teamSelect;
  list="";
  for (i=0; i<teams.length; i++){
    if (i>0) list=list+",";
    list=list+""+teams.options[i].value;
  }
  document.publiForm.teamList.value=""+list;
}
</script>

<h1>Publication edition</h1>

<div class='inputForm'>
<form action='publiAction.php' name='publiForm'>
<input type='hidden' name='act' value='' />
<input type='hidden' name='authorList' value='' />
<input type='hidden' name='teamList' value='' />
<?php
// This page can be accessed by 3 means
// - With no parameter, to create a new publication : select publication entry 
// - With a entry parameter, to create a new publication
// - With a publication id, to edit this publication

if (isset($_GET))
{
  if (!empty($_GET["id"]))   $id=$_GET["id"];
  if (!empty($_GET["entry"])) $entry=$_GET["entry"];
  if (!empty($_GET["specialType"])) $specialType=$_GET["specialType"];
}

sqlConnect();

if (!empty($id)) //modification
{
  $result = sqlQuery("SELECT * FROM publis WHERE id=$id");
  if (mysql_num_rows($result) != 1)
    die("Error - Unable to retrieve publication data from this id ($id).");
  $row=mysql_fetch_array($result);
  $entry=$row["entry"];
  echo "<input type='hidden' name='previousBibTex' value='$row[bibTex]'/>\n";
  echo "<input type='hidden' name='previousYear'   value='$row[year]'/>\n";

  // special way for HDR entry
  if ($row[type]=="$hdr") $specialType = "HDR";
  // special way for Contract Repport entry
  if ($row[type]=="$rap_c") $specialType = "RC";
}

echo "<input type='hidden' name='id' value='$id' />\n";
echo "<input type='hidden' name='entry' value='$entry' />\n";
echo "<input type='hidden' name='specialType' value='$specialType' />\n";

if (empty($entry)) //new entry
{
  echo "<p>Please select the type of your entry</p>\n";

  echo "<div class='even'>\n";
  echo "<select name='selectEntry' onChange='changeEntry();'>\n";
  echo " <option value='' >Select an entry type</option>\n";
  $entries=array("Article","InProceedings","PhdThesis","TechReport","InBook","Book","Booklet","InCollection","MastersThesis","Manual","Proceedings","Unpublished","Misc", "HDR", "Rapport de Contrat");
  foreach($entries as $t)
    echo " <option value='$t'>$t</option>\n";
  echo "</select>\n";
  echo "</div>\n\n";

  echo "<div class='information'>\n";
  echo "Here is a short description of these entry types :\n";
  echo " <ul>\n";
  echo "  <li><b>Article</b> : Article from a journal or magazine.</li>\n";
  echo "  <li><b>InProceedings</b> : Article in a conference proceedings.</li>\n";
  echo "  <li><b>PhdThesis</b> : Names says it all.</li>\n";
  echo "  <li><b>TechReport</b> : Report published by a school or an institution.</li>\n";
  echo "  <li><b>InBook</b> : Chapter or part of a book.</li>\n";
  echo "  <li><b>Book</b> : An entire book.</li>\n";
  echo "  <li><b>Booklet</b> : A work printed and bound, with no publisher or institution.</li>\n";
  echo "  <li><b>InCollection</b> : A part of a book having its own title.</li>\n";
//  echo " </ul>\n";
  
//  echo "These types will only be listed when the full display option is activated:\n";
//  echo " <ul>\n";
  echo "  <li><b>MastersThesis</b> : Names says it all.</li>\n";
  echo "  <li><b>Manual</b> : Technical documentation.</li>\n";
  echo "  <li><b>Proceedings</b> : The proceedings of a conference.</li>\n";
  echo "  <li><b>Unpublished</b> : Document not formally published. Remove after publication.</li>\n";
  echo "  <li><b>Misc</b> : All the rest (video, sketch, poster...).</li>\n";
  echo " </ul>\n";

  echo "Special french types :\n";
  echo " <ul>\n";
  echo "  <li><b>HDR</b> : Research Supervision Diploma.</li>\n";
  echo "  <li><b>Rapport de Contrat</b> : Name says it all.</li>\n";
  echo " </ul>\n";
  
  echo "</div>\n\n";
  echo "</form>\n";
  echo "</div>\n\n";
  echo "</body>\n";
  echo "</html>\n";
  exit();
}
// else
  // echo "$entry $row[bibTex]\n\n";

// Determine optional and required fields
$required=array("authors","title","year","team");
switch($entry)
{
 case "Article":
   $required = array_merge($required, array("range","journal", "review"));
   $fields = array("project", "publisher", "volume","number","pages","doi","issn"); // publisher n'est pas standard
   break;
 case "InProceedings":
   $required = array_merge($required, array("range", "booktitle", "review"));
   $fields = array("project", "publisher","series","pages","editor","organization","address","volume","number", "doi", "issn");
   break;
 case "InBook":
   $required = array_merge($required, array("publisher","chapter","pages"));
   $fields = array("project", "series","address","volume","number","edition","type","proceedings");
   break;
 case "Book":
   $required = array_merge($required, array("publisher"));
   $fields = array("project", "series","address","edition","volume","number","doi", "issn");
   break;
 case "PhdThesis":
 case "MastersThesis":
   $required = array_merge($required, array("school"));
   $fields = array("project", "address","type");
   break;
 case "TechReport":
   $required = array_merge($required, array("institution"));
   $fields = array("project", "address","number","type");
   break;
 case "Misc":
   $fields = array("project", "howpublished", "type");
   break;
 case "Booklet":
   $fields = array("project", "howpublished","address");
   break;
 case "InCollection":
   $required = array_merge($required, array("booktitle","publisher"));
   $fields = array("project", "editor","volume","number","series","chapter","pages","address","edition","type");
   break;
 case "Manual":
   $fields = array("project", "organization","address","edition");
   break;
 case "Proceedings":
   $required = array_merge($required, array("range"));
   $fields = array("project", "editor","volume","number","series","address","organization","publisher");
   break;
 case "Unpublished":
   $required = array_merge($required, array("note"));
   $fields = array();
   break;
 default:
   break;
}

$fields = array_merge($required, $fields, array("month","optkey","keywords"));
if ($entry!="Unpublished")
  $fields = array_merge($fields, array("note"));

// Only needed when update author list is called : fill back the rows
foreach($fields as $field)
  if (!empty($_GET[$field]))
     $row[$field] = $_GET[$field];

// Maximum field size in database
$fieldSize = array("address" => 255,
		   "booktitle" => 255,
		   "chapter" => 200,
		   "edition" => 50,
		   "editor" => 255,
		   "howpublished" => 255,
		   "institution" => 255,
		   "journal" => 255,
		   "keywords" => 255,
		   "month" => 30,
		   "note" => 255,
		   "number" => 10,
		   "optkey" => 255,
		   "organization" => 255,
		   "pages" => 15,
		   "publisher" => 255,
		   "school" => 255,
		   "series" => 255,
		   "title" => 255,
		   "type" => 255,
		   "volume" => 20,
		   "issn" => 70,
		   "doi" => 70,
		   "proceedings" => 255,
		   "location" => 255,
		   "team" => 10,
		   "project" => 10,
		   "range" => 15,
		   "review" => 6);

// Help tip to help fill the form appropriately
$tip = array("publisher" 	=> "Springer, ACM Press, Addison Wesley...",
	     "editor" 		=> "Researcher name(s)",
	     "address" 		=> "The address of the institution",
	     "pages" 		=> "Intervals as in 12--34",
	     "optkey" 		=> "Normally inferred from authors",
	     "month" 		=> "jan, feb...",
	     "volume" 		=> "Use volume <b>or</b> number",
	     "organization" 	=> "ACM, Eurographics,... ",
	     "type"      	=> "Research Notes, ...",
	     "series" 		=> "Annual Conference Series, Computer Science,...",
	     "edition"		=> "Second, Third...",
	     "range"		=> "national, international",
	     "review"		=> "peer review committee",
	     "team"		=> "select your team");

function printSelectAuthor()
{
  global $id,$images_path;
  $name = $_GET["name"];

  // Div are not that good
  echo "<table><tr><td>\n";
  echo " <div style='text-align:center'>\n";
  echo "   <span style='font-size:small'>Name</span>\n";
  echo "   <input size='15' maxlength='30' name='name' value='$name'/>\n";
  echo "   &nbsp; <input type='button' value=' Search ... ' style='font-size:10px;' onClick='updateList();'/>\n";

  echo "<br/>\n";

  if (!empty($name))
    $where = "WHERE (last LIKE '%$name%' OR first LIKE '%$name%')";
  else $where = "WHERE 1";
  $where .= " and is_author = 'oui'";

  $result = sqlQuery("SELECT * FROM authors $where ORDER BY last, first ASC");
  $nb = mysql_num_rows($result);
  if ($nb == 0)
    {
      echo "   <p>\n";
      echo "    No author matches<br/>\n";
      echo "    <a href='../Authors/author.php'>Create new author</a>\n";
      echo "   </p>\n";
    }
  else
    {
      echo "   <select name='selectAuthor' size='8' style='font-size:12px;width:20em;margin-top:5px'>\n";
      while ($result && $row=mysql_fetch_array($result))
	echo "   <option value='$row[id],$row[last],$row[first]'>$row[last] $row[first]</option>\n";
      echo "   </select><br/>\n";
    }
  echo " </div>\n";

  echo "</td><td>\n";
  
  echo " <input type='button' value=' Add  >> ' src='$images_path/authorADD.png' style='font-size:10px;' onClick='selectAuthorFromList();'/>\n";
  echo "</td><td>\n";

  echo " <select multiple name='authorSelect' size='10' style='font-size:12px;width:20em'>\n";

  if (!empty($_GET["authorList"]))
    {
      $authorsIds = explode(",", $_GET["authorList"]);
      foreach ($authorsIds as $authorId)
	{
	  $aresult = sqlQuery("select id,first,last from authors WHERE id=$authorId");
	  if (!$result)
	    error("No author associated with id $authorId");
	  $arow=mysql_fetch_array($aresult);
	  echo "   <option value='$arow[id]'>$arow[first] $arow[last]</option>\n";
	}
    }
  else
    if (!empty($id))
      {
	$aresult=sqlQuery("select DISTINCT id,first,last from authors,publiauthors WHERE publiauthors.idPubli=$id AND publiauthors.idAuthor=authors.id ORDER BY rank ASC");
	while ($aresult && $arow=mysql_fetch_array($aresult))
	  echo "   <option value='$arow[id]'>$arow[first] $arow[last]</option>\n";
      }

  echo " </select>\n";
  
  echo "</td><td>\n";

  echo " <div>\n";
  echo "  <a href='javascript:upAuthor();'><img src='$images_path/authorUP.png' alt='Get up'></a><br />\n";
  echo "  <a href='javascript:downAuthor();'><img style='margin-bottom:12px;margin-top:2px' src='$images_path/authorDOWN.png' alt='Get down'></a><br />\n";
  echo "  <a href='javascript:delAuthor();'><img src='$images_path/authorDEL.png' alt='Remove'></a>\n";
  echo " </div>\n";
  echo "</td></tr></table>\n";

}

function printSelectYear()
{
  global $row;
  global $id;
  echo " <select name='year' size='1'>";

  if (empty($id))
    $row["year"] = date("Y");
  
  for ($i=(date("Y"));$i>1985;$i--)
    {
      echo "  <option value='$i'";
      if ($i==$row["year"])
	echo " selected";
      echo ">$i</option>\n";
    }
  echo " </select>\n";
}

function printSelectPortee()
{
	global $id;
	$resu=sqlQuery("select `range` from publis where id='$id'");
	if(mysql_num_rows($resu)!=0)
		$range=mysql_result($resu,0);
	else 
		$range="";
	echo " <select name='range' size='1'>";
	if($range=="national")
	{
		echo " <option selected>national</option>";
		echo " <option>international</option>";
	}
	else
	{
		echo " <option >national</option>";
		echo " <option selected>international</option>";
	}
	echo " </select>\n";
}

function printSelectLect()
{
	global $id;
	$resu=sqlQuery("select com_lect from publis where id='$id'");
	if(mysql_num_rows($resu)!=0)
		$com_lect=mysql_result($resu,0);
	else 
		$com_lect="";
	echo " <select name='com_lect' size='1'>";
	if($com_lect=="without")
	{
		echo " <option selected>without</option>";
		echo " <option>with</option>";
	}
	else
	{
		echo " <option >without</option>";
		echo " <option selected>with</option>";
	}
	echo " </select>\n";
}

function printSelectMonth()
{
	global $id;
	$mont=sqlQuery("select month from publis where id='$id'");
	if(mysql_num_rows($mont)!=0)
		$mon=mysql_result($mont,0);
	else
		$mon="";
	$tab_month=array("","january","february","march","april","may","june","july","august","september","october","november","december");
	echo "<select name='month' size='1'>";
	foreach($tab_month as $month)
	{
		if($month==$mon)
			echo "<option selected value='$month'>$month</option>";
		else
			echo "<option value='$month'>$month</option>";
	}
	echo "</select>";
}

function printSelectProject()
{
	global $id;
	$pro=sqlQuery("select project from publis where id='$id'");
	if(mysql_num_rows($pro)!=0)
		$projet=mysql_result($pro,0);
	else
		$projet="";
	echo " <select name='project' size='1'>";
	$resu=sqlQuery("select id, prname from projects ORDER BY id");
	while($table=mysql_fetch_array($resu))
	{
		if($table[0]==$projet)
			echo " <option value='$table[0]' selected>$table[1]</option>";
		else
			echo " <option value='$table[0]'>$table[1]</option>";
	}
	echo " </select>\n";
}

function printSelectEquip()
{

	global $id, $images_path;
  	
  	echo "<table><tr><td>\n";
	echo " <select name='selectTeam' size='10' style='font-size:12px;width:10em'>\n";
	
	$resu=sqlQuery("select id, sigle from equip");
	while($table=mysql_fetch_array($resu))
	{
		// Team list (always the same)
		echo " <option value='$table[0],$table[1]'>$table[1]</option>\n";
	}
	echo " </select></td><td>\n";
	echo " <input type='button' value=' Add >> ' style='font-size:10px;' onClick='selectTeamFromList();'/>\n";
	echo "</td><td>\n";
	echo "<select multiple name='teamSelect' size='10' style='font-size:12px;width:10em;'>\n";
	if (!empty($id))	// in case of edition display previous selected teams
	{
	    $aresult=sqlQuery("select DISTINCT id, sigle from equip, publiequip WHERE publiequip.idPubli=$id AND publiequip.idEquip=equip.id ORDER BY rank ASC");
	    $prev_team_list = "";
	    while ($aresult && $arow=mysql_fetch_array($aresult)) 
	    {
		  $prev_team_list = $prev_team_list."$arow[id],";
		  echo "   <option value='$arow[id]'>$arow[sigle]</option>\n";
	    }
  	    echo "<input type='hidden' name='previousTeamList' value='".substr($prev_team_list,0,strlen($prev_team_list)-1)."'/>\n";
	}
  	echo " </select>\n";
  	
	echo "</td><td>";
        echo " <div>\n";
        echo "  <a href='javascript:upTeam();'><img src='$images_path/teamUP.png' alt='Get up'></a><br />\n";
        echo "  <a href='javascript:downTeam();'><img style='margin-bottom:12px;margin-top:2px' src='$images_path/teamDOWN.png' alt='Get down'></a><br />\n";
        echo "  <a href='javascript:delTeam();'><img src='$images_path/teamDEL.png' alt='Remove'></a>\n";
        echo " </div>\n";
	echo "</td></tr></table>\n";
}

function printSelectFrom($field)
{
  global $fieldSize;
  global $row;
  $fresult = sqlQuery("SELECT DISTINCT $field FROM publis WHERE $field<>'' ORDER BY $field ASC");
  echo " <select name='selectFill' onChange='fillFromSelect();'>\n";
  echo "  <option value=\"\">Select from this list or enter the name below...</option>\n";
  while ($fresult && $frow=mysql_fetch_array($fresult))
    {
      echo "  <option value=\"$frow[$field]\">".substr($frow[$field],0,60);
      if (strlen($frow[$field])>60) echo "...";
      echo "\n";
    }
  echo " </select>\n";
  echo " <br />";
  echo " <input name=\"$field\" size='60' value=\"$row[$field]\" maxlength=\"$fieldSize[$field]\"/>\n";
}

$lineNb=0;
function line($field)
{
  global $lineNb;
  global $required;
  global $fieldSize;
  global $tip;
  global $entry;
  global $row;
  global $specialType;
  global $hdr, $rap_c, $images_path;
  
  echo " <div class='".(($lineNb==0)?"even":"odd")."'>\n";
  $lineNb = 1-$lineNb;

  // Optionnal or required field
  if (in_array($field, $required))
    {
      $optClass = "required";
      $imgSrc = "$images_path/required.png";
    }
  else
    {
      $optClass = "";
      $imgSrc = "$images_path/empty.png";
    }
  
  if ($field == "optkey") // "key" is a mySQL keyword
    echo "  <span class='label $optClass'>key &nbsp;\n";
  else
  echo "  <span class='label $optClass'>$field &nbsp;\n";

  echo "   <img src='$imgSrc' width='6' height='12' alt=''/>\n";
  if ($field == "authors")
      echo "    <br/><br/><a href='../Authors/author.php'>Create new author</a> &nbsp; &nbsp; </span>\n";
  else
    echo " &nbsp;</span>\n";
  switch ($field)
    {
    case "month":
      printSelectMonth(); break;
    case "range" :
      printSelectPortee(); break;
    case "team" :
      printSelectEquip(); break;
    case "authors" :
      printSelectAuthor(); break;
    case "year" :
      printSelectYear(); break;
    case "project" :
      printSelectProject();break;
    case "booktitle" :
    case "journal" :
      printSelectFrom($field);
      break;
    case "review" :
      printSelectLect(); break;
    case "type" :
      if (!empty($specialType)) {
      	if ($entry == "PhdThesis" and $specialType == "HDR") $tmp_val = $hdr;
        if ($entry == "Misc" and $specialType == "RC") $tmp_val = $rap_c;
        echo "  <input name='$field' size='".min(60, $fieldSize[$field])."' maxlength='$fieldSize[$field]' value=\"$tmp_val\" disabled />\n";
        break;
      }

    default:
      echo "  <input name='$field' size='".min(60, $fieldSize[$field])."' maxlength='$fieldSize[$field]' value=\"$row[$field]\" />\n";
      break;
    }
  if ($field!="team")
    echo $tip[$field]."\n";
  if (($entry=="InBook") && ($field=="chapter"))
    echo "Chapter and/or pages are required\n";
  echo " </div>\n";
}
?>

<script language="javascript">
// Check that all required fields are filled.
function chk()
{
  makeAuthorList();
  makeTeamList();
  d=document.publiForm;
  if (d.authorList.value=="") {
    window.alert("Warning!\nAt least one author must be given."+d.authorList.value);
    return false;
  }
  if (d.teamList.value=="") {
    window.alert("Warning!\nAt least one team must be given."+d.teamList.value);
    return false;
  }
  <?php
  global $required;
  global $entry;
  global $specialType;
  global $hdr, $rap_c;
  foreach($required as $field)
  {
    if ($field=="entry" || $field=="authors" || $field=="chapter" || $field=="pages" || $field=="team" || $field=="range" || $field=="review") continue;
    echo " if (d.$field.value=='') {\n  window.alert('Warning!\\nYou must fill the \"$field\" field.');\n  d.$field.focus();\n  return false;\n }\n";
  }
  if ($entry=="InBook")
    echo " if (d.chapter.value=='' && d.pages.value=='') {\n  window.alert('Warning!\\n\"chapter\" and/or \"pages\" fields must be filled.');\n  d.pages.focus(); return false;\n }\n";
  if (($entry=="InProceedings") || ($entry=="InBook") || ($entry=="Book") || ($entry=="InCollection"))
    echo " if (d.number.value!='' && d.volume.value!='') {\n  window.alert('Warning!\\n\"number\" and \"volume\" fields can not be both filled.');\n  d.number.focus(); return false;\n }\n";
  if (!empty($specialType)) {
      echo " d.type.disabled = false\n";
    if ($entry=="PhdThesis" and $specialType=="HDR") {
      echo " d.type.value='$hdr'\n";
    }
    if ($entry=="Misc" and $specialType=="RC") {
      echo " d.type.value='$rap_c'\n";
    }
  }
  ?>
  return true;
}

function add()
{
  a=chk();
  if (a){
    document.publiForm.act.value="add";
    document.publiForm.submit();
  }
}

function update()
{
  a=chk();
  if (a){
    document.publiForm.act.value="update";
    document.publiForm.submit();
  }
}

function del()
{
  a=window.confirm("Warning!\nAre you sure you want to DEFINITIVELY delete this publication ?");
  if (a){
    document.publiForm.act.value="delete";
    makeAuthorList();
    document.publiForm.submit();
  }
}
</script>


<?php
// Main loop
foreach($fields as $field)
  line($field);

echo " <div class='buttons'>\n";
if (!empty($id))
{
  echo "  <input type='button' value=' Update ' onClick='javascript:update();'/> &nbsp;";
  echo "  <input style='color:red;' type='button' value=' Delete ' onClick='javascript:del();'/> &nbsp;";
  echo "  <input type='button' value=' Cancel ' onClick='window.location.href=\"../intro.html\"';'/>";
}
else
  echo "  <input type='button' value=' Add ' onClick='javascript:add();'/>";

echo " </div>\n";

echo "</form>\n";
echo " </div>\n\n";
if (!empty($id))
  echo "See the publication's <a href='$local_server$public_path/$row[year]/$row[bibTex]' target='parent'>associated web page</a>. &nbsp; &nbsp; ";
echo "<img src='$local_server$images_path/required.png' width='6' height='12' alt=''/> Fields in red must be filled\n\n";
?>

</body>
</html>
