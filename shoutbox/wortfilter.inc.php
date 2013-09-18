<?php

/*

MODUL -> SHOUTBOX -> WORTFILTER

Version 0.1

*/

// CHECK ADMIN =>
if (!isset($_SESSION['login'])) {
	echo "KEINE BERECHTIGUNG!";
	exit;
}

$path = "index.php?seite=modul&amp;modul=".$_GET['modul']."&amp;datei=";
$filePath = $path."wortfilter";
$sendPath = str_replace("&amp;","&",$filePath);
$backPath = $path."admin";
require_once('../../includes/module/shoutbox/fehlerbehandlung.php');

// SAVE NEW
if (isset($_POST['speicherWortfilter'])) {
	if (trim($_POST['wort']) != "" && $_POST['ersetzung'] != "") {
		$wort = addslashes(htmlentities($_POST['wort'], ENT_NOQUOTES));
		$ersetzung = addslashes($_POST['ersetzung']);
		mysql_query("INSERT INTO `modul_shoutbox_swears` (`orig`, `rplace`) VALUES (\"".$wort."\", \"".$ersetzung."\")");
		header ("Location: ".$sendPath."&speichern=okay");
		exit;
	} else {
		header ("Location: ".$sendPath."&speichern=fehler&was=saveNewSwear");
		exit;
	}
}

// SAVE EDIT
if ( isset($_POST['edit'])) {
	if (trim($_POST['edit']) != "" && is_numeric($_POST['edit']) && trim($_POST['wort']) != "" && trim($_POST['ersetzung']) != "") {
		$wort = addslashes(htmlentities($_POST['wort'], ENT_NOQUOTES));
		$ersetzung = addslashes($_POST['ersetzung']);
		mysql_query("UPDATE `modul_shoutbox_swears` SET `orig`= '".$wort."' , `rplace`='".$ersetzung."' WHERE `id`='".$_POST['edit']."'");
		header ("Location: ".$sendPath."&speichern=okay");
		exit;
	} else {
		header ("Location: ".$sendPath."&speichern=fehler&was=editSwear");
		exit;
	}
}

// DELETE
if ( isset($_GET['delete'])) {
	if(trim($_GET['delete']) != "" && is_numeric($_GET['delete']) ) {
		mysql_query("DELETE FROM `modul_shoutbox_swears` WHERE `id`='".$_GET['delete']."'");
		header ("Location: ".$sendPath."&speichern=okay");
		exit;
	} else {
		header ("Location: ".$sendPath."&speichern=fehler&was=deleteSwear");
		exit;
	}
}

fehlerBehandung();

echo "<h1>Wortfilter</h1>";
echo "<div class=\"box\">";
?>

<h3>Neuen Wortfilter erfassen</h3>
<form method="post" action="<?php echo $filePath ?>">
<table cellspacing="0" cellpadding="0" summary="text">
  <tr>
   <td width="150"><input type="text" name="wort" value="" class="formular" style="width: 150px;" /></td>
   <td width="100" align="center">ersetzen durch</td>
   <td width="150"><input type="text" name="ersetzung" value="" class="formular" style="width: 150px;" /></td>
   <td width="35" align="center"><input value='' type='submit' name="speicherWortfilter" style='background:url(../data/images/save.png) no-repeat; border:0; width:20px;'/></td>
  </tr>
</table>
</form>

<br />

<?
// SHOW
$swears = mysql_query ("SELECT * FROM modul_shoutbox_swears ORDER by id DESC");
$anzahl = mysql_numrows($swears);

if ($anzahl < 1) {
	echo "<h3>Bisher noch kein Eintrag vorhanden.</h3>";
} else {
	
	echo "<h3>Bisherige Wortfilter</h3>";
	
	if (isset($_GET['edit'])) {
		echo "<form action='$filePath' method='post'>";
		echo "	<input name='edit' value='".$_GET['edit']."' type='hidden' />";
	}

	echo "<table width='100%'>";
	
	// Größe
	echo "<colgroup>";
	echo '	<col width="45%" />';
	echo '	<col width="45%" />';
	echo '	<col width="5%" />';
	echo '	<col width="5%" />';
	echo '</colgroup>';
	
	while ($data = mysql_fetch_assoc($swears)) {
		$farbcode = ($i & 1)?"#F9F8F6":"#fff";	
		
		
		echo "<tr style='background:$farbcode;'>";
		
		// edit view
		if (isset($_GET['edit']) && $_GET['edit'] == $data['id']) {
			echo "	<td><input style='width:180px;' name='wort' class='formular' type='text' value='".stripcslashes($data['orig'])."' /></td>\n";
			echo "	<td><input style='width:180px;' name='ersetzung' class='formular' type='text' value='".stripcslashes($data['rplace'])."' /></td>\n";
			echo "	<td><input title='Speichern' input value='' type='submit' style='background:url(../data/images/save.png) no-repeat; border:0; width:20px;'/></td>\n";
			echo "	<td><a title='Abbrechen' href='".$filePath."'><img src='../../includes/module/shoutbox/img/cross.gif' border='0' alt='cancel' /></a></td>";
		
		// normal  view
		} else  {
			echo "	<td>".stripcslashes($data['orig'])."</td>";
			echo "	<td>".stripcslashes(htmlentities($data['rplace'], ENT_NOQUOTES))."</td>";
			echo "	<td><a title='Berabeiten' href='$filePath&amp;edit=".$data['id']."'><img src='../data/images/edit.png' border='0' alt='edit' /></a></td>";
			echo "	<td><a title='L&ouml;schen' onclick='return swearDelete()' href='$filePath&amp;delete=".$data['id']."'><img src='../data/images/delete.png' border='0' alt='delete' /></a></td>";
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