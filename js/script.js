function showMovies(str) {
    if (str.trim() === "") {
        document.getElementById("movie").innerHTML = "";
        return;
    }

    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("movie").innerHTML = this.responseText;
        } else if (this.readyState === 4) { // Fehlerbehandlung
            document.getElementById("movie").innerHTML = "<p>Fehler beim Abrufen der Vorschläge.</p>";
            console.error("Fehler. Status:", this.status, "Response:", this.responseText);
        }
    };
    xmlhttp.open("GET", "include/ajax_suche.php?q=" + encodeURIComponent(str), true); // Verwende die neue Datei
    xmlhttp.send();
}