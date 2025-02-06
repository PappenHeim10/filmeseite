<?php
namespace mvc;

class User extends Datenbank
{
    use GetterSetter; // Getter Setter als trait um redundanten code zu vermeiden
    private $anrede;
    private $vorname;
    private $nachname;
    private $email;
    private $benutzername;
    private $passwort;
    private $pww;

    public function __construct(Array $daten = [])
    {
        $this->setDaten($daten);
    }
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
    public function insert()
    {
        try{
            $sql = "INSERT INTO user (anrede, vorname, nachname, email, benutzername, passwort) VALUES (:anrede, :vorname, :nachname, :email, :benutzername, :passwort)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':anrede', $this->anrede);
            $stmt->bindParam(':vorname', $this->vorname);
            $stmt->bindParam(':nachname', $this->nachname);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':benutzername', $this->benutzername);
            $stmt->bindParam(':passwort', $this->passwort);
            $stmt->execute();

            echo "<h1>User erstellt</h1>";
        }
        catch(\PDOException $e)
        {
            $_SESSION['error'] = "Fehler beim Einfügen des Users: ". $e->getMessage();
            return false;
        }
    }
    public function update($i)
    {
        try
        {
            $sql = "UPDATE user SET anrede = :anrede, vorname = :vorname, nachname = :nachname, email = :email, benutzername = :benutzername, passwort = :passwort WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':anrede', $this->anrede);
            $stmt->bindParam(':vorname', $this->vorname);
            $stmt->bindParam(':nachname', $this->nachname);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':benutzername', $this->benutzername);
            $stmt->bindParam(':passwort', $this->passwort);
            $stmt->bindParam(':id', $i);
        }
        catch(\PDOException $e)
        {
            $_SESSION['error'] = "Fehler beim Aktualisieren des Users: ". $e->getMessage();
            return false;
        }
    }
    public function delete($i)
    {
        try
        {
            $sql = "DELETE FROM user WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $i);
            $stmt->execute();
            return true;
        }
        catch(\PDOException $e)
        {
            $_SESSION['error'] = "Fehler beim Löschen des Users: ". $e->getMessage();
            return false;
        }
    }

	public function select($id){
		try{
            $sql = "SELECT * FROM user WHERE id = :id";
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
?>