<?php
require_once __DIR__. '/../models/api.php';
$api = new mvc\Api();


$film = $api->getFilmDetailsInXML('tt0067310'); // TEST: Hier die Datei als XML schreiben

//NOTE: diese Datei ist ein XML-File


echo $film->asXML();


?>
<pre>
    <?php
    print_r($filme);
   ?>
</pre>
