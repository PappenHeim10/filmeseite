<?php
namespace mvc;

$movies = $api->getMovies(strtolower($title), $view, $page,$imdbId); // Wenn der view 'liste' ist, wird die Antwort der API methode getMovies an movies gespeichert




// Direkter Zugriff auf $movies, da es im selben Scope ist.
if (is_array($movies) && !empty($movies['Search'])) {
    foreach ($movies['Search'] as $movie) {


        $output = "<div class='movie'>";
        $output.= "<h2><a href='?action=singleMovies&imdbID=". urlencode($movie['imdbID']). "&view=singleMovies&'>". htmlspecialchars($movie["Title"]). "</a></h2>";
        $output .= "<img src='" . htmlspecialchars($movie['Poster']) . "' alt='" . htmlspecialchars($movie['Title']) . "'>";
        $output .= "<p>Erscheinungs Jahr:  " . htmlspecialchars($movie['Year']) . "</p>";
        $output .= "<p>Art: " . htmlspecialchars($movie['Type']) . "</p>";
        $output .= "<p>imdbID: " . htmlspecialchars($movie['imdbID']) . "</p>";
        $output .= "</div>";

        echo $output;
    }


    $totalresults = isset($movies['totalResults']) ? (int)$movies['totalResults'] : 1;
    $totalPages = ceil($totalresults / 10);
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 0;



    echo "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        $url = "?action=liste&title=" . urlencode($_REQUEST['title']) . "&page=" . $i;
        echo "<a href='" . $url . "'" . ($i == $currentPage ? " class='current'" : "") . ">" . $i . "</a> ";
    }
    echo "</div>";


} else {
    echo "<p>Keine Filme mit dem Titel <u>".$title."</u> gefunden.</p>";
}
