<?php

require_once __DIR__. '/../models/Api.php';

$datei = file_get_contents('test.json');
$jsonData = json_decode($datei, true);

echo '<pre> ' . print_r($jsonData, true) . '</pre>';


$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true;
$root = $xml->createElement('Filme');
$xml->appendChild($root);


foreach($jsonData as $key => $value){
    if(is_array($value)){
        $subNode = $xml->createElement($key);
        $root->appendChild($subNode);
        foreach($value as $subKey => $subValue){
            $subNode->appendChild(($xml->createElement($subKey,htmlspecialchars($subValue))));
        }
    }else{
        $root->appendChild($xml->createElement($key, htmlspecialchars($value)));
    }
}

$xml->save('test2.xml');



/*
foreach($dateien as $datei){
    if ($datei != '.' && $datei != '..' && pathinfo($datei, PATHINFO_EXTENSION) === 'json') {
        $jsonString = file_get_contents($json_dir . '/' . $datei);
        $jsonData = json_decode($jsonString, true);

        if ($jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            write_error("Fehler beim Dekodieren der JSON-Datei: " . $datei . ". Fehler: " . json_last_error_msg());
            continue; // Fahre mit der nächsten Datei fort
        }


        $xml = new DOMDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        $root = $xml->createElement('Film');
        $xml->appendChild($root);


        foreach($jsonData as $key => $value){
            if(is_array($value)){
                $subNode = $xml->createElement($key);
                $root->appendChild($subNode);
                foreach($value as $subKey => $subValue){
                    $subNode->appendChild(($xml->createElement($subKey,htmlspecialchars($subValue))));
                }
            }
            else{
                $root->appendChild(($xml->createElement($key, htmlspecialchars($value))));
            }
        }

        $xmlDateiName = $xml_dir . '/' . pathinfo($datei, PATHINFO_FILENAME) . '.xml';
        $xml->save($xmlDateiName);

    }
}

*/

?>