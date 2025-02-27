<?php

$xml  =new DOMDocument();
$xml->load("filmLinks.xml");

$link = $xml->getElementsByTagName('link');

// NOTE: Hier wird der q parameter aus der funktion geholt
$q = $_GET['q'] ?? '';
$q = trim($q);
$q = strtolower($q);
$q = htmlspecialchars($q);

$maxSuggestions = 5;
$hint = [];
$count = 0;

if(strlen($q) > 0){ // WICHTIG: Prüft ob der String länger als 0 ist
  for($i = 0; $i < ($link->length); $i++){ // Sollange es noch link elemente gibt
    // NOTE: Das Link-Element holt aus dem Tag Titel das erste Item
    $titelNode = $link->item($i)->getElementsByTagName('titel')->item(0); //
    $urlNode = $link->item($i)->getElementsByTagName('url')->item(0);


    if($titelNode && $urlNode){
      $titel = $titelNode->nodeValue;
      $url = $urlNode->nodeValue;

      if(strpos(strtolower($titel), $q) === 0){
        $hint[] = [  //  Array von Objekten erstellen
          'titel' => htmlspecialchars($titel, ENT_QUOTES, 'UTF-8'), //  htmlspecialchars HIER!
          'url'   => htmlspecialchars($url, ENT_QUOTES, 'UTF-8')    //  htmlspecialchars HIER!
        ];

        $count++;
        
        if ($count >= $maxSuggestions) {
          break; // Schleife verlassen, wenn das Limit erreicht ist
        }
      }
    }
  }
}


header('Content-Type: application/json'); //  JSON-Header setzen!
echo json_encode($hint); //  JSON-kodieren und ausgeben
exit; // Wichtig, um unerwünschte Ausgaben zu verhindern

?>