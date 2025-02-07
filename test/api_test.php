<?php
namespace mvc;
include_once '../models/api.php';
$api = new Api();
?>

<pre>
    <?php
    $title = "Knight";
    $view = "multipleMovies";
    print_r($result);
   ?>
</pre>
<pre>
    <?php
    
    
    $title = "love house";
    $view = "singleMovies";
    $result = $api->getMovies($title, $view);
    print_r($result);
   ?>
</pre>

<pre>
    <?php
    $title = "Road House";
    $view = "liste";
    $result = $api->getMovies($title, $view, );
    print_r($result);
   ?>
</pre>

<pre>
    <?php
$url = 'http://www.omdbapi.com/?apikey=f9d69f9c&s=game';

$response = file_get_contents($url);
$movies = json_decode($response);
?>
   
</pre>

<pre>
    <?php echo print_r($response); ?>
    <?php echo print_r($movies); ?>
</pre>