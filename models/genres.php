<?php
namespace mvc;
require_once 'include/namespace.php';
require_once 'include/datenbank.php';
class Genres extends Datenbank // Das ist das Model für das genre
{
	// attributes
	use GetterSetter;
	private $id; // Die id 
	private $genre;// und der Name des Genres werden hier angegeben

	// konstruktor führt die funktion setDaten auf
	function __construct(Array $daten = []) // Der Konstruktor akzeptiert ein Array als parameter
	{
		parent::__construct();
		$this->setDaten($daten); // Die Daten werden durch die setData methode zugewiesen
	}
	

	// setDaten führt die set-Methoden der Klasse auf, die übergebenen Werte entsprechendem Attributen setzen
    public function setDaten(array $daten)
    {
        if ($daten) {
            foreach ($daten as $key => $value) {
                $propertyName = strtolower($key); // Konvertiere den Schlüssel in Kleinbuchstaben

                // Überprüfe, ob die Eigenschaft existiert, bevor du sie setzt.
                if (property_exists($this, $propertyName)) {
                     $this->$propertyName = $value; // Dynamisch die Eigenschaft setzen
                }
            }
        }
    }

	// Methods
	public function insert(){
		try{
			$sql = "INSERT INTO genres (genre) VALUES (:genre)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':genre', $this->genre, \PDO::PARAM_STR);
            $stmt->execute();

			return $this->db->lastInsertId();
		}
		catch(\PDOException $e){
			write_error("Fehler beim Einfügen: " . $e->getMessage());
            echo $e->getMessage();
            return false;
        }
	}

    public function update($id){
		try{
			$sql = "UPDATE genres SET genre = :genre WHERE id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindParam(':genre', $this->genre, \PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, \PDO::PARAM_INT);
			$stmt->execute();
			
			return true;
		}
		catch(\PDOException $e){
            $_SESSION['error'] = "Fehler beim Ändern: ". $e->getMessage();
            echo $e->getMessage();
            return false;
        }
	}

    public function delete($id){
		try
		{
			$sql = "DELETE FROM genres WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
		}
		catch(\PDOException $e)
		{
			$_SESSION['error'] = "Fehler beim Löschen: ". $e->getMessage();
            echo $e->getMessage();
            return false;
        }
	}

	public function select($id){
		try{
            $sql = "SELECT * FROM genres WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
	}
	catch(\PDOException $e){
            $_SESSION['error'] = "Fehler beim Auslesen: ". $e->getMessage();
            echo $e->getMessage();
            return false;
        }
	}

    public function selectAll(){
		try
		{
			$sql = "SELECT * FROM genres";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}catch(\PDOException $e){
			$_SESSION['error'] = "Fehler beim Auslesen: ". $e->getMessage();
            echo $e->getMessage();
            return false;
        }
	}
}
   

//--- Beispiele für die Verwendung der Klasse ---

/* 1. Neues Genre hinzufügen:
$genre = new Genres();
$genre->setDaten(['genre' => 'Science Fiction']); // Oder $genre->genre = 'Science Fiction';
$neueId = $genre->insert();

if ($neueId) {
    echo "Neues Genre hinzugefügt mit ID: " . $neueId . "<br>";
} else {
    echo "Fehler beim Hinzufügen des Genres.<br>";
}


// 2. Genre aktualisieren:
$genre = new Genres();
$erfolg = $genre->update(2, ['genre' => 'Sci-Fi']);  // Genre mit ID 2 aktualisieren.

if ($erfolg) {
    echo "Genre aktualisiert.<br>";
} else {
    echo "Fehler beim Aktualisieren des Genres.<br>";
}

// 3. Genre löschen:

$genre = new Genres();
$erfolg = $genre->delete(3);  // Genre mit ID 3 löschen.

if ($erfolg) {
    echo "Genre gelöscht.<br>";
} else {
    echo "Fehler beim Löschen des Genres.<br>";
}


// 4. Einzelnes Genre abrufen:
$genre = new Genres();
$genreDaten = $genre->select(1);  // Genre mit ID 1 abrufen

if ($genreDaten) {
    echo "Genre ID: " . $genreDaten['id'] . "<br>";
    echo "Genre Name: " . $genreDaten['genre'] . "<br>";
} else {
    echo "Genre nicht gefunden oder Fehler beim Abrufen.<br>";
}


// 5. Alle Genres abrufen:

$genre = new Genres();
$alleGenres = $genre->selectAll();

if ($alleGenres) {
    foreach ($alleGenres as $g) {
        echo "Genre ID: " . $g['id'] . ", Name: " . $g['genre'] . "<br>";
    }
} else {
    echo "Keine Genres gefunden oder Fehler beim Abrufen.<br>";
}
*/