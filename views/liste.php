<?php
namespace mvc;
?>
<div class="liste"> 
<?php
// Es werden nach Filmen in der Datenbank gesucht
if(isset($_REQUEST['title']) && isset($_REQUEST['page']) && !empty($_REQUEST['title']))
{
    $filme = $filmController->getFilmeAusDerDatenbank($title,$page);

    if (is_array($filme) && !empty($filme)) : // Immer Datenbank-Schlüssel
        filmeAnzeigen($filme);
    elseif(isset($filme['Error'])): // API Fehler anzeigen
        echo "<p>".htmlspecialchars($filme['Error'])."</p>";
    else:
        echo "<p>Keine Filme gefunden.</p>"; // Keine Ergebnisse (weder API noch DB)
    endif;
    ?>

    </div>
    <?php

    // --- 5. Pagination ---
    $totalresults = 0; // Initialisieren

    // Gesamtzahl IMMER aus Datenbank holen
    try {
        $stmt = $filmController->filmeModel->db->prepare("SELECT COUNT(*) FROM filme WHERE LOWER(titel) LIKE LOWER(:suchbegriff)");
        $stmt->bindValue(':suchbegriff', '%' . $title . '%', \PDO::PARAM_STR);
        $stmt->execute();
        $totalresults = (int)$stmt->fetchColumn();
    } catch (\PDOException $e) {
        write_error("Fehler bei der Datenbankabfrage: " . $e->getMessage()."<br>".__FILE__ );
    }

    // --- Pagination ---
    $totalresults = $filmController->countFilme($title); // Die Anzahl der Filme mit dem gleichen Namen werden gesucht
    $totalPages = ceil($totalresults / 10); // Anzahl der Filme durch die anzahl der filme die Angezeigt werden können

    if ($totalresults > 0 && $totalPages > 1) : // W
        $currentPage = $page;
        echo "<div class='pagination'>";

        $urlBase = "?action=liste&title=" . urlencode($title) . "&page=";

        // "First" und "Previous"
        if ($currentPage > 1) {
            echo "<a href='" . $urlBase . "1'>First</a> ";
            echo "<a href='" . $urlBase . ($currentPage - 1) . "'>Previous</a> ";
        }

        // Seitenzahlen (optimiert)
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $start + 4);

        if ($start > 1) {
            echo "... ";
        }

        for ($i = $start; $i <= $end; $i++) {
            echo "<a href='" . $urlBase . $i . "'" . ($i == $currentPage ? " class='current'" : "") . ">" . $i . "</a> ";
        }

        if ($end < $totalPages) {
            echo "... ";
        }

        // "Next" und "Last"
        if ($currentPage < $totalPages) {
            echo "<a href='" . $urlBase . ($currentPage + 1) . "'>Next</a> ";
            echo "<a href='" . $urlBase . $totalPages . "'>Last</a>";
        }

        echo "</div>";
    endif;
}else{

    $zufallsfilme = [];
    for ($i = 0; $i < 10; $i++) {
        $neuerFilm = $filmController->getZufallsFilm();
        $zufallsfilme[] = $neuerFilm;
        $zufallsfilme = array_unique($zufallsfilme, SORT_REGULAR); // SORT_REGULAR für Objekte/Arrays

        //Prüfen, ob ein Duplikat entfernt wurde
        while(count($zufallsfilme) <= $i) {
        $neuerFilm = $filmController->getZufallsFilm();
        $zufallsfilme[] = $neuerFilm;
        $zufallsfilme = array_unique($zufallsfilme, SORT_REGULAR);
        }
    }
    filmeAnzeigen($zufallsfilme);
}
?>
