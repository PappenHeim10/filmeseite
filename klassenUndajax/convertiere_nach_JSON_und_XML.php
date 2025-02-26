<?php
require_once __DIR__. '/FilmController.php';
require_once __DIR__. '/../models/Api.php';
require_once __DIR__. '/../models/Filme.php';
require_once __DIR__. '/../include/datenbank.php';


$json_dir = __DIR__. '/../admin/data/library_in_JSON'; // Das Verzeichniss in dem die JSON-Dateien liegen
$xml_dir = __DIR__. '/../admin/data/library_in_XML'; // Das XML Verzeichniss


$dateien = scandir($verzeichnis); // Alle Dateien im Verzeichniss einlesen

foreach($dateien as $datei){
    if ($datei != '.' && $datei != '..' && pathinfo($datei, PATHINFO_EXTENSION) === 'json') {
        $json = file_get_contents($json_dir . '/' . $datei); 
        $array = json_decode($json, true);
        $xml = new SimpleXMLElement('<Film/>');
        array_to_xml($array, $xml);
        $xml->asXML($xml_dir . '/' . pathinfo($datei, PATHINFO_FILENAME) . '.xml');
    }
}


?>
