<?php
require_once __DIR__. '/FilmController.php';
require_once __DIR__. '/../models/Api.php';
require_once __DIR__. '/../models/Filme.php';
require_once __DIR__. '/../include/datenbank.php';



$json_dir = __DIR__. '/../admin/data/library_in_JSON'; // Der Pfad zu dem Ordner, in dem die JSON-Dateien gespeichert werden sollen
if (!is_dir($json_dir)) { // Wenn der Ordner nicht existiert
    mkdir($json_dir, 0777, true); // Erstelle den Ordner
}
$xml_dir = __DIR__. '/../admin/data/library_in_XML'; // Der Pfad zu dem Ordner, in dem die XML-Dateien gespeichert werden sollen
if (!is_dir($xml_dir)) { // Wenn der Ordner nicht existiert
    mkdir($xml_dir, 0777, true); // Erstelle den Ordner
}


$filmController = new mvc\FilmController(); // Alle Filme aus der Datenbank werden Aufgerufen
$filme = $filmController->getAlleFilme(); // Alle Filme (in from von arrays) aus der Datenbank werden in einer Variable gespeichert

$imdbIdListe = []; // Das ist die Liste mit allen titel
foreach ($filme as $film) {
    if (is_array($film) && isset($film['imdbid'])) { 
        $imdbIdListe[] = htmlspecialchars($film['imdbid']);// Alle Titel aus werden hier gespeichert
    }
}

$api = new mvc\Api(); // Die API wird aufgerufen

foreach($imdbIdListe as $imdbId){
    $filmDaten = $api->getFilmDetails($imdbId); // Die FilmeDaten werden an die API übergeben
    $jsonDaten = json_encode($filmDaten); // Die FilmeDaten werden in JSON formatiert

    file_put_contents($json_dir . '/' . $imdbId . '.json', $jsonDaten); // Die JSON-Datei wird erstellt und in den Ordner gespeichert
}
?>
