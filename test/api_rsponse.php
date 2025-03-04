<?php
namespace mvc;
include_once '../models/api.php';


$api = new Api();

$filme = $api->getFilme('Knight', 1); // NOTE: Das ist ein JSON object
$filePath = __DIR__ . '/../admin/data/library_in_JSON';

$data = json_encode($filme);
try{
    file_put_contents( $filePath, $data);

}catch(\Exception $e){
    write_error('Fehler ist aufgetreten: ' . $e->getMessage());
}

?>
<pre>
    <?php
    print_r($filme);
   ?>
</pre>

