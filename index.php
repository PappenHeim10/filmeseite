<?php
namespace mvc;
session_start(); // Die Session wird gestartet
include_once 'include/datenbank.php';

include_once 'models/filme.php'; // die nötigen dependencies werden eingebungen
include_once 'include/functions.php'; // die nötigen dependencies werden eingebungen
include_once 'include/templates.php';



$whitelist = ['multipleMovies', 'Home',  'singleMovies',  'index', 'registrierung', 'start', 'liste', 'einzel']; // Die whiteliset wird inistialisiert
$apiKey = "f9d69f9c"; // Der API key wird inistialisiert

?>

<div id="error">
<?php
echo read_error(); // Error Felder 
?>
</div>
<div class="wrapper">

<?php
$header = new Header();
$header->render();
$nav = new Navigation();
$nav->render(); // Der Header und die Navigation werden gerndert
?>

<form action="" method="get">
    <input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? $_GET['page'] : 1; ?>">  </input>
    <label for="titel">Titel Eingeben: </label>
    <input type="text" name="title" placeholder="Titanic">
    <input type="submit" value="Suchen">
</form>


<h1>Startseite</h1>
<div class="main"> 

<?php

$api = new Api();

$page = isset($_GET['page']) ? $_GET['page'] : 1; 

$view = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'start';

$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : 'A';

if(in_array($view, $whitelist))
{
    switch ($view) {
        case'liste':
            
            $movies = $api->getMovies($title, $view, $page);
            require_once "views/$view.php";
            break;

        case'singleMovies':
            $movie = $api->getMovies($title, $view);
            require_once "views/$view.php";
            break;
        case'registrierung':
            require_once "views/$view.php";
            break;
        case'start':
            require_once "views/$view.php";
            break;

        case'home':
            require_once "views/$view.php";
            break;
        default:
            break;
    }
    if($view =='start')
	{
		require_once "views/start.php";
	}
}else{
    write_error('Seite nicht gefunden.');
    require_once "index.php";
}

?>
</div>
<?php
$foot = new Footer(); // die footer werden eingebunden
$foot->render();
?>
</div>