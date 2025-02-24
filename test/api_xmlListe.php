<?php
require_once __DIR__. '/../models/api.php';
$api = new mvc\Api();


$filme = $api->getFilmeAsXML('love', 1);



?>
<pre>
    <?php
    print_r($filme);
   ?>
</pre>