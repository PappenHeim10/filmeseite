<?php
require_once __DIR__. '/FilmController.php';
require_once __DIR__. '/../models/Api.php';
require_once __DIR__. '/../models/Filme.php';
require_once __DIR__. '/../include/datenbank.php';



$filmController = new mvc\FilmController(); // Alle Filme aus der Datenbank werden Aufgerufen
$filme = $filmController->getAlleFilme(); // Alle Filme (in from von arrays) aus der Datenbank werden in einer Variable gespeichert



$titelListe = []; // Das ist die Liste mit allen titel
foreach ($filme as $film) {
    if (is_array($film) && isset($film['titel'])) { 
        $titelListe[] = htmlspecialchars($film['titel']);// Alle Titel aus werden hier gespeichert
    }
}

$q = $_GET['q'] ?? ''; // Wenn der q parameter nicht existiert wird ein leerer string benutzt
$q = trim($q); // q wird getrimmt 
$q = strtolower($q); // und in kleinbuchstaben gesetzt

$hint = []; // Die Variable in der die ergebnisse der suche gespeichert werden 


if ($q !== "") {
    $q = strtolower($q);
    $len = strlen($q);
    foreach($titelListe as $titel) {
        if (stristr($q, substr($titel, 0, $len))) {
            $hint[] = $titel;
        }
    }
}


foreach($hint as $titel){
    $filmIds[] = $filmController->getFilmeIdNachName($titel);
}

foreach($filmIds as $id){
    $filmData[] = $filmController->getFilmNachId($id);
}
?>

<pre>
    <?php print_r($filmData); ?>
</pre>