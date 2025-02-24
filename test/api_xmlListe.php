<?php
require_once __DIR__. '/../models/api.php';
$api = new mvc\Api();

$filme = $api->getFilmeAsXML('love', 1); //NOTE: Das ist ein XML object

$xml = simplexml_load_string($filme); // NOTE: simplexml_load_string ist eine Methode umwandelt

?>
<pre>
    <?php
    print_r($filme);
   ?>
</pre>