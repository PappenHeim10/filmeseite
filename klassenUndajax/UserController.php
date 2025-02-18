<?php
class UserController
{
    private $userModel;
    use Validation; // Die Validation-Trait ist in namespace.php

    public function __construct()
    {
        $this->userModel = new User(); // Instanz der User-Klasse (dein Model)
    }

    public function registrierung(): array | false
    {
        try
        {
            $sql = "SELECT COUNT(*) AS anzahl FROM users WHERE benutzername = :benutzername";
            $stmt = $this->userModel->db->prepare($sql);  // $this->db, da in der User-Klasse
            $stmt->bindParam(':benutzername', $benutzername);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC); // Holt das Ergebnis als assoziatives Array
            $anzahl =  (int)$result['anzahl']; // Gibt die Anzahl als Integer zurück

            //DO!: Ich muss die Funtkion weiter schreiben
        }
        catch(\PDOException $e){
            write_error('Fehler in der UserController klasse: '.$e->getMessage(). ' ' . __METHOD__);
        }
    }


}


