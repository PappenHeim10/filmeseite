<?php
namespace mvc;

require_once __DIR__ . '/FilmController.php'; // Pfade anpassen!
require_once __DIR__ . '/../models/Api.php'; // Pfade anpassen!
require_once __DIR__ . '/../models/Filme.php'; // Pfade anpassen!
require_once __DIR__ . '/../include/datenbank.php'; // Pfade anpassen!
require_once __DIR__ . '/../include/functions.php';

const MAX_FILME = 300;  // Maximale Anzahl der Filme, die heruntergeladen werden sollen
const OUTPUT_DIR = __DIR__ . '/json_files/'; //  Verzeichnis für die JSON-Dateien (ABSOLUTER Pfad)


function filmeHerunterladenUndSpeichern(string $suchbegriff, int $maxFilme = MAX_FILME, string $outputDir = OUTPUT_DIR) {

$api = new Api();
$filmController = new FilmController();
 // Stelle sicher, dass das Ausgabeverzeichnis existiert
if (!is_dir($outputDir)) {
    if (!mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {  //WICHTIG:  0777: Lese-, Schreib- und Ausführungsrechte für alle
        throw new \RuntimeException(sprintf('Verzeichnis "%s" konnte nicht erstellt werden', $outputDir));
    }
}
 // Hole vorhandene IMDb-IDs aus der Datenbank
$vorhandeneImdbIds = $filmController->getImdbIdListe(); //  Methode im FilmController (siehe unten)
$vorhandeneImdbIds = array_flip($vorhandeneImdbIds); //  Schlüssel und Werte tauschen für schnellere Suche


$geladen = 0;
$seite = 1;
$totalPages = 1; // Initialisieren, um die Schleife zu starten


  do {
    $filmliste = $api->getFilme($suchbegriff, $seite);

    if ($filmliste && isset($filmliste['Search']) && is_array($filmliste['Search'])) {
        $totalresults = (int)$filmliste['totalResults'];
        $totalPages = ceil($totalresults/10);

        foreach ($filmliste['Search'] as $film) {
            if ($geladen >= $maxFilme) {
                break 2; // Beende beide Schleifen
            }

            $imdbId = $film['imdbID'];

            if (isset($vorhandeneImdbIds[$imdbId])) { //  $vorhandeneImdbIds ist jetzt ein assoziatives Array
                //echo "Film mit IMDb-ID '$imdbId' existiert bereits. Überspringe.<br>";
                continue; // Überspringe diesen Film, da er schon existiert
            }
            // Film Details abrufen.
            $filmdetails = $api->getFilmDetailsInJson($imdbId);

            if ($filmdetails && $filmdetails['Response'] === 'True') {
                $dateiname = $outputDir . $imdbId . '.json';
                $json = json_encode($filmdetails, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); // Schön formatieren und Unicode nicht escapen

                if ($json === false)
                {
                     error_log("Fehler beim JSON-Kodieren von Film $imdbId: " . json_last_error_msg());
                     write_error("Fehler beim JSON-Kodieren von Film $imdbId: " . json_last_error_msg()); // Schreibe Fehler
                     continue; // Überspringe diesen Film
                }
                if (file_put_contents($dateiname, $json) === false)
                {
                    error_log("Fehler beim Speichern der Datei '$dateiname'");
                    write_error("Fehler beim Speichern der Datei '$dateiname'");
                     continue;
                }
                // in die Datenbank speichern
                $filmController->filmeModel->setDaten($filmdetails);
                if (!$filmController->filmeModel->insert())
                {
                    error_log("Fehler beim Einfügen des Films in die Datenbank: " . print_r($filmdetails,true));
                    write_error("Fehler beim Einfügen des Films mit der ID $imdbId in die Datenbank");
                }
                $geladen++;
                echo "Film '$imdbId' gespeichert.<br>"; // Erfolgsmeldung (optional)
            } else {
                error_log("Fehler beim Abrufen von Film-Details für IMDb-ID '$imdbId': " . print_r($filmdetails, true));
                write_error("Fehler beim Abrufen von Film-Details für IMDb-ID '$imdbId'"); // Schreibe Fehler
            }
        }
    }
    elseif(isset($filmliste['Error']))
    {
        error_log("API-Fehler: " . $filmliste['Error']);
        write_error("API-Fehler: " . $filmliste['Error']);
        break;
    }
    else
    {
        // Keine Ergebnisse (mehr) von der API
        break;
    }
     $seite++;
} while ($seite <= $totalPages && $geladen < $maxFilme); //  Bedingung anpassen
echo "Insgesamt $geladen Filme heruntergeladen und gespeichert.<br>";
}


try {
    filmeHerunterladenUndSpeichern('Love');
} catch (\Exception $e) {
    echo "Ein Fehler ist aufgetreten: " . htmlspecialchars($e->getMessage()); //  Fehler ausgeben (für den Benutzer)
}

?>