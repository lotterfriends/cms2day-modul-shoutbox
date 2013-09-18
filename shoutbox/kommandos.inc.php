<?php

/*

MODUL -> SHOUTBOX -> KOMMANDOS

Version 0.1

*/


// CHECK ADMIN =>
if (!isset($_SESSION['login'])) {
	echo "KEINE BERECHTIGUNG!";
	exit;
}

require_once('../../includes/module/shoutbox/fehlerbehandlung.php');
$path = "index.php?seite=modul&amp;modul=".$_GET['modul']."&amp;datei=";
$filePath = $path."kommandos";
$sendPath = str_replace("&amp;","&",$filePath);
$backPath = $path."admin";
$detailPath = $path."kommando_detail";
$sendDetailPath = str_replace("&amp;","&",$path."kommando_detail");

// EDIT AND CREATE COMMAND
if (isset($_POST['kommandoErstellen']) || isset($_POST['kommandoBearbeiten'])) {
	$kommando = trim(addslashes(htmlentities($_POST['kommando'], ENT_NOQUOTES)));
	$ersetzung = trim(addslashes($_POST['ersetzung']));
	$beschreibung = trim(addslashes(htmlentities($_POST['beschreibung'], ENT_NOQUOTES)));
	$anwendung = trim(addslashes(htmlentities($_POST['anwendung'], ENT_NOQUOTES)));
}

// CREATE NEW COMMAND
if (isset($_POST['kommandoErstellen'])) {
	if ($kommando != "" && $ersetzung != "" && $beschreibung != "" && $anwendung != "" && substr($kommando,0,1) == "/"  && substr($anwendung,0,1) == "/") {
		mysql_query("INSERT INTO `modul_shoutbox_commands` (`command`,`r_command`,`describ`,`use` ) 
			VALUES (\"".$kommando."\",
					\"".$ersetzung."\",
					\"".$beschreibung."\",
					\"".$anwendung."\"
					)");
		header ("Location: ".$sendPath."&speichern=okay");
		exit;
	} else {
		header ("Location: ".$sendDetailPath
			."&speichern=fehler&was=newCommand&command="
			.urlencode($kommando)."&r_command="
			.urlencode($ersetzung)."&describ="
			.urlencode($beschreibung)."&use="
			.urlencode($anwendung));
		exit;
	}
}

// EDIT COMMAND
if (isset($_POST['kommandoBearbeiten'])) {
	if ($kommando != "" && $ersetzung != "" && $beschreibung != "" && $anwendung != "" && substr($kommando,0,1) == "/"  && substr($anwendung,0,1) == "/" ) {
		mysql_query("UPDATE `modul_shoutbox_commands` SET 
			`command`='".$kommando."',
			`r_command`='".$ersetzung."',
			`describ`='".$beschreibung."',
			`use`='".$anwendung."'
			WHERE `id`='".$_POST['id']."'");
		header ("Location: ".$sendPath."&speichern=okay");
		exit;
	} else {
		header ("Location: ".$sendDetailPath."&was=editCommand&speichern=fehler&edit=".$_POST['id']);
		exit;
	}
}



// DELETE
if ( isset($_GET['delete'])) {
	if(trim($_GET['delete']) != "" && is_numeric($_GET['delete']) ) {
		mysql_query("DELETE FROM `modul_shoutbox_commands` WHERE `id`='".$_GET['delete']."'");
		header ("Location: ".$sendPath."&speichern=okay");
		exit;
	} else {
		header ("Location: ".$sendPath."&speichern=fehler&was=deleteCommand");
		exit;
	}
}

fehlerBehandung();

echo "<h1>Kommandos</h1>";
echo "<div class=\"box\">";

// SHOW
$kommandos = mysql_query ("SELECT * FROM `modul_shoutbox_commands` ORDER by id DESC");
$anzahl = mysql_numrows($kommandos);

echo "<span style='float:right;'><a title='neues Kommando erstellen' href='$detailPath'><img src='../data/images/seite.gif' alt='neues Kommando erstellen'/></a></span>";

if ($anzahl < 1) {
	echo "<h3>Bisher noch kein Eintrag vorhanden.</h3>";
} else {
	
	echo "<h3>Bisherige Kommandos</h3>";
	echo "<table width='100%'>";
	
	// Größe
	echo "<colgroup>";
	echo '	<col width="90%" />';
	echo '	<col width="5%" />';
	echo '	<col width="5%" />';
	echo '</colgroup>';
	
	while ($data = mysql_fetch_assoc($kommandos)) {
		$farbcode = ($i & 1)?"#F9F8F6":"#fff";	
		
		echo "<tr style='background:$farbcode;'>";
		echo "	<td>".stripcslashes($data['command'])."</td>";
		echo "	<td><a title='Bearbeiten' href='$detailPath&amp;edit=".$data['id']."'><img src='../data/images/edit.png' border='0' alt='edit' /></a></td>";
		echo "	<td><a title='L&ouml;schen' href='$filePath&amp;delete=".$data['id']."' onclick='return commandDelete()'><img src='../data/images/delete.png' border='0' alt='delete' /></a></td>";
		echo "</tr>";
		
		$i++;	
	}
	echo "</table>";

	echo "<br />";
}

echo '<p><a href="'.$backPath.'" class="button">Zur&uuml;ck</a></p>';
echo "</div>"


?>