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
        filmeAnzeign($filme);
    elseif(isset($filme['Error'])): // API Fehler anzeigen
        echo "<p>".htmlspecialchars($filme['Error'])."</p>";
    else :
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
    $totalresults = $filmController->countFilme($title); // IMMER aus der DB
    $totalPages = ceil($totalresults / 10);

    if ($totalresults > 0 && $totalPages > 1) :
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

}
?>

<div id="movie"> </div>