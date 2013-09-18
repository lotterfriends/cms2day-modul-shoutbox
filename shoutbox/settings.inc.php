<?php

/*

MODUL -> SHOUTBOX -> SETTINGS

Version 0.1

*/

// CHECK ADMIN =>

if (!isset($_SESSION['login'])) {

	echo "KEINE BERECHTIGUNG!";
	exit;
	
}

$filePath = "index.php?seite=modul&modul=".$_GET['modul']."&datei=settings";
$backPath = "index.php?seite=modul&modul=".$_GET['modul']."&amp;datei=admin";
require_once('../../includes/module/shoutbox/fehlerbehandlung.php');


// EINSTELLUNGEN

$settings = mysql_fetch_assoc(mysql_query("SELECT * FROM modul_shoutbox_settings"));

// SPEICHERN

if (isset($_GET['action']) && $_GET['action'] == "speichern") {

	// Fehler abfangen
	if (trim($_POST['anzahl_anzeige']) == "" || !is_numeric($_POST['anzahl_anzeige']) || $_POST['anzahl_anzeige'] > 999) {
		header ("Location: ".$filePath."&speichern=fehler&was=anzahl_anzeige");
		exit;
	}
	if (trim($_POST['anzahl_speichern']) == "" || !is_numeric($_POST['anzahl_speichern']) || $_POST['anzahl_speichern'] > 999) {
		header ("Location: ".$filePath."&speichern=fehler&was=anzahl_speichern");
		exit;
	}
	if (trim($_POST['flood_sperre']) == "" || !is_numeric($_POST['flood_sperre']) || $_POST['flood_sperre'] > 9999) {
		header ("Location: ".$filePath."&speichern=fehler&was=flood_sperre");
		exit;
	}
	if (trim($_POST['aktiv']) == "" || !is_numeric($_POST['aktiv']) || ($_POST['aktiv'] != 0 && $_POST['aktiv'] != 1)) {
		header ("Location: ".$filePath."&speichern=fehler&was=aktiv");
		exit;
	}
		
	mysql_query("UPDATE modul_shoutbox_settings SET anzahl_anzeige='".$_POST['anzahl_anzeige']."', anzahl_speichern='".$_POST['anzahl_speichern']."', flood_sperre='".$_POST['flood_sperre']."' , aktiv='".$_POST['aktiv']."'");
	header ("Location: ".$filePath."&speichern=okay");
	exit;
}

fehlerBehandung();

// AUSGABE

?>


<h1>Einstellungen</h1>


<div class="box">


<form name="settings" method="post" action="index.php?seite=modul&amp;modul=<?php echo "".$_GET['modul'].""; ?>&amp;datei=settings&amp;action=speichern">

<h2>Angezeigte Shouts</h2>
<h3>Wie viele Shouts sollen bei der Initialisierung der Shoutbox geleaden werden?</h3>
<p><input type="text" maxlength="3" name="anzahl_anzeige" value="<?php echo $settings['anzahl_anzeige']; ?>" class="formular" /></p>
<br />

<h2>Gespeicherte Shouts</h2>
<h3>Wie viele Shouts sollen in der Datenbank gespeichert werden?</h3>
<p><input type="text" maxlength="3" name="anzahl_speichern" value="<?php echo $settings['anzahl_speichern']; ?>" class="formular" /></p>
<br />

<h2>Flood Sperre</h2>
<h3>Wie viele Sekunden muss man warten bis man wieder eine Nachricht abschicken kann? "0" bedeutet deaktiviert.</h3>
<p><input type="text" maxlength="4" name="flood_sperre" value="<?php echo $settings['flood_sperre']; ?>" class="formular" /></p>
<br />

<h2>Shoutbox Aktiviert</h2>
<h3>Sollen die Shoutbox ein- oder ausgeschlatet sein?</h3>

<table cellspacing="0" cellpadding="0" summary="text">

  <tr>
   <td width="25"><input type="radio" name="aktiv" value="0" <?php if ($settings['aktiv'] == "0") { echo 'checked="checked"'; } ?> /></td>
   <td>Ja (Standard)</td>
  </tr>

  <tr>
   <td width="25"><input type="radio" name="aktiv" value="1" <?php if ($settings['aktiv'] == "1") { echo 'checked="checked"'; } ?> /></td>
   <td>Nein</td>
  </tr>

</table>

<hr />

<table width="100%" cellspacing="0" cellpadding="0" summary="text">
<tr>
<td width="50%" align="left"><p><a href="<? echo $backPath ?>" class="button">Zur&uuml;ck</a></p></td>
<td width="50%" align="right"><p><input type="submit" value="Speichern" class="button" /></p></td>
</tr>
</table>

</form>

</div>