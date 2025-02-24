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


$filmController = new mvc\FilmController(); // Alle Filme aus der Datenbank werden Aufgerufen
$alleImdbIds = $filmController->getImdbIdListe(); // Alle Filme aus der Datenbank werden Aufgerufen
$api = new mvc\Api(); // Die API wird aufgerufen


foreach($alleImdbIds as $imdbId){
    $filmDatenJSON = $api->getFilmDetailsInJson($imdbId); // Die FilmeDaten werden an die API übergeben

    if ($filmDatenJSON === null) {
        write_error("Fehler beim Abrufen der Filmdaten für in JSON für IMDb-ID: " . $imdbId);
        continue; // Mit dem nächsten Film fortfahren
    }
    
    $jsonDaten = json_encode($filmDatenJSON); // Die FilmeDaten werden in JSON formatiert

    $json_datei = $json_dir . '/' . $imdbId . '.json';

    file_put_contents($json_dir . '/' . $imdbId . '.json', $jsonDaten); // Die JSON-Datei wird erstellt und in den Ordner gespeichert
}


foreach($titel_Liste as $imdbId){ // DO!: Den Herausfinden was ichmit einer XML Datei machen kann.
    $filmDatenXML = $api->getFilmDetailsInXml($imdbId); // Die FilmeDaten werden an die API übergeben

    if($filmDatenXML === null){
        write_error("Fehler beim Abrufen der Filmdaten für in XML für IMDb-ID: " . $imdbId);
        continue; // Mit dem nächsten Film fortfahren
    }
    // WICHTIG: Hier musss das Passende script für die XML erstellung hin
}

?>
