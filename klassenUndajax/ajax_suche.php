<?php
require_once __DIR__. '/FilmController.php';
require_once __DIR__. '/../models/Api.php';
require_once __DIR__. '/../models/Filme.php';
require_once __DIR__. '/../include/datenbank.php';

$filmController = new mvc\FilmController(); // Alle Filme aus der Datenbank werden Aufgerufen
$filmTitelListe = $filmController->getFilmTitelListe(); //Ein Array als liste aller Filme 

$q = $_GET['q'] ?? ''; // Wenn der q parameter nicht existiert wird ein leerer string benutzt
$q = trim($q); // q wird getrimmt 
$q = strtolower($q); // und in kleinbuchstaben gesetzt

$hint = []; // Die Variable in der die ergebnisse der suche gespeichert werden 

if ($q !== "") {
    $q = strtolower($q);
    $len = strlen($q);
    foreach($filmTitelListe as $titel) {
        if (stristr($q, substr($titel, 0, $len))) {
            $hint[] = $titel;
        }
    }
}

$filmIds = [];

foreach($hint as $titel){
    $filmIds[] = $filmController->getFilmeIdNachName($titel);
}

foreach($filmIds as $id){
    if(is_array($id))
    {
        foreach($id as $idpoint){
            $filmData[] = $filmController->getFilmNachId($idpoint);
        }
    }else{
        $filmData[] = $filmController->getFilmNachId($id);
    }
}

if(isset($filmData))
    filmeAnzeigen($filmData);
?>
