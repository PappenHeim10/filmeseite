<?php
require_once __DIR__. '/../models/api.php';
$api = new mvc\Api();


$filmInJson = $api->getFilmDetailsInJson('tt9248940'); // TEST: Hier die Datei als XML schreiben

//NOTE: diese Datei ist ein XML-File


$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;
$root = $xml->createElement('Film');
$xml->appendChild($root);


foreach($filmInJson as $key => $value){
    if(is_array($value)){
        $subNode = $xml->createElement($key);
        $root->appendChild($subNode);
    }
}

?>
<pre>
    <?php

   ?>
</pre>
