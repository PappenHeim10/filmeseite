<?php
namespace mvc;
include_once '../models/api.php';
$api = new Api();
?>

<pre>
    <?php
    $url = 'http://www.omdbapi.com/?apikey=f9d69f9c&type=movie&s=Movie';
    $response = file_get_contents($url);
    $movie = json_decode($response);
    print_r($movie);
   ?>
</pre>


<pre>
    <?php
    $title = "love house";
    $result = $api->getFilmDetails($title);
    print_r($result);
   ?>
</pre>

<pre>
    <?php
    $title = "Road House";
    $view = "liste";
    $result = $api->getFilme($title, $view, );
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