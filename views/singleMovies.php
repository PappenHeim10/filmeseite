<?php
namespace mvc;
#echo "BeDugging: View=$view , action=$action, title=$title, page=$page, imdbId=$imdbId"; 

$film = new Film($movie);
$film->insert();        

if (!empty($imdbId)) {
    $movie = $api->getMovies('', 'singleMovies', 1, $imdbId); 

    if (is_array($movie) && $movie['Response'] == 'True') {
        $output = "<div class='einzelFilm'>";
        $output .= "<div>";

        // Titel (immer vorhanden, wenn Response == True)
        $output .= "<h2>" . htmlspecialchars($movie['Title']) . "</h2>";

        // Poster (mit Prüfung auf leeren String)
        if (!empty($movie['Poster']) && $movie['Poster'] !== 'N/A') {
            $output .= "<img src='" . htmlspecialchars($movie['Poster']) . "' alt='" . htmlspecialchars($movie['Title']) . "'>";
        } else {
            $output .= "<p>Kein Poster verfügbar.</p>"; // Oder ein Standard-Platzhalterbild
        }

        // Erscheinungsjahr
        if (!empty($movie['Year']) && $movie['Year'] !== 'N/A') {
            $output .= "<p>Erscheinungs Jahr: " . htmlspecialchars($movie['Year']) . "</p>";
        }

        $output .= "</div>";
        $output .= "<div>";

        // Alle anderen Felder (mit Prüfung auf leeren String UND 'N/A')
        $fields = [
            'Rated' => 'Rated',
            'Runtime' => 'Runtime',
            'Genre' => 'Genre',
            'Director' => 'Director',
            'Writer' => 'Writer',
            'Actors' => 'Actors',
            'Plot' => 'Plot',
            'Language' => 'Language',
            'Country' => 'Country',
            'Awards' => 'Awards',
            'Metascore' => 'Metascore',
            'imdbRating' => 'imdbRating',
            'imdbVotes' => 'imdbVotes',
            'imdbID' => 'imdbID',
            'Type' => 'Type',
            'DVD' => 'DVD',
            'BoxOffice' => 'BoxOffice',
            'Production' => 'Production',
            'Website' => 'Website',
        ];

        foreach ($fields as $key => $label) {
            if (!empty($movie[$key]) && $movie[$key] !== 'N/A') {
                $output .= "<p>$label: " . htmlspecialchars($movie[$key]) . "</p>";
            }
        }
        // Ratings (spezielle Behandlung, da es ein Array ist)
        if (isset($movie['Ratings']) && is_array($movie['Ratings']) && !empty($movie['Ratings'])) {
            $output .= "<p>Ratings: <br>";
            foreach ($movie['Ratings'] as $rating) {
                if (!empty($rating['Source']) && !empty($rating['Value'])) { //auch hier prüfen ob etwas vorhanden ist.
                    $output .= htmlspecialchars($rating['Source']) . ": " . htmlspecialchars($rating['Value']) . "<br>";
                }
            }
            $output .= "</p>";
        }


        $output .= "</div>";
        $output .= "</div>";

        echo $output;


    } elseif(isset($movie['Error'])) {
        echo "<p> Fehler: " . htmlspecialchars($movie["Error"]) . "</p>"; // Fehlermeldung anzeigen, falls vorhanden
    } else {

        echo "<p> Keine Daten gefunden. </p>"; // Fehlermeldung anzeigen, falls keine Daten gefunden wurden
        }
    }
    else 
    {
        $movie = $api->getEinenFilm('Fight');
        $film = new Film($movie);
        $film->insert();        


        if (is_array($movie) && $movie['Response'] == 'True') {
            $output = "<div class='einzelFilm'>";
            $output .= "<div>";
    
            // Titel (immer vorhanden, wenn Response == True)
            $output .= "<h2>" . htmlspecialchars($movie['Title']) . "</h2>";
    
            // Poster (mit Prüfung auf leeren String)
            if (!empty($movie['Poster']) && $movie['Poster'] !== 'N/A') {
                $output .= "<img src='" . htmlspecialchars($movie['Poster']) . "' alt='" . htmlspecialchars($movie['Title']) . "'>";
            } else {
                $output .= "<p>Kein Poster verfügbar.</p>"; // Oder ein Standard-Platzhalterbild
            }
    
            // Erscheinungsjahr
            if (!empty($movie['Year']) && $movie['Year'] !== 'N/A') {
                $output .= "<p>Erscheinungs Jahr: " . htmlspecialchars($movie['Year']) . "</p>";
            }
    
            $output .= "</div>";
            $output .= "<div>";
    
            // Alle anderen Felder (mit Prüfung auf leeren String UND 'N/A')
            $fields = [
                'Rated' => 'Rated',
                'Runtime' => 'Runtime',
                'Genre' => 'Genre',
                'Director' => 'Director',
                'Writer' => 'Writer',
                'Actors' => 'Actors',
                'Plot' => 'Plot',
                'Language' => 'Language',
                'Country' => 'Country',
                'Awards' => 'Awards',
                'Metascore' => 'Metascore',
                'imdbRating' => 'imdbRating',
                'imdbVotes' => 'imdbVotes',
                'imdbID' => 'imdbID',
                'Type' => 'Type',
                'DVD' => 'DVD',
                'BoxOffice' => 'BoxOffice',
                'Production' => 'Production',
                'Website' => 'Website',
            ];
    
            foreach ($fields as $key => $label) {
                if (!empty($movie[$key]) && $movie[$key] !== 'N/A') {
                    $output .= "<p>$label: " . htmlspecialchars($movie[$key]) . "</p>";
                }
            }
            // Ratings (spezielle Behandlung, da es ein Array ist)
            if (isset($movie['Ratings']) && is_array($movie['Ratings']) && !empty($movie['Ratings'])) {
                $output .= "<p>Ratings: <br>";
                foreach ($movie['Ratings'] as $rating) {
                    if (!empty($rating['Source']) && !empty($rating['Value'])) { //auch hier prüfen ob etwas vorhanden ist.
                        $output .= htmlspecialchars($rating['Source']) . ": " . htmlspecialchars($rating['Value']) . "<br>";
                    }
                }
            $output .= "</p>";
            }
        $output .= "</div>";
        $output .= "</div>";

        echo $output;
    }
}
?>