<?php
require_once __DIR__. '/../models/api.php';
$api = new mvc\Api();


$film = $api->getFilmDetailsInXML('tt9248940'); // TEST: Hier die Datei als XML schreiben

//NOTE: diese Datei ist ein XML-File


$film->asXML();
$myFile = fopen("test.xml", "w") or die("Unable to open file!");
fwrite($myFile, $film->asXML());
fclose($myFile);


echo "test";


?>
<pre>
    <?php

   ?>
</pre>
