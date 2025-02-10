<?php
#echo "BeDugging: View=$view , action=$action, title=$title, page=$page, imdbId=$imdbId"; 


$movies = $api->movieZumstart('Movie');

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

}
?>
 