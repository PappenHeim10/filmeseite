<?php
require_once __DIR__. '/FilmController.php';
require_once __DIR__. '/../models/Api.php';
require_once __DIR__. '/../models/Filme.php';
require_once __DIR__. '/../include/datenbank.php';

$filmController = new mvc\FilmController();
$filmTitelUndImdbIdListe = $filmController->getFilmTitelUndImdbIds();


$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;
$root = $xml->createElement('Hinwise');
$xml->appendChild($root);



foreach($filmTitelUndImdbIdListe as $filmTitelUndImdbIds)
{

    //NOTE: Hier wird der Url erstellt
    $baseurl = "?action=singleMovies&imdbID=" . $filmTitelUndImdbIds['imdbid'] . "&view=singleMovies";
    $safe_url = htmlspecialchars($baseurl);


    // NOTE: Hier wird der Titel erstellt
    $titel = $filmTitelUndImdbIds['titel'];
    $safe_titel = htmlspecialchars($titel);

    //NOTE: Hier wird der Link erstellt
    $ahref = $xml->createElement('a'); //erstelle das <a> element.
    $ahref->setAttribute('href', $safe_url); //WICHTIG: setze das href Attribut.

    $link = $xml->createElement('link');
    $url = $xml->createElement('url');

    $titel = $xml->createElement('titel', $safe_titel);

    $url->appendChild($ahref);
    
    $link->appendChild($titel);
    $link->appendChild($url);

    $root->appendChild($link); //füge dem Url Node den Link hinzu.
}

$xml->save('filmLinks.xml');
?>