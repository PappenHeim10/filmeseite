<?php
require_once __DIR__. '/../include/datenbank.php';
class Post extends \Datenbank{
    private $inhalt;
    private $titel;
    private $imdbid;
    private $datum;

    public function __construct(array $daten){
        parent::__construct();
        $this->setDaten($daten);
    }

    public function setDaten(array $daten) {

        if ($daten) { // Wenn die Daten vorhanden sind
            foreach ($daten as $key => $value) {
                // Die Daten Werden als werte Paare genomme
                $propertyName = strtolower($key); // Der Key des wertes wird zum Key in Kleinbuchstaben
                if (property_exists($this, $propertyName)) {
                    $this->$propertyName = $value;
                }
            }
        }
    }
	public function insert():bool{
        try{
            $sql = "INSERT INTO posts(imdbid, titel, inhalt, datum, user_id) VALUES (:imdbid, :titel, :inhalt, :datum, :user_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':imdbid', $this->imdbid, \PDO::PARAM_STR);
            $stmt->bindParam(':titel', $this->titel, \PDO::PARAM_STR);
            $stmt->bindParam(':inhalt', $this->inhalt, \PDO::PARAM_STR);
            $stmt->bindParam(':datum', $this->datum, \PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], \PDO::PARAM_INT);

            return true;
        }catch(PDOException $e){
            write_error('Fehler beim einfügen in die Datenbank in der Post methode' .$e);
            return false;
        }
    }
    
	public function update(int $i):bool{
        try{
            $sql = "UPDATE posts SET imdbid = :imdbid, titel = :titel, inhalt = :inhalt, datum = :datum WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':imdbid', $this->imdbid, \PDO::PARAM_STR);
            $stmt->bindParam(':titel', $this->titel, \PDO::PARAM_STR);
            $stmt->bindParam(':inhalt', $this->inhalt, \PDO::PARAM_STR);
            $stmt->bindParam(':datum', $this->datum, \PDO::PARAM_STR);
            $stmt->bindParam(':id', $i, \PDO::PARAM_INT);

            return true;
        }catch(PDOException $e){
            write_error('Fehler beim Aktualisieren in der Datenbank in der Post methode' .$e);
            return false;
        }
    }
	function delete(int $i):bool{
        try{
            $sql = "DELETE FROM posts WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $i, \PDO::PARAM_INT);
            return true;
        }catch(PDOException $e){
            write_error('Fehler beim Löschen in der Datenbank in der Post methode' .$e);
            return false;
        }
    }
	function select(int $i):array|false{
        try{
            $sql = "SELECT * FROM posts WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $i, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            write_error('Fehler beim Auslesen in der Datenbank in der Post methode' .$e);
            return false;
        }
    }
	function selectAll():array|false{
        try{
            $sql = "SELECT * FROM posts";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            write_error('Fehler beim Auslesen in der Datenbank in der Post methode' .$e);
            return false;
        }
    }
}





?>