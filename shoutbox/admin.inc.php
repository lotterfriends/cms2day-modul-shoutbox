<?php

/*

MODUL -> SHOUTBOX -> ADMIN

Version 0.1

*/

global $settings;
global $optionen;

// CHECK ADMIN =>

if (!isset($_SESSION['login'])) {
	echo "KEINE BERECHTIGUNG!";
	exit;
}

// CHECK FOR UPDATE

$eigenschaften = mysql_fetch_assoc(mysql_query("SELECT * FROM module WHERE code='%SHOUTBOX%'"));
if ($eigenschaften['version'] < "0.1") {
	header ("Location: index.php?seite=modul&modul=".$_GET['modul']."&datei=update");
	exit;
}

// ÜBERSICHT 
?>

<h1>&Uuml;bersicht</h1>
<div class="box">
	<ul>
		<li>&raquo; <a href='index.php?seite=modul&amp;modul=<?php echo $_GET['modul'] ?>&amp;datei=shouts'>Shouts</a></li>
		<li>&raquo; <a href='index.php?seite=modul&amp;modul=<?php echo $_GET['modul'] ?>&amp;datei=wortfilter'>Wortfilter</a></li>
		<li>&raquo; <a href='index.php?seite=modul&amp;modul=<?php echo $_GET['modul'] ?>&amp;datei=kommandos'>Kommandos</a></li>
	</ul>
	<hr />
	<p align="right">&raquo;&nbsp;<a href="index.php?seite=modul&amp;modul=<?php echo $_GET['modul'] ?>&amp;datei=settings">Einstellungen</a></p>
</div>
