<?php
namespace mvc;
#echo "BeDugging: View=$view , action=$action, title=$title, page=$page, imdbId=$imdbId";

if (!empty($imdbId)){ // Wenn ein imdb nummer zur verfügung steht
    $film = $filmController->getFilmNachImdb($imdbId); // Wird deren die filmdaten anhand der nummer aufgerugefen

    if (is_array($film)) { // Wenn der Film ein array ist
        echo einzeilAnzeigen($film); //
    }
}


if(empty($imdbId) && empty($title))
{
    $film = $filmController->getZufallsFilm();

    if(!$film){
        echo "<p>Es ist ein Fehler aufgetreten: Kein zufälliges Film gefunden.</p>";
        return;  // Abbruch der Funktion, damit keine Fehler bei leeren Datenbankergebnissen passiert
    }

    if (is_array($film)) {
        echo einzeilAnzeigen($film); // Wenn der Film ein array ist
    }
}


if($title){

    $filmId = $filmController->getFilmeIdNachName($title);
    if (!$filmId) {
        echo "<p>Es ist ein Fehler aufgetreten: Keine ID mit dem Titel '$title' gefunden.</p>";
        return; // Abbruch der Funktion, damit keine Fehler bei leeren Datenbankergebnissen passiert
    }
    $film = $filmController->getFilmNachId($filmId);

    if (is_array($film)) {
        echo einzeilAnzeigen($film); // Wenn der Film ein array ist
    }
    else {
        echo "<p>Es ist ein Fehler aufgetreten: Kein Film mit dem Titel '$title' gefunden.</p>";
    }
}
?>

