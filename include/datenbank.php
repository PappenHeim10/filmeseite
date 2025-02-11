<?php
namespace mvc;
include_once 'namespace.php';

abstract class Datenbank implements iDatenbank{
    private $servername = 'localhost';
    private $username = 'root';
    private $password = '';
    protected $dbname = 'filmeseite_cohen';
    public $db;

    private $options =  array
	(
		\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
		\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
		\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
		\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
        \PDO::ATTR_EMULATE_PREPARES => false
	);
    

    public function __construct() {
        // DSN mit charset=utf8mb4
        $dsn = "mysql:host=$this->servername;dbname=$this->dbname;charset=utf8mb4";
        try {
            $this->db = new \PDO($dsn, $this->username, $this->password, $this->options);
        } 
        catch (\PDOException $e)
        {
            // Fehlerbehandlung:  NICHT die Exception-Nachricht direkt ausgeben!
            write_error("Datenbankverbindungsfehler: " . $e->getMessage()); // Ins Error-Log schreiben

            // Optional: Benutzerfreundliche Fehlermeldung anzeigen (ohne sensible Daten)
             die("Es gab ein Problem mit der Datenbankverbindung. Bitte versuchen Sie es später erneut."); // Oder leite auf eine Fehlerseite um.

            // Optional: Exception weiterwerfen, wenn du sie an höherer Stelle behandeln willst
            // throw $e;
        }
    }
}
?>