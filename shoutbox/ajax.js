/*
* (C) Andr� Tarnowsky - lotterfriends.net
* Möglicher Aufruf
* - sendeAnfrage("get","datei.json",function (r) {}); 
* - sendeAnfrage("POST","ServletZwei",function (r) {alert(r)}),"text="+this.innerHTML);
* @param String "get oder post"
* @param String "Datei die Angefragt wird"
* @param function "JS Function die die Rückgabe verarbeiten soll bekommt die Rückgabe"
* @param String o. Array "Ein oder mehrere Key - Value Paare"
* @access  public
*/
function sendeAnfrage(art,ziel,verarbeiter,parameter) {
	var http = null;
	try {
		// Mozilla, Opera, Safari sowie Internet Explorer (ab v7)
		http = new XMLHttpRequest();
	} catch(e) {
		try {
			// MS Internet Explorer (ab v6)
			http  = new ActiveXObject("Microsoft.XMLHTTP");
		} catch(e) {
			try {
				// MS Internet Explorer (ab v5)
				http  = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				http  = null;
			}
		}
	}
	
	if (!http) return false;
	zeigeLadegrafik(true)
	art = art.toUpperCase();
	http.open(art,ziel,true);
	if (art == "POST") http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
          zeigeLadegrafik(false);
          verarbeiter(http.responseText);
		}
	}	
	var uebertraeger = "";
	if(parameter!==undefined) {
		if (parameter.constructor == Array) {
			for (var i=0; i<parameter.length; i++) uebertraeger += parameter[i] + "&";
		} else uebertraeger = parameter;
	}
	http.send(uebertraeger);
}

// Ladegrafik anzeigen
// Auf der Seite muss eine Grafik mit der ID "load" sein,
function zeigeLadegrafik(zeigen) {
	var bild = document.getElementById("load");
	if (bild) {
		if (zeigen) bild.style.display = 'block';
		else        bild.style.display = 'none';	
	}
}

function erstelleLadegrafik(pfad,woran) {
	var load = document.createElement("img");
	load.src = pfad;
	load.id = "load";
	load.style.display = "none";
	woran.appendChild(load);
	return load;
}

