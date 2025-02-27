<?php
// In index.php ODER wo auch immer du die XML-Datei erstellst.
require_once __DIR__. '/FilmController.php';
require_once __DIR__. '/../models/Api.php'; // Falls benötigt
require_once __DIR__. '/../models/Filme.php'; // Falls benötigt
require_once __DIR__. '/../include/datenbank.php'; // Falls benötigt

use mvc\FilmController;

$filmController = new FilmController();
$filmTitelUndImdbIdListe = $filmController->getFilmTitelUndImdbIds(); // Die Titel und die ID werden gerugen

$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true; //TIP: Für bessere Lesbarkeit
$root = $xml->createElement('Hinweise'); // Das Root Element wird erstellt
$xml->appendChild($root);

foreach ($filmTitelUndImdbIdListe as $film) {
    $link = $xml->createElement('link');

    // NOTE: Das Titel Element wird erstellt, der Element Text wird erstellt und dann dem Titel Element hinzugefügt
    $titel = $xml->createElement('titel');
    $titelText = $xml->createTextNode(htmlspecialchars($film['titel'], ENT_QUOTES, 'UTF-8')); //  Textknoten erstellen!
    $titel->appendChild($titelText);

    // NOTE: Das Url Element wird erstellt, die URL wird erstellt und dann dem Url Element hinzugefügt
    $url = $xml->createElement('url');
    $urlValue = "?action=singleMovies&imdbID=" . urlencode($film['imdbid']) . "&view=singleMovies";
    $urlText = $xml->createTextNode($urlValue);
    $url->appendChild($urlText);

    $link->appendChild($titel);
    $link->appendChild($url);
    $root->appendChild($link);
}

$xml->save('filmLinks.xml'); // Speicherort überprüfen!

?>