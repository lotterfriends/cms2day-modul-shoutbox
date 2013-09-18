CREATE TABLE `modul_shoutbox` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `datum` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `modul_shoutbox_commands` (
  `id` int(11) NOT NULL auto_increment,
  `command` varchar(255) NOT NULL default '' UNIQUE,
  `r_command` text NOT NULL,
  `describ` varchar(255) NOT NULL default '',
  `use` tinytext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

CREATE TABLE `modul_shoutbox_swears` (
  `id` int(11) NOT NULL auto_increment,
  `orig` varchar(255) NOT NULL default '' UNIQUE,
  `rplace` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

CREATE TABLE IF NOT EXISTS `modul_shoutbox_settings` (
  `anzahl_anzeige` int(3) NOT NULL DEFAULT '0',
  `anzahl_speichern` int(3) NOT NULL DEFAULT '0',
  `flood_sperre` int(4) NOT NULL DEFAULT '0',
  `aktiv` int(1) NOT NULL DEFAULT '0'
) TYPE=MyISAM;


INSERT INTO `modul_shoutbox_swears` (`orig`, `rplace`) VALUES ("1337","<b>1337</b>");
INSERT INTO `modul_shoutbox_commands` (`command`, `r_command`, `describ`, `use`) VALUES ("/link", "<a href=\"%s\" target=\"_blank\">Link</a>", "Mit Diesem Kommando kann man Links in der Shoutbox posten", "/link http://www.google.de");
INSERT INTO `modul_shoutbox_settings` (`anzahl_anzeige`, `anzahl_speichern`, `flood_sperre`, `aktiv`) VALUES (30, 50, 0, 0);


SET FOREIGN_KEY_CHECKS=1;