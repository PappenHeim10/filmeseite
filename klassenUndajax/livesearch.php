<?php

$xml  =new DOMDocument();

$xml->load("filmLinks.xml");

$link = $xml->getElementsByTagName('link');

// NOTE: Hier wird der q parameter aus der funktion geholt
$q = $_GET['q'] ?? ''; // Wenn der q parameter nicht existiert wird ein leerer string benutzt
$q = trim($q); // q wird getrimmt 
$q = strtolower($q); // und in kleinbuchstaben gesetzt


if (strlen($q)>0) { // Wenn die länge des q parameters größer als 0 ist
  $hint=""; // hint wird initialieseirt

  for($i=0; $i < ($link->length); $i++) { 

    $titel = $link->item($i)->getElementsByTagName('titel');
    $url = $link->item($i)->getElementsByTagName('url');

    if ($titel->item(0)->nodeType==1) {
      //find a link matching the search text
      if (stristr($titel->item(0)->childNodes->item(0)->nodeValue,$q)) {
        if ($hint=="") {
          $hint="<a href='" .
          $z->item(0)->childNodes->item(0)->nodeValue .
          "' target='_blank'>" .
          $y->item(0)->childNodes->item(0)->nodeValue . "</a>";
        } else {
          $hint=$hint . "<br /><a href='" .
          $z->item(0)->childNodes->item(0)->nodeValue .
          "' target='_blank'>" .
          $y->item(0)->childNodes->item(0)->nodeValue . "</a>";
        }
      }
    }
  }
}

// Set output to "no suggestion" if no hint was found
// or to the correct values
if ($hint=="") {
  $response="no suggestion";
} else {
  $response=$hint;
}

//output the response
echo $response;
?>