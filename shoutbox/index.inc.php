<?php

global $optionen;

echo "<div id=\"modul\">\n";
echo "<div id=\"modul_shoutbox\">\n\n";

// MODUL SETTINGS
$modul_settings = mysql_fetch_assoc(mysql_query("SELECT * FROM modul_shoutbox_settings"));
	
// HEADLINE =>
if ($optionen['page_headline'] == "1") echo "<h1>Shoutbox</h1>\n\n";

if ($modul_settings['aktiv'] == "0") {
?>
<div id="shoutbox">
	<form action="#" onsubmit="nachrichtSenden(); return false;">
		<input type="text" value="Name" name="shoutboxName"  size="17" maxlength="20" />
		<input type="text" value="Nachricht" name="shoutboxNachricht"  size="53" maxlength="100" />
		<input type="button" value="senden" name="shoutboxSubmitButton" onkeydown="" onclick="nachrichtSenden()"/>
		<input type="button" value="FAQ" id="shoutboxShowFAQ" name="shoutboxShowFAQ" onclick="showFAQ()" />
		<input type="button" value="&nbsp;" id="shoutboxSmilies" name="shoutboxSmilies" onclick="showSmilies(true)"/>
	</form>
	<div id="shoutboxShouts">
		<noscript>JavaScript muss aktiviert sein, um die Shoutbox zu nutzen.</noscript>
	</div>
	<div id="FAQBox" class="inaktiv"></div>
	<div id="smilieBox" class="inaktiv"></div>
</div>
<?php
}

echo "</div>";
echo "</div>";

?>