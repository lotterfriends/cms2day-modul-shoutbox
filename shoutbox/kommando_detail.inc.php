<?php

// CHECK ADMIN =>
if (!isset($_SESSION['login'])) {
	echo "KEINE BERECHTIGUNG!";
	exit;
}

require_once('../../includes/module/shoutbox/fehlerbehandlung.php');
$path = "index.php?seite=modul&amp;modul=".$_GET['modul']."&amp;datei=";
$backPath = $path."kommandos";

$kommando = "/";
$ersetzung = ""; 
$beschreibung = "";
$benutzung = "/";
$aktion = "kommandoErstellen";
$editMode = false;

if ( isset($_GET['edit']) && trim($_GET['edit']) != "" && is_numeric($_GET['edit'])) {
	$editMode = true;
	$id = htmlentities($_GET['edit'], ENT_NOQUOTES);
	$kommandos = mysql_query("SELECT * FROM `modul_shoutbox_commands` WHERE `id`='".$id."'");
	while ($data = mysql_fetch_assoc($kommandos)) {	
		$kommando = stripcslashes($data['command']);
		$ersetzung = stripcslashes($data['r_command']); 
		$beschreibung = stripcslashes($data['describ']);
		$benutzung = stripcslashes($data['use']);
		$aktion = "kommandoBearbeiten";
	}
}

if ( isset($_GET['speichern']) && isset($_GET['was']) && $_GET['was'] == "newCommand") {
	$kommando = urldecode($_GET['command']);
	$ersetzung = urldecode($_GET['r_command']);
	$beschreibung = urldecode($_GET['describ']);
	$benutzung = urldecode($_GET['use']);
}

fehlerBehandung();


?>


<h1>Kommandos</h1>
<div class="box">
	<form method="post" action="<?php echo $backPath ?>">
		<?php if ($editMode) echo "<input type='hidden' name='id' value='$id' />"; ?>
		<h2>Kommando</h2>
		<h3>Das Kommando, muss immer mit einerm Slash ("/") beginnen</h3>
		<p><input type="text" maxlength="100" name="kommando" value="<?php echo $kommando ?>" class="formular" /></p>
		
		<h2>Ersetzen mit</h2>
		<h3>Womit sollen die Kommandos ersetzt werden? Der Parameter nach dem Kommando kann mit %s angesprochen werden.</h3>
		<textarea cols="0" rows="0" class="textarea" name="ersetzung" style="margin-bottom: 0px;"><?php echo $ersetzung ?></textarea>
		<div class="resizer"></div>
		
		<h2>Beschreibung</h2>
		<h3>Beschreibung für das Kommando</h3>
		<textarea cols="0" rows="0" class="textarea" name="beschreibung" style="margin-bottom: 0px;"><?php echo $beschreibung ?></textarea>
		<div class="resizer"></div>
		
		<h2>Anwendung</h2>
		<h3>Ein Anwendungsbeispiel</h3>
		<p><input type="text" maxlength="100" name="anwendung" value="<?php echo $benutzung ?>" class="formular" /></p>
		
		<hr />
	
		<table width="100%" cellspacing="0" cellpadding="0" summary="text">
			<tr>
				<td width="50%" align="left"><p><a href="<?php echo $backPath ?>" class="button">Zur&uuml;ck</a></p></td>
				<td width="50%" align="right"><p><input name="<? echo $aktion ?>" type="submit" value="Speichern" class="button" /></p></td>
			</tr>
		</table>
		
		<br />
	</form>
	<br />
</div>
