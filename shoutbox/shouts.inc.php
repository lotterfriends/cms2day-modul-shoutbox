<?php

/*

MODUL -> SHOUTBOX -> SHOUTS

Version 0.1

*/

// CHECK ADMIN =>
if (!isset($_SESSION['login'])) {
	echo "KEINE BERECHTIGUNG!";
	exit;
}

$path = "index.php?seite=modul&amp;modul=".$_GET['modul']."&amp;datei=";
$filePath = $path."shouts";
$sendPath = str_replace("&amp;","&",$filePath);
$backPath = $path."admin";
require_once('../../includes/module/shoutbox/fehlerbehandlung.php');

// DELETE
if ( isset($_GET['delete'])) {
	if (trim($_GET['delete']) != "" && is_numeric($_GET['delete'])) {
		mysql_query("DELETE FROM `modul_shoutbox` WHERE `id`='".$_GET['delete']."'");
		header ("Location: ".$sendPath."&speichern=okay");
		exit;
	} else {
		header ("Location: ".$sendPath."&speichern=fehler&was=deleteShout");
		exit;
	}
} 

// SAVE EDIT
if ( isset($_POST['edit'] )) {
	if (trim($_POST['edit']) != "" && is_numeric($_POST['edit']) && trim($_POST['text']) != "" ) {
		$text = addslashes(htmlentities($_POST['text'], ENT_NOQUOTES));
		mysql_query("UPDATE `modul_shoutbox` SET `text`= '".$text."' WHERE `id`='".$_POST['edit']."'");
		header ("Location: ".$sendPath."&speichern=okay");
		exit;
	} else {
		header ("Location: ".$sendPath."&speichern=fehler&was=deleteShout");
		exit;
	}
}

// SHOW
$shouts = mysql_query ("SELECT * FROM modul_shoutbox ORDER by id DESC");
$anzahl = mysql_numrows($shouts);

fehlerBehandung ();

echo "<h1>Shouts</h1>";
echo "<div class=\"box\">";

if ($anzahl < 1) {
	echo "<h3>Bisher noch kein Eintrag vorhanden.</h3>";
} else {
	
	if (isset($_GET['edit'])) {
		echo "<form action='$filePath' method='post'>";
		echo "	<input name='edit' value='".$_GET['edit']."' type='hidden' />";
	}

	echo "<table id='shouts'>";
	
	// Größe
	echo "<colgroup>";
	echo '	<col width="10%" />';
	echo '	<col width="20%" />';
	echo '	<col width="60%" />';
	echo '	<col width="5%" />';
	echo '	<col width="5%" />';
	echo '</colgroup>';
	
	// Überschrift
	echo '<tr>';
	echo '	<th>Zeit</th>';
	echo '	<th>Autor</th>';
	echo '	<th>Shout</th>';
	echo '	<th></th>';
	echo '	<th><a title="Aktualisieren" href="'.$filePath.'"><img style="" src="../data/images/load.png" alt="Aktualisieren" border="0" /></a></th>';
	echo '</tr>';
	
	// Inhalt
	while ($data = mysql_fetch_assoc($shouts)) {
		$farbcode = ($i & 1)?"#F9F8F6":"#fff";	
		
		
		echo "<tr style='background:$farbcode;'>";
		echo "	<td title='".makeDatum($data['datum'])."'>".date("H:i:s",$data['datum'])."</td>";
		echo "	<td align='center' title='".$data['ip']."'>".$data['name']."</td>";
		
		// edit view
		if (isset($_GET['edit']) && $_GET['edit'] == $data['id']) {
			echo "	<td><input id='input".$_GET['edit']."' name='text' class='formular' type='text' value='".stripcslashes($data['text'])."' /></td>\n";
			echo "	<td><input title='Speichern' value='' type='submit' style='background:url(../data/images/save.png) no-repeat; border:0; width:20px;'/></td>\n";
			echo "	<td><a title='Abbrechen' href='".$filePath."'><img src='../../includes/module/shoutbox/img/cross.gif' border='0' alt='cancel' /></a></td>";
		
		// normal  view
		} else  {
			echo "	<td>".stripcslashes($data['text'])."</td>";
			echo "	<td><a title='Bearbeiten' href='$filePath&amp;edit=".$data['id']."#input".$data['id']."'><img src='../data/images/edit.png' border='0' alt='edit' /></a></td>";
			echo "	<td><a onclick='return shoutDelete()' title='L&ouml;schen' href='$filePath&amp;delete=".$data['id']."'><img src='../data/images/delete.png' border='0' alt='delete' /></a></td>";
		}
		echo "</tr>";
		
		
		
		$i++;	
	}
	echo "</table>";
	
	if (isset($_GET['edit'])) echo "</form>";

	echo "<br />";
}
echo '<p><a href="'.$backPath.'" class="button">Zur&uuml;ck</a></p>';
echo "</div>"



?>