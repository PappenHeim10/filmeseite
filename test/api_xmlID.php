<?php
require_once __DIR__. '/../models/api.php';
$api = new mvc\Api();


$filme = $api->getFilmDetailsInXML('tt0067310');



?>
<pre>
    <?php
    print_r($filme);
   ?>
</pre>