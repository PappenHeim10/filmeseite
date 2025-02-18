<?php
function filmeAnzeign($filme):void {

    if (empty($filme)) {
        echo "<p>Keine Filme gefunden.</p>"; // Wenn es Werder filme in der Datenbank noch welche von der API kommne
        return;
    }

    foreach ($filme as $film) : // Die Filme werden hier angezeigt
        $title = htmlspecialchars($film['titel'] ?? 'N/A');
        $poster = htmlspecialchars($film['poster'] ?? '#');
        $year = htmlspecialchars($film['erscheinungs_jahr'] ?? 'N/A');
        $imdbID = htmlspecialchars($film['imdbid'] ?? '');

        // die korrekten (kleingeschriebenen) Schlüssel verwenden!
        echo "<div class='movie'>";
        echo "<h2><a href='?action=singleMovies&imdbID=" . urlencode($imdbID) . "&view=singleMovies&'>" . htmlspecialchars($title) . "</a></h2>";
        echo "<img src='" . htmlspecialchars($poster) . "' alt='" . htmlspecialchars($title) . "'>";
        echo "<p>Erscheinungs Jahr:  " . htmlspecialchars($year) . "</p>";
        echo "<p>imdbID: " . htmlspecialchars($imdbID) . "</p>";
        echo "</div>";
	endforeach;
}
function showFilmDetails($film):void{
	if(empty($film)){
		echo "<p>Keine Filmdaten vorhanden.</p>";
        return;
	}
	echo "<div class='film'>";
	
}

function write_error($message):void {
	$logFile = __DIR__ . '/../admin/data/error.log';// Protokolldatei im übergeordneten Verzeichnis
	$timestamp = date('d-m-Y H:i:s');
	$logMessage = "[$timestamp] $message\n";
	
	file_put_contents($logFile, $logMessage);
}

function hinweise_log($message):void{
	$hinweise = __DIR__ . '/../admin/data/hinweise.log';
	$timestamp = date('d-m-Y H:i:s');
	$logMessage = "[$timestamp] $message\n";

	file_put_contents($hinweise, $logMessage, FILE_APPEND | LOCK_EX);
}

function read_error()
{
	if(isset($_SESSION['error']))
	{
		$error = $_SESSION['error'];
		unset($_SESSION['error']);
		return $error;
	}
}

function read_message()
{
	if(isset($_SESSION['message']))
	{
		$message = $_SESSION['message'];
		unset($_SESSION['message']);
		return $message;
	}
}
function write_message($param)
{
	$_SESSION['message'] = $param;
}



function autoloadNS(string $param) :void 
{
	$arr = explode("\\", $param);
	$className = $arr[count($arr) - 1];
	
	if (file_exists("models/$className.php")) 
	{
		require_once "models/$className.php";
	}
	if(file_exists("kassenUndajax/$className.php"))
	{
		require_once "kassenUndajax/$className.php";
	}
}

spl_autoload_register('autoloadNS');
?>