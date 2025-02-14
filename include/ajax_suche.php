<?php
namespace mvc;
require_once $_SERVER['DOCUMENT_ROOT'] . '/phpa-d/filmesite/klassen/FilmController.php';
require_once 'include/datenbank.php'; //WICHTIG: Hier ist ein Bug der repariert werden muss.


$filmController = new FilmController();
$filme = $filmController->getAlleFilme();


$titelListe = [];
foreach ($filme as $film) {
    if (is_array($film) && isset($film['titel'])) {
        $titelListe[] = htmlspecialchars($film['titel']);
    }
}

$q = $_GET['q'] ?? '';
$q = trim($q);
$q = strtolower($q);

$hint = "";

if ($q !== '') {
    foreach ($titelListe as $name) {
        if (strpos(strtolower($name), $q) !== false) {
            if ($hint === "") {
                $hint = $name;
            } else {
                $hint .= ", " . $name;
            }
        }
    }
}

echo $hint === "" ? "" : $hint;

?>
