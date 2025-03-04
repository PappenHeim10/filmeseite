<?php
function filmeAnzeigen($filme):void {
	$i = 0;
    if (empty($filme)) {
        echo "<p>Keine Filme gefunden.</p>"; // Wenn es Werder filme in der Datenbank noch welche von der API kommne
        return;
    }

    foreach ($filme as $film) : // Die Filme werden hier angezeigt
		$i++; // Zähler um die Anzeigeposition zu bestimmen
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



function einzeilAnzeigen($film):string{
	$output = "<div class='einzelFilm'>"; // ein div zum Stylen
	$output .= "<div>"; // ein div zum Stylen

	$output .= "<h2>" . htmlspecialchars($film['titel']) . "</h2>"; // titel

	if (!empty($film['poster']) || $film['poster'] !== 'N/A' || imagecreatefromjpeg( $film['poster']) !== false ) { // das bild
		$output .= "<img src='" . htmlspecialchars($film['poster']) . "' alt='" . htmlspecialchars($film['titel']) . "'>"; // Das bild bekommt einen Alias
	} else {
		$output .= "<img src='../bilder/movie.png' alt='platzhalter'>"; // Oder ein Standard-Platzhalterbild
	}

	// Erscheinungsjahr
	if (!empty($film['erscheinungs_jahr'])) {
		$output .= "<p>Erscheinungs Jahr: " . htmlspecialchars($film['erscheinungs_jahr']) . "</p>";
	}

	$output .= "</div>";
	$output .= "<div>";

	foreach($film as $key => $value) {
		if (!empty($value) && $value!== 'N/A' && $key !== 'poster' && $key !== 'id') {
			$output.= "<p>$key: ". htmlspecialchars($value)."</p>";
		}
	}

	$output .= "</div>";
	$output .= "</div>";

	return $output ;
}

function write_error($message):void {
	$logFile = __DIR__ . '/../admin/data/error.log';// Protokolldatei im übergeordneten Verzeichnis
	$timestamp = date('d-m-Y H:i:s');
	$logMessage = "[$timestamp] $message\n";
	
	file_put_contents($logFile, $logMessage, FILE_APPEND);
}

function hinweis_log($message):void{
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





