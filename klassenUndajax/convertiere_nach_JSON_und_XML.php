<?php
require_once __DIR__. '/FilmController.php';
require_once __DIR__. '/../models/Api.php';
require_once __DIR__. '/../models/Filme.php';
require_once __DIR__. '/../include/datenbank.php';

$json_dir = __DIR__. '/../admin/data/library_in_JSON'; // Der Pfad zu dem Ordner, in dem die JSON-Dateien gespeichert werden sollen
if (!is_dir($json_dir)) { // Wenn der Ordner nicht existiert
    mkdir($json_dir, 0777, true); // Erstelle den Ordner und gib ihm di epassender Berechtigung
}

$xml_dir = __DIR__. '/../admin/data/library_in_XML'; // Der Pfad zu dem Ordner, in dem die XML-Dateien gespeichert werden sollen
if (!is_dir($xml_dir)) { // Wenn der Ordner nicht existiert
    mkdir($xml_dir, 0777, true); // Erstelle den Ordner
}

$filmController = new mvc\FilmController();
$alleImdbIds = $filmController->getImdbIdListe(); 
$api = new mvc\Api();


foreach ($alleImdbIds as $imdbId) {
    $filmDatenJSON = $api->getFilmDetailsInJson($imdbId);

    if (isset($filmDatenJSON['Error'])) { // Bessere Fehlerprüfung!
        write_error("Fehler beim Abrufen der Filmdaten für IMDb-ID $imdbId: " . $filmDatenJSON['Error']);
        continue;
    }
    if ($filmDatenJSON['Response'] === 'False') {
       write_error("API-Fehler (Response = False) für IMDb-ID $imdbId: " . $filmDatenJSON['Error']);
        continue;
    }

    $json_datei = $json_dir . '/' . $imdbId . '.json';

     // Prüfe ob die datei schon existiert
    if (file_exists($json_datei)) {
        write_log("Datei existiert bereits und wird uebersprungen für IMDb-ID $imdbId: $json_datei"); // OPTIM: Das soll ein log und keine error sein
        continue; // Überspringe, wenn die Datei bereits existiert.
    }

    // JSON-Daten in Datei speichern (mit korrekter Kodierung)
    if (file_put_contents($json_datei, json_encode($filmDatenJSON)) === false) {
        write_error("Fehler beim Speichern der JSON-Datei für IMDb-ID $imdbId: " . $json_datei);
    }
}

foreach($titel_Liste as $imdbId){ // DO!: Den Herausfinden was ich mit einer XML Datei machen kann.
    $filmDatenXML = $api->getFilmDetailsInXml($imdbId); // Die FilmeDaten werden an die API übergeben

    if ($filmDatenXML instanceof SimpleXMLElement) {
        //DO!:Aufschreiben wie die XML Datei erstellt wird

    } else {
        // Fehlerbehandlung, falls $filme kein SimpleXMLElement ist
        write_error("Fehler: Keine XML-Datei: " . $imdbId);
    }
}

?>
