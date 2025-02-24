<?php
require_once __DIR__. '/FilmController.php';
require_once __DIR__. '/../models/Api.php';
require_once __DIR__. '/../models/Filme.php';
require_once __DIR__. '/../include/datenbank.php';


$json_dir = __DIR__. '/../admin/data/library_in_JSON'; // Der Pfad zu dem Ordner, in dem die JSON-Dateien gespeichert werden sollen
if (!is_dir($json_dir)) { // Wenn der Ordner nicht existiert
    mkdir($json_dir, 0777, true); // Erstelle den Ordner und gib ihm di epassender Berechtigung
}


$xml_dir = __DIR__. '/../admin/data/library_in_XML'; // Der Pfad zu dem Ordner, in dem die XML-Dateien gespeichert werden sollen
if (!is_dir($xml_dir)) { // Wenn der Ordner nicht existiert
    mkdir($xml_dir, 0777, true); // Erstelle den Ordner
}


$filmController = new mvc\FilmController(); // Alle Filme aus der Datenbank werden Aufgerufen
$alleImdbIds = $filmController->getImdbIdListe(); // Alle Filme aus der Datenbank werden Aufgerufen
$api = new mvc\Api(); // Die API wird aufgerufen


foreach($alleImdbIds as $imdbId){ //FIXME: Es es muss gefragt werden pb dei datei schon existier und damit sie überscprungen woird und ich muss nach der RESPONSE[]
     $filmDatenJSON = $api->getFilmDetailsInJson($imdbId); // Die FilmeDaten werden an die API übergeben

    if ($filmDatenJSON === null) {
        write_error("Fehler beim Abrufen der Filmdaten für in JSON für IMDb-ID: " . $imdbId);
        continue; // Mit dem nächsten Film fortfahren
    }
    
    $jsonDaten = json_encode($filmDatenJSON); // Die FilmeDaten werden in JSON formatiert

    $json_datei = $json_dir . '/' . $imdbId . '.json';

    file_put_contents($json_dir . '/' . $imdbId . '.json', $jsonDaten); // Die JSON-Datei wird erstellt und in den Ordner gespeichert
}


foreach($titel_Liste as $imdbId){ // DO!: Den Herausfinden was ichmit einer XML Datei machen kann.
    $filmDatenXML = $api->getFilmDetailsInXml($imdbId); // Die FilmeDaten werden an die API übergeben


 
    if ($filmDatenXML instanceof SimpleXMLElement) { // Überprüfen, ob $filme ein SimpleXMLElement ist
        foreach ($filme->Movie as $film) { // Assuming "Movie" is the element name
            $imdbId = $film->imdbID; // Assuming "imdbID" is the element containing the IMDb ID
            $xml_datei = $xml_dir . '/' . $imdbId . '.xml';
    
            // XML-Dokument erstellen und formatieren (optional, aber empfohlen)
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false; // Whitespace entfernen (optional)
            $dom->formatOutput = true; // Formatierung aktivieren (optional)
    
            // Wurzelknoten importieren und hinzufügen
            $importedNode = $dom->importNode($film, true); // Importiere den SimpleXMLElement-Knoten
            $dom->appendChild($importedNode);
    
    
            $dom->save($xml_datei);
        }
    } else {
        // Fehlerbehandlung, falls $filme kein SimpleXMLElement ist
        write_error("Fehler: Keine XML-Daten erhalten.");
    }

}

?>
