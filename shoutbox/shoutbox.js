window.onload = function() {
	Shoutbox = new Shoutbox();
}

function Shoutbox () {
	this.INTERVAL = 3000;
	this.SERVER_PATH = "./includes/module/shoutbox/shoutbox.php";
	this.IMAGE_PATH = "./includes/module/shoutbox/img";
	this.lauschenID = 0;
	
	lauschen(this.INTERVAL);
	erstelleLadegrafikShoutbox(this.IMAGE_PATH);
	vorbelegungsSteuerung();
}

function vorbelegungsSteuerung() {
	var shoutboxName = document.getElementsByName("shoutboxName")[0];
	var shoutboxNachricht = document.getElementsByName("shoutboxNachricht")[0];
	shoutboxName.onfocus = function () { if (shoutboxName.value == "Name") shoutboxName.value = ""; }
	shoutboxName.onblur = function () { if (shoutboxName.value == "") shoutboxName.value = "Name"; }
	shoutboxNachricht.onfocus = function () { if (shoutboxNachricht.value == "Nachricht") shoutboxNachricht.value = ""; }
	shoutboxNachricht.onblur = function () { if (shoutboxNachricht.value == "") shoutboxNachricht.value = "Nachricht"; }
}

function erstelleLadegrafikShoutbox(pfad) {
	var ladegrafik = erstelleLadegrafik(pfad + "/load.gif",document.getElementById("shoutbox"));
}

function lauschen(interval) {
	Shoutbox.lauschenID = window.setInterval(aktualisiereShoutbox,interval);
}

function nachrichtSenden() {
	var message = document.getElementsByName("shoutboxNachricht")[0];
	var name = document.getElementsByName("shoutboxName")[0];
	if (message.value=="" || name.value=="" || message.value=="Nachricht" || name.value=="Name") return false;
	sendeAnfrage("get",Shoutbox.SERVER_PATH+"?action=save&message=" + escape(message.value) + "&name=" +escape(name.value),function (r) {
		if (r == "disabled") return false;
		var d = document.getElementById("shoutboxShouts");
		d.insertBefore(shoutsAufbereiten(r), d.firstChild);
		classNeuBerechnen(d);
		message.value="";
		message.focus();
	}); 
	return false;
}


function showFAQ() {
	window.clearInterval(Shoutbox.lauschenID);
	var shouts = document.getElementById("shoutboxShouts");
	var FAQBox = document.getElementById("FAQBox");
	var FAQButton = document.getElementsByName("shoutboxShowFAQ")[0];
	if (FAQBox.innerHTML == "") {
		sendeAnfrage("get",Shoutbox.SERVER_PATH+"?action=showFAQ",function (r) {
			disableButton(true,true,true,false,true);
			FAQButton.className = "aktiv";
			FAQBox.className = "aktiv";
			shouts.className = "inaktiv";
			
			if (r != "no entry") FAQAufbereiten(r,FAQBox);
			else FAQBox.innerHTML = "noch kein Kommando";
			
			var frame = document.getElementById("shoutbox");
			showSmilies(false); // if displayed -> hide
		});
	} else {
		if (FAQBox.className == "aktiv") {
			FAQButton.className = "inaktiv";
			FAQBox.className    = "inaktiv";
			shouts.className    = "aktiv";
			disableButton(false,false,false,false,false);
			lauschen(Shoutbox.INTERVAL);
		} else{
			disableButton(true, true,true,false,true);
			FAQButton.className = "aktiv";
			FAQBox.className    = "aktiv";
			shouts.className    = "inaktiv";
			showSmilies(false);
		} 
	}
}


function showSmilies(zeigen) {
	var smilieBox = document.getElementById("smilieBox");
	var smilieButton = document.getElementsByName("shoutboxSmilies")[0];
	if (smilieBox.innerHTML == "" && zeigen) {
		sendeAnfrage("get",Shoutbox.SERVER_PATH+"?action=showSmilies",function (r) {		
			smilieButton.className = "aktiv";
			var frame = document.getElementById("shoutboxShouts");
			smilieBox.className = "aktiv";		
			smiliesAufbereiten(r,smilieBox);
		});
	} else {
		if (!smilieBox) return false;
		if (smilieBox.className == "aktiv" || !zeigen) {
			smilieBox.className = "inaktiv";
			smilieButton.className = "inaktiv";
		} else{
			smilieBox.className = "aktiv";
			smilieButton.className = "aktiv";
		} 
	}
}


aktualisiereShoutbox = function () {	
	var request = false;
	var lastShout = getFirstChildOf("p",document.getElementById("shoutboxShouts"));
	if (lastShout){
		var lastShoutID = lastShout.id.substring(5,lastShout.id.length);
		sendeAnfrage("get",Shoutbox.SERVER_PATH+"?action=getLastID",function (r){
			if (lastShoutID == r) return;
			else  sendeAnfrage("get",Shoutbox.SERVER_PATH+"?action=show",shoutsAktualisieren);	
		});
	} else sendeAnfrage("get",Shoutbox.SERVER_PATH+"?action=show",shoutsAktualisieren);	
}

shoutsAktualisieren = function (r) {	
	var shoutbox = document.getElementById("shoutboxShouts");
	if (r == "no shout") shoutbox.innerHTML = "Bisher keine Shouts";
	else {
		shoutsAufbereiten(r,shoutbox);
		classNeuBerechnen(shoutbox);
	}
}


function shoutsAufbereiten(jsonShouts,shoutbox) {
	var shouts = eval(jsonShouts);
	if (shoutbox) shoutbox.innerHTML = "";
	for (var i = 0; i < shouts.length; i++) {
		
		var shout = document.createElement("p");
		shout.id = "shout"+shouts[i].id;

		
		var shoutTime = document.createElement("span");
		shoutTime.className = "time";
		shoutTime.innerHTML = shouts[i].datum + " ";
		shout.appendChild(shoutTime);
		
		var shoutAutor = document.createElement("b");
		shoutAutor.innerHTML = shouts[i].name + " &raquo; ";
		shout.appendChild(shoutAutor);
			
		var shoutText = document.createElement("span");
		shoutText.id = "comment"+shouts[i].id;
		shoutText.innerHTML = shouts[i].text;
		shout.appendChild(shoutText);
		
		if (shoutbox) shoutbox.appendChild(shout);
		else return shout;
		
	}
	
}


function FAQAufbereiten(jsonFAQ, FAQBox) {
	var faq = eval(jsonFAQ);
	var FAQTable = document.createElement("table");
	FAQTable.id = "FAQTable";
	
	var FAQTableBody = document.createElement("tbody"); 
	
	var FAQTrTh = document.createElement("tr");	
	var FAQTRTHK = document.createElement("th");
	FAQTRTHK.innerHTML = "Kommando";	
	FAQTrTh.appendChild(FAQTRTHK);
	var FAQTRTHA = document.createElement("th");
	FAQTRTHA.innerHTML = "Anwendung";
	FAQTrTh.appendChild(FAQTRTHA);
	var FAQTRTHB = document.createElement("th");
	FAQTRTHB.innerHTML = "Beschreibung";
	FAQTrTh.appendChild(FAQTRTHB);
	FAQTableBody.appendChild(FAQTrTh); 
	for (var i = 0; i < faq.length; i++) {
	
		var FAQTr = document.createElement("tr");
		
		var FAQTdK = document.createElement("td");
		FAQTdK.innerHTML = faq[i].command;
		FAQTr.appendChild(FAQTdK);
		
		var FAQTdA = document.createElement("td");
		FAQTdA.innerHTML = faq[i].use;
		FAQTr.appendChild(FAQTdA);
		
		var FAQTdB = document.createElement("td");
		FAQTdB.innerHTML = faq[i].describ;
		FAQTr.appendChild(FAQTdB);		
		
		FAQTableBody.appendChild(FAQTr);
	}
	
	classNeuBerechnen(FAQTableBody);
	FAQTable.appendChild(FAQTableBody);
	FAQBox.appendChild(FAQTable);
	
}

function smiliesAufbereiten(jsonSmilies, smilieBox) {
	var smiliePath = Shoutbox.IMAGE_PATH + "/smilies/"
	var smilie = eval(jsonSmilies);
	var smilieTable = document.createElement("table");
	var smilieTableBody = document.createElement("tbody"); 
	var durchlauf = 0;
	for (var i = 0; i < 5; i++) {
		
		var smilieTableTr = document.createElement("tr");
		
		for (var i2 = 0; i2 < 5; i2++) {
			
			if (!smilie[durchlauf]) break;
			var smilieTableTd  = document.createElement("td");
			
			// Smilie Bild erstellen
			var smilieBild    = document.createElement("img");
			smilieBild.src    = smiliePath + smilie[durchlauf].datei;
			smilieBild.border = 0;
			smilieBild.title  = smilie[durchlauf].bez;
			smilieBild.alt    = smilie[durchlauf].bez;
	
			// Smilie Link erstellen
			var smilieLink  = document.createElement("a");
			smilieLink.href = "javascript:smilie('"+ smilie[durchlauf].bez +"')";
			smilieLink.appendChild(smilieBild);
			
			// Link mit Smlie in die Tabelle schreiben
			smilieTableTd.appendChild(smilieLink);
			
			smilieTableTr.appendChild(smilieTableTd);
			durchlauf++;
		}
		
		smilieTableBody.appendChild(smilieTableTr);
	}
	
	smilieTable.appendChild(smilieTableBody);
	smilieBox.appendChild(smilieTable);
	
}


/**	
*
* Helper
*
*/

function disableButton(name,nachricht,send,faq,smilies) {
	if (name) document.getElementsByName("shoutboxName")[0].disabled = true;
	else document.getElementsByName("shoutboxName")[0].disabled = false;
	
	if (faq) document.getElementsByName("shoutboxShowFAQ")[0].disabled = true;
	else document.getElementsByName("shoutboxShowFAQ")[0].disabled = false;
	
	if (nachricht) document.getElementsByName("shoutboxNachricht")[0].disabled = true;
	else document.getElementsByName("shoutboxNachricht")[0].disabled = false;
	
	if (send) document.getElementsByName("shoutboxSubmitButton")[0].disabled = true;
	else document.getElementsByName("shoutboxSubmitButton")[0].disabled = false;
	
	if (smilies) document.getElementsByName("shoutboxSmilies")[0].disabled = true;
	else document.getElementsByName("shoutboxSmilies")[0].disabled = false;
}


function smilie(theSmilie) {
	var feld = document.getElementsByName("shoutboxNachricht")[0];
	feld.value += " " + theSmilie;
}


function classNeuBerechnen(wovon) {
	if (!wovon) return false;
	for (var i = 0; i < wovon.childNodes.length; i++) {
		var name = (i & 1)?"ungerade":"gerade";	
		wovon.children[i].className = name;
	}
}

function getFirstChildOf(tagname,parent) {
	if (!parent) return;	
	var childs = parent.childNodes;
	for (var i = 0; i < childs.length; i++) {
		if (!childs[i].tagName) continue;
		if (childs[i].tagName.toLowerCase == tagname.toLowerCase) return childs[i];
	}
}
