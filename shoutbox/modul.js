
function shoutDelete() { return confirm("Diesen Shout wirklich löschen?"); }
function commandDelete() { return confirm("Diesen Kommando wirklich löschen?"); }
function swearDelete() { return confirm("Diese Wortersetzung wirklich löschen?"); }

// Nur laden wenn auch benötigt
if (document.getElementsByTagName("shoutbox")) {

	function nachladen(name){
		var tag;
		if (name.indexOf(".js") > 0) {
			tag = document.createElement("script");
			tag.src=name;
			tag.type = "text/javascript";
		} else if (name.indexOf(".css") > 0) {
			tag = document.createElement("link");
			tag.rel = "stylesheet";
			tag.type = "text/css";
			tag.href = name;
		}  else return;
		document.getElementsByTagName("head")[0].appendChild(tag);
	}

	nachladen("./includes/module/shoutbox/ajax.js");
	nachladen("./includes/module/shoutbox/shoutbox.js");

}

