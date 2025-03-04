<?php
namespace mvc; // Namespaces hinzufügen

// --- Konfiguration ---
$inputDir = __DIR__ . '/../admin/data/library_in_JSON/'; //  Eingabeverzeichnis (ABSOLUTER Pfad!)
$outputDir = __DIR__ . '/../admin/data/library_in_XML/'; // Ausgabeverzeichnis (ABSOLUTER Pfad!)

// --- Hilfsfunktionen (könnten auch in functions.php ausgelagert werden) ---

function jsonToXml(mixed $json, ?\SimpleXMLElement $parent = null, string $childName = 'item'): \SimpleXMLElement
{
    // 1. JSON dekodieren (falls es ein String ist)
   if (is_string($json)) {
        $json = json_decode($json, true);
        if ($json === null) {
            throw new \Exception("Ungültiges JSON: " . json_last_error_msg());
        }
    }

    // 2. Initialisierung (Root-Element)
    if ($parent === null) {
        $parent = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root></root>');
    }

    // 3. Iteriere durch die Daten (Array oder Objekt)
    if (is_array($json)) {
        foreach ($json as $key => $value) {
            if (is_int($key)) {
                $child = $parent->addChild($childName);
                jsonToXml($value, $child, $childName);   // Rekursion
            } else {
                // 3.2 String-Schlüssel -> Objekt-Eigenschaften

                // Ungültige Zeichen in Schlüsseln behandeln
                $validKey = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key);
                if ($validKey != $key) {
                    trigger_error("Ungültiger XML-Elementname: '$key'. Ersetzt durch '$validKey'.", E_USER_WARNING);
                }

                if (is_array($value)) {
                    $child = $parent->addChild($validKey);
                    jsonToXml($value, $child, $childName);   // Rekursion
                } else {
                    // Einfacher Wert -> direkt hinzufügen (mit Escaping)
                    $parent->addChild($validKey, htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'));
                }
            }
        }
    }
    else {
        // Skalarer Wert
        $parent->{0} = htmlspecialchars((string)$json, ENT_QUOTES, 'UTF-8');
    }
    return $parent;
}

// --- Hauptlogik ---

// 1.  Eingabe- und Ausgabeverzeichnisse prüfen
if (!is_dir($inputDir)) {
    die("Eingabeverzeichnis '$inputDir' existiert nicht."); //  ODER: throw new Exception(...);
}
if (!is_readable($inputDir)) {
    die("Eingabeverzeichnis '$inputDir' ist nicht lesbar."); // Oder Exception
}

// Ausgabeverzeichnis erstellen, falls es nicht existiert
if (!is_dir($outputDir)) {
    if (!mkdir($outputDir, 0777, true) && !is_dir($outputDir)) { //  0777: Lese-, Schreib- und Ausführungsrechte für alle (ggf. anpassen)
        die(sprintf('Ausgabeverzeichnis "%s" konnte nicht erstellt werden', $outputDir)); // Oder Exception
    }
}
if (!is_writable($outputDir)) {
    die("Ausgabeverzeichnis '$outputDir' ist nicht beschreibbar."); // Oder Exception
}

// 2.  Durch das Eingabeverzeichnis iterieren
$dirHandle = opendir($inputDir);
if ($dirHandle === false) {
    die("Kann Eingabeverzeichnis '$inputDir' nicht öffnen."); //Oder exception
}


$erfolgreicheKonvertierungen = 0;
$fehlerhafteDateien = [];

while (($datei = readdir($dirHandle)) !== false) {  //  Liest *nächsten* Eintrag.  false, wenn keine weiteren Einträge
    if ($datei === '.' || $datei === '..') {
        continue;  //  Aktuelles und übergeordnetes Verzeichnis überspringen
    }

    $inputFilePath = $inputDir . $datei;

    //  Prüfe, ob es eine Datei und eine JSON-Datei ist
    if (is_file($inputFilePath) && strtolower(pathinfo($inputFilePath, PATHINFO_EXTENSION)) === 'json') {

        $outputFilePath = $outputDir . pathinfo($datei, PATHINFO_FILENAME) . '.xml'; //  .xml statt .json

        try {
            // JSON einlesen
            $jsonContent = file_get_contents($inputFilePath);
            if ($jsonContent === false) {
                throw new \Exception("Fehler beim Lesen von '$inputFilePath'");
            }

            // JSON in XML umwandeln
            $xml = jsonToXml($jsonContent);

            // XML in Datei speichern
            if ($xml->asXML($outputFilePath) === false) { // asXML gibt false zurück bei Fehler
                throw new \Exception("Fehler beim Schreiben der XML-Datei '$outputFilePath'");
            }

            $erfolgreicheKonvertierungen++;

        } catch (\Exception $e) {
            $fehlerhafteDateien[] = $datei;
            error_log("Fehler bei der Verarbeitung von '$datei': " . $e->getMessage()); //  Ins Error-Log schreiben
            echo "Fehler bei der Verarbeitung von '$datei': " . htmlspecialchars($e->getMessage()) . "<br>"; //  Ausgabe auf dem Bildschirm (für Entwicklung)
        }
    }
}
closedir($dirHandle); // Verzeichnis-Handle schließen

// 3.  Ergebnis ausgeben (oder in Datei schreiben/loggen)
echo "Konvertierung abgeschlossen.<br>";
echo "Erfolgreich konvertiert: $erfolgreicheKonvertierungen Dateien.<br>";
if (!empty($fehlerhafteDateien)) {
    echo "Fehlerhafte Dateien:<br>";
    foreach ($fehlerhafteDateien as $datei) {
        echo htmlspecialchars($datei) . "<br>"; // Sichere Ausgabe
    }
}

?>