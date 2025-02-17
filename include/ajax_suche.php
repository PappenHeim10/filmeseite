
<?php
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

if ($q !== '') { // Wenn q kein string ist
    foreach ($titelListe as $name) { // es wird durch die list iteriert
        if (strpos(strtolower($name), $q) !== false) { // Sucht im Titel ob er q enthält
            $hint[] = $name; // wird der name in der liste gespeichert
        }
    }
}

$gesuchteFilme = []; // Die daten aller Filme werden hier gespeichert
foreach($hint as $film){ // Für jeden Film der als hinweis angegeben wird
    if (is_array($film) && !empty($film)){ // wenn der film icht leer ist und ein array
        $filmid = $filmController->getFilmeIdNachName($film); // gib die id zurück
        $filmDaten = $filmController->getFilmNachId($filmid); // Nimmt die daten des Films
        $gesuchteFilme[] = $filmDaten; //Speichert sie dann nochmal in einem Array
    }
}
?>

<pre>
    <?php print_r($gesuchteFilme) ?>
</pre>