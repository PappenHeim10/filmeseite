<?php
require_once __DIR__. '/FilmController.php';
require_once __DIR__. '/../models/Api.php';
require_once __DIR__. '/../models/Filme.php';
require_once __DIR__. '/../include/datenbank.php';


$filmController = new mvc\FilmController(); // Alle Filme aus der Datenbank werden Aufgerufen
$filme = $filmController->getAlleFilme(); // Alle Filme (in from von arrays) aus der Datenbank werden in einer Variable gespeichert

$imdbIdListe = []; // Das ist die Liste mit allen titel
foreach ($filme as $film) {
    if (is_array($film) && isset($film['imdbid'])) { 
        $imdbIdListe[] = htmlspecialchars($film['imdbid']);// Alle Titel aus werden hier gespeichert
    }
}

$api = new mvc\Api(); // Die API wird aufgerufen


$filmDaten = $api->getFilmDetails($imdbIdListe); // Die FilmeDaten werden an die API übergeben
?>