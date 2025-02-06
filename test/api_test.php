<?php
namespace mvc;
include_once '../models/api.php';
?>

<pre>
    <?php
    $api = new Api();
    
    $title = "Knight";
    $view = "singleMovies";
    $result = $api->getMovies($title, $view);
    print_r($result);
   ?>
</pre>

<pre>
    <?php
    $title = "Knight";
    $view = "multipleMovies";
    $result = $api->getMovies($title, $view);
    print_r($result);
   ?>
</pre>