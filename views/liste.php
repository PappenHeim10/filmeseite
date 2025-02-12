<?php
namespace mvc;

// Es werden Alle filme aus der Datenbank geladen
$filme = $filmController->filmeModel->selectAll();


// Wenn keine filme in der Datenbank gefunden wurden ODER wenn einen neue Seite angefordert wird
if ($filme === false) {
    $filme = $api->getFilme(strtolower($title), $page); // Daten von der API abrufen

    // Filme in die Datenbank einfügen, *nachdem* sie von der API abgerufen wurden
    if ($filme && isset($filme['Search']) && is_array($filme['Search'])) { 
           $filmController->filmeMasseneinfuegen($title, $page); // Speichert die Filme in der Datenbank
    }
}


// --- Filme anzeigen (egal ob aus DB oder API) ---
if (is_array($filme) && isset($filme['Search']) && !empty($filme['Search'])) : // API Antwort wird erstellt
    foreach ($filme['Search'] as $movie) : // Die Filme werden hier angezeigt
        $output = "<div class='movie'>";
        $output .= "<h2><a href='?action=singleMovies&imdbID=" . urlencode($movie['imdbID']) . "&view=singleMovies&'>" . htmlspecialchars($movie["Title"]) . "</a></h2>";
        $output .= "<img src='" . htmlspecialchars($movie['Poster']) . "' alt='" . htmlspecialchars($movie['Title']) . "'>";
        $output .= "<p>Erscheinungs Jahr:  " . htmlspecialchars($movie['Year']) . "</p>";
        $output .= "<p>imdbID: " . htmlspecialchars($movie['imdbID']) . "</p>";
        $output .= "</div>";
        echo $output;
    endforeach;

     $totalresults = isset($filme['totalResults']) ? (int)$filme['totalResults'] : 1; // die Anzahl aller Filme werden hier gespeichert oder auf Null gesetzt
     $totalPages = ceil($totalresults / 10); // Die anzahl aller seite iwrd hier gespeichert


elseif(is_array($filme) && !empty($filme)): // Datenbank-Ergebnisse
    foreach ($filme as $movie) :
        $output = "<div class='movie'>";
        // ACHTUNG: Hier die korrekten (kleingeschriebenen) Schlüssel verwenden!
        $output .= "<h2><a href='?action=singleMovies&imdbID=" . urlencode($movie['imdbid']) . "&view=singleMovies&'>" . htmlspecialchars($movie["titel"]) . "</a></h2>";
        $output .= "<img src='" . htmlspecialchars($movie['poster']) . "' alt='" . htmlspecialchars($movie['titel']) . "'>";
        $output .= "<p>Erscheinungs Jahr:  " . htmlspecialchars($movie['erscheinungs_jahr']) . "</p>";
        $output .= "<p>imdbID: " . htmlspecialchars($movie['imdbid']) . "</p>"; //imdbID ist jetzt kleingeschrieben
        $output .= "</div>";
        echo $output;

endforeach;
       $totalresults = 0; // Gesamtanzahl aus DB holen
       $stmt = $filmController->filmeModel->db->prepare("SELECT COUNT(*) FROM filme WHERE LOWER(titel) LIKE LOWER(:suchbegriff)"); // $filmController->filmeModel->db, da verbindung im model
       $stmt->bindValue(':suchbegriff', '%' . $title . '%', \PDO::PARAM_STR);
       $stmt->execute();
       $totalresults = (int)$stmt->fetchColumn();
      $totalPages = ceil($totalresults / 10);
else : // Keine Filme gefunden (weder in DB noch von API)
    echo "<p>Keine Filme gefunden.</p>";
endif;


// --- Pagination  ---
if(isset($totalPages)) : // Wird nur angezeigt wenn es überhaupt ergebnisse gibt.
    $currentPage = $page;
    echo "<div class='pagination'>";

    // First page
    if ($currentPage > 1) {
        echo "<a href='?action=liste&title=" . urlencode($title) . "&page=1'>First</a> ";
        echo "<a href='?action=liste&title=" . urlencode($title) . "&page=" . ($currentPage - 1) . "'>Previous</a> ";
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
        $url = "?action=liste&title=" . urlencode($title) . "&page=" . $i;
        echo "<a href='" . $url . "'" . ($i == $currentPage ? " class='current'" : "") . ">" . $i . "</a> ";
    }

    if ($end < $totalPages) {
        echo "... ";
    }

    // Last page
    if ($currentPage < $totalPages) {
        echo "<a href='?action=liste&title=" . urlencode($title) . "&page=" . ($currentPage + 1) . "'>Next</a> ";
        echo "<a href='?action=liste&title=" . urlencode($title) . "&page=" . $totalPages . "'>Last</a>";
    }
    echo "</div>";
endif;

?>