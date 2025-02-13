<?php
namespace mvc;
?>
<div class="liste"> 
<?php
// Es werden nach Filmen in der Datenbank gesucht
$filme = $filmController->getFilmeAusDerDatenbank($title,$page);


// Wenn keine Filme gefunden werden ODER die Seite größer als 1 ist UND filme gefunden wurden
if ($filme === false || ($page > 1 && $filme !== false)) {
    $apiFilme = $api->getFilme($title, $page);

    // Speichere NUR, wenn von API abgerufen wurde.
    if (is_array($apiFilme) && isset($apiFilme['Search'])) {
        $filmController->filmeMasseneinfuegen($title); // Ruft die Methode jedesmal auf wenn die API einen Antwort gibt
        $filme = $filmController->getFilmeAusDerDatenbank($title, $page); // Lade die Filme neu aus der Datenbank
    }
}

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


$totalresults = $filmController->filmeModel->countFilme($title); // IMMER aus der DB
$totalPages = ceil($totalresults / 10);

// Pagination 
if ($totalresults > 0 && $totalPages > 1) : // Wenn es mehr als 0 seite m gibt
    $currentPage = $page; //Die jetzifge seite wird in dieser Variable gespeichert
    echo "<div class='pagination'>";// Die div Pagnation wird geöffnet

    $urlBase = "?action=liste&title=" . urlencode($title) . "&page=";// urlBasis wird erstellt

    // "First" und "Previous"
    if ($currentPage > 1) {
        echo "<a href='" . $urlBase . "1'>First</a> ";
        echo "<a href='" . $urlBase . ($currentPage - 1) . "'>Previous</a> ";
    }

    // Seitenzahlen
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
?>
