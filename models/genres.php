<?php
namespace mvc;

class Genre extends Datenbank // Das ist das Model für das genre
{
	// attributes
	use GetterSetter;
	private $id; // Die id 
	private $genre;// und der Name des Genres werden hier angegeben

	// konstruktor führt die funktion setDaten auf
	function __construct(Array $daten = []) // Der Konstruktor akzeptiert ein Array als parameter
	{
		$this->setDaten($daten); // Die Daten werden durch die setData methode zugewiesen
	}
	

	// setDaten führt die set-Methoden der Klasse auf, die übergebenen Werte entsprechendem Attributen setzen
    public function setDaten(array $daten)  
    {
        if($daten) 
        {
            foreach ($daten as $key => $value)  
            {
                // Direkt den Wert über __set setzen
                $this->$key = $value; 
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
			$_SESSION['error'] = "Fehler beim Einfügen: " . $e->getMessage();
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
   