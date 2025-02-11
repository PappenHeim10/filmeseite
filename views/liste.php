<?php
namespace mvc;
#echo "BeDugging: View=$view , action=$action, title=$title, page=$page, imdbId=$imdbId";

$movies = $api->getMoviesSortedByYear(strtolower($title), $page);

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

    $totalresults = isset($movies['totalResults']) ? (int)$movies['totalResults'] : 1; // die anzahl der filme
    $totalPages = ceil($totalresults / 10); // Die anzahl der film edurch 10 und dann auf die nächste zahl aufgerunded um die Letzte seite festzulegen
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;// Die aktuelle seite

    echo "<div class='pagination'>"; // Die pagination wird erstellt
    
    // First page
    if ($currentPage > 1) {
        echo "<a href='?action=liste&title=" . $title . "&page=1'>First</a> "; // Das ergebnis der $api->getMoviesSortedByYear funktion mit dem $titel parameter der aber statt vom get_übergeben zu werden aus der $_R
        echo "<a href='?action=liste&title=" . $title . "&page=" . ($currentPage - 1) . "'>Previous</a> ";
    }

    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $start + 5);
    if ($end - $start < 5 && $start > 1) {
        $start = max(1, $end - 5);
    }

    if ($start > 1) {
        echo "... ";
    }

    for ($i = $start; $i <= $end; $i++) {
        $url = "?action=liste&title=" . $title. "&page=" . $i;
        echo "<a href='" . $url . "'" . ($i == $currentPage ? " class='current'" : "") . ">" . $i . "</a> ";
    }

    if ($end < $totalPages) {
        echo "... ";
    }

    // Last page
    if ($currentPage < $totalPages) { // Ich bin mir nicht sicher wie der Link gener
        echo "<a href='?action=liste&title=" . $title . "&page=" . ($currentPage + 1) . "'>Next</a> ";
        echo "<a href='?action=liste&title=" . $title . "&page=" . $totalPages . "'>Last</a>";
    }

    echo "</div>";

} else {
    $movies = $api->movieZumstart('Titanic');
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
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    echo "<div class='pagination'>";
    
    // First page
    if ($currentPage > 1) {
        echo "<a href='?action=liste&title=" . urlencode($_REQUEST['title']) . "&page=1'>First</a> ";
        echo "<a href='?action=liste&title=" . urlencode($_REQUEST['title']) . "&page=" . ($currentPage - 1) . "'>Previous</a> ";
    }

    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $start + 5);
    if ($end - $start < 5 && $start > 1) {
        $start = max(1, $end - 5);
    }

    if ($start > 1) {
        echo "... ";
    }

    for ($i = $start; $i <= $end; $i++) {
        $url = "?action=liste&title=titanic&page=" . $i;
        echo "<a href='" . $url . "'" . ($i == $currentPage ? " class='current'" : "") . ">" . $i . "</a> ";
    }

    if ($end < $totalPages) {
        echo "... ";
    }

    // Last page
    if ($currentPage < $totalPages) {
        echo "<a href='?action=liste&title=titanic&page=" . ($currentPage + 1) . "'>Next</a> ";
        echo "<a href='?action=liste&title=titanic&page=" . $totalPages . "'>Last</a>";
    }

    echo "</div>";
}
