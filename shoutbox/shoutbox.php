<?php

/**
*
* Zugang für Requests 
* @param action (Pflicht) -> Gibt an was gemacht werden soll
* @param name -> Autor eines neuen Shouts
* @param message -> das Shouts
* @return json / text
*
*/

if (isset($_GET['action'])) $action = htmlentities($_GET['action'], ENT_NOQUOTES);
else exit();

// MYSQL Daten und json Klasse einbinden
require_once('../../mysql.inc.php');
require_once('json.php');

// Zu Datenbank verbinden
@mysql_connect($host,$benutzer,$passwort) or die ("Fehler: Verbindung zur MySQL Server nicht möglich!");
@mysql_select_db($dbname) or die ("Fehler: Verbindung zur Datenbank nicht möglich!");

// Einstellungen Laden
$modul_settings = mysql_fetch_assoc(mysql_query("SELECT * FROM modul_shoutbox_settings"));

// Cachen verbieten (für IE und Opera)
header('Cache-Control: must-revalidate');
header('Expires: Thu, 1 Jan 1970 0:00:00 GMT');

/**
*
* get Last ID
* gibt die letzte Shout ID aus
*
*/ 
if ($action == "getLastID") {
	$result = mysql_query("SELECT id FROM `modul_shoutbox` ORDER BY id DESC LIMIT 1");
	$get = mysql_fetch_assoc($result);
	exit(trim($get['id']));
}

/**
*
* Show all Shouts
*
*/ 
if ($action == "show") {		
	$shouts = mysql_query ("SELECT `id`, `name`, `text`,`datum` FROM modul_shoutbox ORDER by id DESC LIMIT ".$modul_settings['anzahl_anzeige']);
	
	$check = mysql_num_rows($shouts);
	if ($check < 1) exit("no shout");
	
	$i = 0;
	while ($data = mysql_fetch_assoc($shouts)) {
 		$data['name']  = stripcslashes($data['name']);
		$data['text']  = smiliesUmwandeln(kommand(swear(stripcslashes($data['text']))));
		$data['datum'] = date("H:i:s",$data['datum']);
		$array[$i] = $data;
		$i++;
	}	
	
	$json = new Services_JSON();
	exit($json->encode($array));
}


/**
*
* Save Shout
*
*/
if ($action == "save") {

	// flood control
	if ($modul_settings['flood_sperre'] > 0) {
		setcookie ("cms2day_shoutbox", "disabled", time()+$modul_settings['flood_sperre'], "/", "", 0);
		if($_COOKIE["cms2day_shoutbox"]) exit("disabled");
	}
	
	$message=trim($_GET['message']);
	$name = trim($_GET['name']);
	if ($message=="" || $name =="") exit();			
	
	$name = addslashes(htmlentities($name, ENT_NOQUOTES));
	$message = addslashes(htmlentities($message, ENT_NOQUOTES));
	$datum = time();
	$ip = $_SERVER['REMOTE_ADDR'];
	
	// Speichern
	mysql_query ("INSERT INTO `modul_shoutbox` SET `name`='".$name."',`text`='".$message."',`datum`='".$datum."',`ip`='".$ip."'");
	
	// Letzten Löschen
	$shouts = mysql_query ("SELECT id FROM modul_shoutbox ORDER by id DESC");
	$check = mysql_num_rows($shouts);
	if ($check > $modul_settings['anzahl_speichern']) {
		$loeschen = $check-$modul_settings['anzahl_speichern'];
		mysql_query ("DELETE FROM `modul_shoutbox` ORDER BY `datum` LIMIT $loeschen"); 
	}
	
	// letzte ID holen, ohne neue Anfrage zu machen
	$data = mysql_fetch_assoc($shouts);
	$id = $data['id'];
	
	// Daten für json Klasse aufbereiten
	$data['id']    = $id;
	$data['name']  = stripcslashes($name);
	$data['text']  = smiliesUmwandeln(kommand(swear(stripcslashes($message))));
	$data['datum'] = date("H:i:s",$datum);	
	$array[0] = $data;
	
	$json = new Services_JSON();
	exit($json->encode($array));
}

/**
*
* Show Smilies
*
*/
if ($action == "showSmilies") {
	include "smilies.php";
	$smilies = array_unique($smilies);
	
	// Smiliearry für json aufbereiten
	$i = 0;
	while (list($bezeichnung, $datei) = each($smilies)) {
		$data['bez']   = $bezeichnung;
		$data['datei'] = $datei;
		$array[$i] = $data;
		$i++;
	}
	
	$json = new Services_JSON();
	exit($json->encode($array));
}


/**
*
* Show FAQ 
*
*/
if ($action == "showFAQ") {
	$result = mysql_query("SELECT * FROM `modul_shoutbox_commands` ORDER BY `command` ASC");
	if(mysql_num_rows($result)<1) exit("no entry"); 
	$i = 0;
	while ($data = mysql_fetch_assoc($result)) {
		$newData['command']   = stripcslashes($data['command']);
		$newData['describ']   = stripcslashes($data['describ']);
		$newData['use']       = stripcslashes(str_replace("\n","<br />",$data['use']));
		$array[$i] = $newData;
		$i++;
	}
	
	$json = new Services_JSON();
	exit($json->encode($array));
}



/**
*
* Wortfilter
* @param <string>  text
* @return <string> text
*/
function swear($text){	
	$result = mysql_query ("SELECT `orig`,`rplace` FROM `modul_shoutbox_swears`");
	while($row = mysql_fetch_assoc($result)){
		$swears[$row["orig"]] = $row["rplace"];
	}
	if($swears){
		while(list($orig,$rplace) = each($swears)){
			$text = str_replace($orig,$rplace,$text);
		}
		reset($swears);
	}
	return $text;
}

/**
*
* Kommandos umsetzen
* @param <string>  text
* @return <string> text
*
*/
function kommand($message){	
	$result = mysql_query ("SELECT `command`,`r_command` FROM `modul_shoutbox_commands`");
	while($data = mysql_fetch_assoc($result)){
		if($data['command']==substr(trim($message),0,strlen($data['command']))){
			$message=sprintf(stripslashes($data['r_command']),substr($message,strlen($data['command']),strlen($message)));
		}
	}
	return $message;
}

/**
*
* Smilies in Text ersetzen
* @param <string>  text mit Smiliecodes
* @return <string> text mit Smilies als Bilder
*
*/
function smiliesUmwandeln ($smilieText) {
	include "smilies.php";
	$smilieOrdner = dirname($_SERVER['PHP_SELF'])."/img/smilies/";
	
	while (list($smiley, $bild) = each($smilies)) {
		$smilieText = str_replace($smiley, "<img class='smilie' src='$smilieOrdner$bild' alt='Smilie' />", $smilieText);
	}
	return $smilieText;
}






?>