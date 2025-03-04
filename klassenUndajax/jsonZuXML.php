<?php
function jsonToXml(mixed $json, ?SimpleXMLElement $parent = null, string $childName = 'item'): SimpleXMLElement
{
    // 1.  JSON dekodieren (falls es ein String ist)
    if (is_string($json)) {
        $json = json_decode($json, true); // true für assoziatives Array
        if ($json === null) {
            throw new \Exception("Ungültiges JSON: " . json_last_error_msg());
        }
    }

    // 2.  Initialisierung (Root-Element)
    if ($parent === null) {
        $parent = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root></root>'); //  Root-Element erstellen
    }

    // 3.  Iteriere durch die Daten (Array oder Objekt/Dictionary)
    if (is_array($json)) {
        foreach ($json as $key => $value) {
            // 3.1  Numerische Schlüssel (Array-Elemente)
            if (is_int($key)) {
                //  Verwende $childName (z.B. 'item') für Array-Elemente
                $child = $parent->addChild($childName); //  Kindelement hinzufügen
                jsonToXml($value, $child, $childName);   //  Rekursiver Aufruf
            } else {
                // 3.2  String-Schlüssel (Objekt-Eigenschaften)

                //  Sonderzeichen in Schlüsseln behandeln (ungültige Zeichen entfernen/ersetzen)
                $validKey = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key); //  Ersetze ungültige Zeichen
                if ($validKey != $key) {
                    trigger_error("Ungültiger XML-Elementname: '$key'. Ersetzt durch '$validKey'.", E_USER_WARNING);
                }

                //  Prüfe, ob der Wert ein Array/Objekt ist (verschachtelt)
                if (is_array($value)) {
                    $child = $parent->addChild($validKey);  //  Kindelement hinzufügen
                    jsonToXml($value, $child, $childName);   //  Rekursiver Aufruf für verschachtelte Daten
                } else {
                    //  Einfacher Wert -> direkt hinzufügen
                    //  htmlspecialchars() für sichere Ausgabe
                    $parent->addChild($validKey, htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'));
                }
            }
        }
    }
     else {
        // Skalarer Wert (String, Zahl, Boolean, null)
        $parent->{0} = htmlspecialchars((string)$json, ENT_QUOTES, 'UTF-8'); // Direkte Zuweisung
     }

    return $parent;
}



try {
    if (!is_dir($outputDir)) {
        if (!mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {  //WICHTIG:  0777: Lese-, Schreib- und Ausführungsrechte für alle
            throw new \RuntimeException(sprintf('Verzeichnis "%s" konnte nicht erstellt werden', $outputDir));
        }
    }

    $xmlFromFile = jsonToXml(file_get_contents($jsonFilePath)); // Inhalt direkt übergeben.
    echo $xmlFromFile->asXML();  // Ausgabe
    $xmlFromFile->asXML('datei_ausgabe.xml'); // Speichern
} catch (\Exception $e) {
    echo "Fehler (Datei): " . $e->getMessage();
}



?>