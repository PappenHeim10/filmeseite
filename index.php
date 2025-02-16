<?php
session_start(); // Die Session wird gestartet
require_once 'include/datenbank.php';
require_once 'models/Filme.php'; // die nötigen dependencies werden eingebungen
include_once 'include/functions.php'; // die nötigen dependencies werden eingebungen
include_once 'include/templates.php';
include_once 'models/user.php';//
require_once 'models/genres.php';
require_once 'models/Api.php';
require_once 'klassen/FilmController.php';




$whitelist = ['multipleMovies','404seite','impressum','agb','forum', 'Home','login','logout' , 'singleMovies',  'index', 'registrierung', 'start', 'liste']; // Die whiteliset wird inistialisiert


echo "<div id='success'>";
echo read_message(); // Nachrichten werden hier ausgegeben
echo "</div>"
?>

<div class="wrapper"> <!-- Hier beginnt der wrapper zum stylen -->
<?php

$header = new Header();
$nav = new Navigation(); 
$filmController = new mvc\FilmController();// Komponenten werden initialisiert


$header->render();// Komponenten wereden hier gerendert
$nav->render();

$action = isset($_GET['action']) ? $_GET['action'] : 'start'; // Der action parameter gibt an welcher view benutzt wird und ist Standard: start
$view = isset($_GET['view']) ? $_GET['view'] : 'start';// Hier wird der view festgelgt der auch erstmal Standart// start ist
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Hier wird die Seiten Position gespeichert
$title = isset($_GET['title']) ? trim($_GET['title']) : ''; // Hier wird der die eingabe formatiert und die $titel variable weitergegeben oder leer gelassen
$imdbId = isset($_GET['imdbID']) ? $_GET['imdbID'] : ''; 



switch ($action) { // Der acrtin
    case 'liste':
        $view = 'liste';
        break;
    case 'singleMovies':
        $view = 'singleMovies';
        if (!empty($title)) { 
            $imdbId = $api->getFilmIDNachTitel($title);
            if($imdbId){
                $movie = $api->getFilmDetails($imdbId);
            }
        }
        break;
    case 'registrierung':
        $view = 'registrierung';
        break;
    default:
        $view = 'start';
        break;
}

?>

<form action="" method="get"> <!-- Das Such Formular. Alle anfragen werden über $_GET geschickt -->
    <input type="hidden" name="page" value="1"><!-- Die such wird immer über die erste seite gestartet -->
    <input type="hidden" name="action" value="<?php echo htmlspecialchars($view);?>"> <!-- Die Aktion und damit der view werden nicht geändert --> 
    <label for="title">Titel Eingeben: </label>
    <input type="text" name="title" id="searchInput" onkeyup="showMovies(this.value)" placeholder="Titanic" value="<?php echo htmlspecialchars(urldecode($title)); ?>">
    <input type="submit" value="Suchen"> 
</form>

<div class="main"> 

<?php
if(in_array($view, $whitelist)) // Heystack. Wenn der view in der Whitelist ist wird der Ihnhalt ausgeführt
{
    switch ($view) {
        case'liste':
            require_once "views/$view.php";
            break;

        case'singleMovies':
            require_once "views/$view.php";
            break;

        case'registrierung':
            require_once "views/$view.php";
            break;

        case'start':
            require_once "views/$view.php";
            break;

        case 'forum':
            require_once "views/$view.php";
            break;

        case'login':
            require_once "views/$view.php";
            break;

        case 'agb':
            require_once "views/$view.php";
            break;

        case 'impressum':
            require_once "views/$view.php";
            break;

        case'logout':
            session_destroy();
            header('Location: index.php');
            break;

        default:
            require_once "views/404seite.php";
            break;
    }
}else{
    require_once "views/404seite.php";
}
?>
</div>
<?php
$foot = new Footer(); // die footer werden eingebunden
$foot->render();
?>
</div>