function handleKeyUp(value) {
  showResult(value);  // Führt showResult() aus
  showMovies(value);  // Führt showMovies() aus
}


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
  if (str.length == 0) {
      document.getElementById("livesearch").innerHTML = "";
      document.getElementById("livesearch").style.border = "0px";
      document.getElementById("livesearch").style.position = "static"; // Reset position
      return;
  }

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          try {  //  Fehlerbehandlung für JSON.parse()
              const response = JSON.parse(this.responseText); // JSON parsen

              let html = '';
              if (response.length > 0) {
                  response.forEach(item => {
                      html += '<div><a href="' + item.url + '">' + item.titel + '</a></div>'; // Links erstellen
                  });
                  document.getElementById("livesearch").innerHTML = html;
                  document.getElementById("livesearch").style.border = "1px solid rgb(36, 65, 48)";
                  document.getElementById("livesearch").style.backgroundColor = "rgb(255, 255, 255)";
                  document.getElementById("livesearch").style.position = "absolute";
                  document.getElementById("livesearch").style.zIndex = "100";

                  const searchInput = document.getElementById("searchInput");
                  const inputRect = searchInput.getBoundingClientRect();
                  document.getElementById("livesearch").style.top = (inputRect.bottom) + "px";
                  document.getElementById("livesearch").style.left = (inputRect.left) + "px";
                  document.getElementById("livesearch").style.width = (inputRect.width) + "px";
              } else {
                  document.getElementById("livesearch").innerHTML = "<div>Keine Vorschläge gefunden.</div>"; // Oder einfach ausblenden
              }

          } catch (e) {
              console.error("Fehler beim Parsen der JSON-Antwort:", e);
              document.getElementById("livesearch").innerHTML = "<div>Fehler bei der Verarbeitung der Daten.</div>";
          }
      }
  };

  xmlhttp.open("GET", "klassenUndajax/livesearch.php?q=" + encodeURIComponent(str), true); // Korrekte URL und Wert
  xmlhttp.send();
}

// Event-Listener (ausblenden bei Klick außerhalb) - wie zuvor
document.addEventListener('click', function(event) {
  const searchInput = document.getElementById('searchInput');
  const livesearch = document.getElementById('livesearch');
  if (!searchInput.contains(event.target) && !livesearch.contains(event.target)) {
      livesearch.style.display = 'none';
  }
});
