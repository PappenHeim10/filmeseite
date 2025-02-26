<?php
require_once __DIR__. '/../models/api.php';
$api = new mvc\Api();

$filme = $api->getFilmeAsXML('love', 1); //NOTE: Das ist ein XML object

foreach ($filme->children() as $film) {//TEST: Ich bin mir nicht sicher was das ist
    echo $film->titel . '<br>';
}



?>
<pre>
    <?php
    print_r($filme); 
   ?>
</pre>
