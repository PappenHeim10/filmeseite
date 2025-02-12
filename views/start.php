<?php
namespace mvc;
// Instanz der Klasse Api wird erstellt
$movies = $api->getFilme('Madagascar'); // Eine Bilddatenbank abfragen



?>

<pre>
    <?php
    print_r($movies); // Ausgeben der Filmdatenbank
 ?>
</pre>