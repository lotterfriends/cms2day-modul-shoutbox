<?php


function fehlerBehandung () {
	if (isset($_GET['speichern']) && $_GET['speichern'] == "fehler" && isset($_GET['was']) && trim($_GET['was']) != "") {
		$buffer = '<div class="box fehler">';
		// Welcher Fehler ist aufgetreten
		$was = trim($_GET['was']);
		if ($was == "anzahl_anzeige")   $buffer .= 'Bei "Angezeigte Shouts" wurde keine oder eine zu gro&szlig;e Zahl eingegeben';
		if ($was == "anzahl_speichern") $buffer .= 'Bei "Gespeicherte Shouts" wurde keine oder eine zu gro&szlig;e Zahl eingegeben';
		if ($was == "flood_sperre")     $buffer .= 'Bei "Flood Sperre" wurde keine oder eine zu gro&szlig;e Zahl eingegeben';
		if ($was == "aktiv")            $buffer .= 'Bei "Shoutbox Aktiviert" wurde ein falscher Wert eingetragen';
		if ($was == "deleteShout")      $buffer .= 'Beim L&ouml;schen des Shouts ist ein Fehler aufgetreten';
		if ($was == "editShout")        $buffer .= 'Beim Editieren des Shouts ist ein Fehler aufgetreten';
		if ($was == "saveNewSwear")     $buffer .= 'Beim Speichern des neuen Wortfilters ist ein Fehler aufgetreten';
		if ($was == "editSwear")        $buffer .= 'Beim Editieren des Wortfilters ist ein Fehler aufgetreten';
		if ($was == "deleteSwear")      $buffer .= 'Beim L&ouml;schen des Wortfilters ist ein Fehler aufgetreten';
		if ($was == "deleteCommand")    $buffer .= 'Beim L&ouml;schen des Kommandos ist ein Fehler aufgetreten';
		if ($was == "newCommand")		$buffer .= 'Beim Erstellen des Kommandos ist ein Fehler aufgetreten';
		if ($was == "editCommand")      $buffer .= 'Beim Editieren des Kommandos ist ein Fehler aufgetreten';
		$buffer .= '</div>';
		echo $buffer;
	}
}

?>