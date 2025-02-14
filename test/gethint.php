<?php
$a[] = "Anna";
$a[] = "Brittany";
$a[] = "Cinderella";
$a[] = "Diana";
$a[] = "Eva";
$a[] = "Fiona";
$a[] = "Gunda";
$a[] = "Hege";
$a[] = "Inga";
$a[] = "Johanna";
$a[] = "Kitty";
$a[] = "Linda";
$a[] = "Nina";
$a[] = "Ophelia";
$a[] = "Petunia";
$a[] = "Amanda";
$a[] = "Raquel";
$a[] = "Cindy";
$a[] = "Doris";
$a[] = "Eve";
$a[] = "Evita";
$a[] = "Sunniva";
$a[] = "Tove";
$a[] = "Unni";
$a[] = "Violet";
$a[] = "Liza";
$a[] = "Elizabeth";
$a[] = "Ellen";
$a[] = "Wenche";
$a[] = "Vicky";

// get the q parameter from URL


$q = isset($_REQUEST["q"]) ? $_REQUEST["q"] : null; // Es wird der $p parameter aus der URK genommen wenn er vorhanden ist

$hint = null;

// Dieser block wird ausgeführt wenn $q gesetzt ist
if ($q !== null) {
  $q = strtolower($q); // DEr String wird auf kleinbuchstaben gesetzt
  $len=strlen($q); // Es wird die str leange genommen
  foreach($a as $name) { // Es wird durch jeden namen in der $a liste geangen
    if (stristr($q, substr($name, 0, $len))) { // Wenn der Buchstabe 
      if ($hint === null) {
        $hint = $name;
      } else {
        $hint .= ", $name";
      }
    }
  }
}

// Output "no suggestion" if no hint was found or output correct values
echo $hint === " " ? "no suggestion" : $hint;
?>