<?php
include_once 'include/helpers.php';
require_once 'include/datenbank.php';

class User extends \Datenbank
{
    use \GetterSetter; // Getter Setter als trait um redundanten code zu vermeiden
    use \Validation;
    private $anrede;
    private $vorname;
    private $nachname;
    private $email;
    private $benutzername;
    private $passwort;
    private $pww;

    public function __construct(Array $daten = [])
    {
        parent::__construct();
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
    public function insert():bool
    {
        try{
            $sql = "INSERT INTO users (anrede, vorname, nachname, email, benutzername, passwort) VALUES (:anrede, :vorname, :nachname, :email, :benutzername, :passwort)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':anrede', $this->anrede);
            $stmt->bindParam(':vorname', $this->vorname);
            $stmt->bindParam(':nachname', $this->nachname);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':benutzername', $this->benutzername);
            $stmt->bindParam(':passwort', $this->passwort);
            $stmt->execute();

            echo "<h1>User erstellt</h1>";
            return true;
        }
        catch(\PDOException $e)
        {
            $_SESSION['error'] = "Fehler beim Einfügen des Users: ". $e->getMessage();
            return false;
        }
    }
    public function update($i):bool
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
            $stmt->execute();
            return true;
        }
        catch(\PDOException $e)
        {
            $_SESSION['error'] = "Fehler beim Aktualisieren des Users: ". $e->getMessage();
            return false;
        }
    }
    public function delete($i):bool
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

	public function select($id):array|false
    {
		try{
            $sql = "SELECT * FROM user WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(\PDO::FETCH_ASSOC); // Fetch a single result as an associative array
	}
	catch(\PDOException $e){
            $_SESSION['error'] = "Fehler beim Auslesen: ". $e->getMessage();
            echo $e->getMessage();
            return false;
        }
	}
    
    public function selectAll():array|false
    {
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
    public function einLoggen($benutzername, $passwort) {
        $fehler = [];
        
        try {
          $sql = "SELECT * FROM users WHERE benutzername = :benutzername";
          $stmt = $this->db->prepare($sql);
          $stmt->bindParam(':benutzername', $benutzername);
          $stmt->execute();
    
          if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            $hashedPassword = $row['passwort'];
    
            if ($passwort == $hashedPassword) {
              $_SESSION['loggedIn'] = true;
              $_SESSION['benutzername'] = $row['benutzername'];
              return true;
            } else {
              $fehler['passwort'] = "Falsches Passwort.";
            }
          } else {
            $fehler['notfound'] = "Benutzername nicht gefunden!";
          }
        } catch (\PDOException $e) {
          $fehler['db'] = "Fehler bei der Datenbankabfrage: " . $e->getMessage();
        }
        return $fehler;
      }


      //TODO Registration zuende schreiben
      public function registrierung(){
        #$this->validation();

        if(empty($this->fehler)){
            $this->insert();
            header('Location: index.php');
            exit;
        }
      }
}
?>

