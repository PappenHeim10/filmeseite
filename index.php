<?php
session_start(); // Die Session wird gestartet
require_once 'include/templates.php';
require_once 'include/datenbank.php';
require_once 'models/Filme.php'; // die nötigen dependencies werden eingebungen
require_once 'include/functions.php'; // die nötigen dependencies werden eingebungen
require_once 'models/user.php';//
require_once 'models/genres.php';
require_once 'models/Api.php';
require_once 'include/datenbank.php';
include_once 'include/helpers.php';
include_once 'klassenUndajax/UserController.php';
require_once 'klassenUndajax/FilmController.php';

$whitelist = ['multipleMovies','404seite','upload','impressum','agb','forum', 'Home','login','logout' , 'singleMovies',  'index', 'registrierung', 'start', 'liste']; // Die whiteliset wird inistialisiert

echo "<div id='success'>";
echo read_message(); // Nachrichten werden hier ausgegeben
echo "</div>"
?>

<div class="wrapper"> <!-- Hier beginnt der wrapper zum stylen -->
<?php

$api = new mvc\Api();

$header = new Header();
$nav = new Navigation(); 
#$userController = new mvc\ TODO: Diese Klasse schreiben
$userController = new mvc\UserController();

$header->render();// Komponenten wereden hier gerendert
$nav->render();

$action = isset($_GET['action']) ? $_GET['action'] : 'start'; // Der action parameter gibt an welcher view benutzt wird und ist Standard: start
$view = isset($_GET['view']) ? $_GET['view'] : 'start';// Hier wird der view festgelgt der auch erstmal Standart// start ist
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Hier wird die Seiten Position gespeichert
$title = isset($_GET['title']) ? trim($_GET['title']) : ''; // Hier wird der die eingabe formatiert und die $titel variable weitergegeben oder leer gelassen
$imdbId = isset($_GET['imdbID']) ? $_GET['imdbID'] : ''; 

$filmController = new mvc\FilmController();

switch ($action) {
    case 'liste':
        $view = 'liste';
        break;
    case 'singleMovies':
        $view = 'singleMovies';
        break;
    case 'registrierung':
        $view = 'registrierung';
        break;
    case 'login':
        $view = 'login';
        break;
    case 'logout':
        $view = 'logout';
        break;
    case 'upload':
        $view = 'upload';
        break;
    #case 'zeigeBenutzer':
    #    // Überprüfe, ob eine ID übergeben wurde
    #    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    #       $id = (int)$_GET['id']; // Stelle sicher, dass es eine Zahl ist.
    #       $userController->zeigeBenutzer($id);
    #    } else {
    #        // Fehlerbehandlung: Keine oder ungültige ID
    #        header('Location: 404.php'); // Oder Fehlerseite.
    #        exit;
    default:
        $view = 'start';
        break;
}
?>

<form action="" method="get"> <!-- Das Such Formular. Alle anfragen werden über $_GET geschickt -->
    <input type="hidden" name="page" value="1"><!-- Die such wird immer über die erste seite gestartet -->
    <input type="hidden" name="action" value="<?php echo htmlspecialchars($view);?>"> <!-- Die Aktion und damit der view werden nicht geändert --> 
    <label for="title">Titel Eingeben: </label>
    <input type="text" name="title" id="searchInput" onkeyup="showMovies(this.value)" placeholder="House" value="<?php echo htmlspecialchars(urldecode($title)); ?>">
    <input type="submit" value="Suchen"><!-- OPTIM: Die suche ist so eingeseelt das  sie auf der Start seite nicht Funktioniert-->
    <?php 
    if($view != 'start'){
        echo "<div id='livesearch'></div>";
    }
    ?>
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

        case'upload':
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
</div>

<?php
$foot = new Footer(); // die footer werden eingebunden
$foot->render();
?>

</body>
