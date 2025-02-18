<?php
include_once 'include/helpers.php';
require_once 'include/datenbank.php';

class User extends \Datenbank
{
    use \GetterSetter; // Getter Setter als trait um redundanten code zu vermeiden
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
            write_error("Fehler beim Einfügen des Users: ". $e->getMessage());
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
            write_error("Fehler beim Aktualisieren des Users: ". $e->getMessage());
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
            write_error("Fehler beim Löschen des Users: ". $e->getMessage());
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
            write_error("Fehler beim Auslesen: ". $e->getMessage());// WICHTIG: Die anderen write_error schreiben 
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


      //TODO Registration zuende schreiben
    public function registrierung(){
        #$this->validation();
        if(empty($this->fehler)){
            $this->insert();
            header('Location: index.php');
            exit;
        }
    }



    
    public function login():bool{
        try
        {
            $sql = "SELECT * FROM users WHERE benutzername = :benutzername AND passwort = :passwort";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':benutzername', $this->benutzername);
            $stmt->bindParam(':passwort', $this->passwort);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            if(empty($user)){
                return false;
            }else{
                $_SESSION['user'] = $user;
                return true;
            }
        }catch (Exception $e){
            write_error("Fehler beim Login: ". $e->getMessage());
            return false;
        }
    }
}
?>

