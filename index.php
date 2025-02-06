<?php
include_once 'include/datenbank.php';
include_once 'include/functions.php'; // die nötigen dependencies werden eingebungen
session_start(); // Die Session wird gestartet
$whitelist = ['multipleMovies', 'Home',  'singleMovies',  'index']; // Die whiteliset wird inistialisiert
$apiKey = "f9d69f9c"; // Der API key wird inistialisiert
use Filmesite\Models\Api; // Die namespace für API wird erstellt
include_once 'include/templates.php';



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

<form action="" method="get"> <!-- Die Suchleiste --> 
    <label for="titel">Titel Eingeben: </label>
    <input type="text" name="title" placeholder="Titanic"> <input type="submit" value="Suchen">
</form>

<div class="main"> 


<?php
$api = new Api(); // Die API wird initialisert

if(isset($_GET['title']) || isset($_SESSION['title'])){ // Wenn die Titel get oder session existiert
    $title = isset($_GET['title']) ? $_GET['title'] : $_SESSION['title']; // wird sie als Titel variable genommen
    $view = isset($_REQUEST['action']) ? $_REQUEST['action'] : ''; // Je nachdem was ich als action nehme sie wird als view genommen
    $_SESSION['title'] = $title; // die sessio variable wird gesetzt oder ersetzt

    if(in_array($view, $whitelist))
    {
        switch ($view) {
            case'multipleMovies':
                $movies = $api->getMovies($title , $view);
                require_once "views/$view.php";
                break;

            case'singleMovies':
                $movie = $api->getMovies($title, $view);
                require_once "views/$view.php";
                break;
    
            case'home':
                require_once "views/$view.php";
                break;
            default:
                break;
        }
    }

}

?>
</div>
<?php
$foot = new Footer(); // die footer werden eingebunden
$foot->render();
?>
</div>