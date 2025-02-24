function showMovies(str) { // die Funktion wird definiert und nimmt einen Sring entgegen
    if (str.trim() === "") { // -wenn der String leer ist 
        document.getElementById("movie").innerHTML = ""; // Wir der Ausgabe ort geleert
        return; // Funktion ist beendet 
    }

    const xmlhttp = new XMLHttpRequest(); //Diese Funktion sendet http anfragen ohne die seite neu laden zu müssen
    xmlhttp.onreadystatechange = function() { // onready ist ei Event handler der jedesmal aufgerufen wird wenn sich der readyState des requests ändert
        if (this.readyState === 4 && this.status === 200) { // readyState gibt den Status der Anfrage zurück
            // 0 Unset: Die Anfrage wurde noch nicht initialiesiert
            // 1 Opened: Die Anfrage wirde initialisiert, aber ncih nicht gesendet
            // 2 Headers_received: Die Header der Antwort wurde empfangen
            // 3 Loading: Die Antwort wird herutergeladen
            // 4 Done: Die Anfrage ist abgeschlossen
            // this.status gubt den HTTP-Statuscode der Antwort an
            // zb. 200 für "OK"
            // 404 für "Not Found"
            // 500 für "Internet Server Error"
            document.getElementById("movie").innerHTML = this.responseText;
            // Das Element mit der id movie wird mit dem Text der Antwort aktualiesert
        } else if (this.readyState === 4) { // Fehlerbehandlung
            document.getElementById("movie").innerHTML = "<p>Fehler beim Abrufen der Vorschläge.</p>";
            console.error("Fehler. Status:", this.status, "Response:", this.responseText);
        }
    };
    xmlhttp.open("GET", "klassenUndajax/ajax_suche.php?q=" + encodeURIComponent(str), true); // Verwende die neue Datei
    xmlhttp.send();
}


function showResult(str) {
    if (str.length==0) {
      document.getElementById("livesearch").innerHTML="";
      document.getElementById("livesearch").style.border="0px";
      return;
    }
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200) {
        document.getElementById("livesearch").innerHTML=this.responseText;
        document.getElementById("livesearch").style.border="1px solid #A5ACB2";
      }
    }
    xmlhttp.open("GET","livesearch.php?q="+str,true);
    xmlhttp.send();
}


