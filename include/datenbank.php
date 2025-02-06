<?php
namespace mvc;
include_once 'namespace.php';

abstract class Datenbank implements iDatenbank{
    private $servername = 'localhost';
    private $username = 'root';
    private $password = '';
    protected $dbname = 'filmesite_cohen';
    public $db;

    private $options =  array
	(
		\PDO::ATTR_PERSISTENT => true,
		\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
		\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
		\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
		\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
	);
    

    public function __construct()
    {
        $this->db = $this->conn();
        if($this->db === false)
        {
            $_SESSION['error'] = "Verbindungsfehler im Konstruktor. include/datenbank.php <br>";
        }

    }

    public function conn(){
        $dsn = "mysql:host=$this->servername;dbname=$this->dbname";

        try
        {
            $db = new \PDO ($dsn, $this->username, $this->password,$this->options);
            return $db;
        }
        catch(\PDOException $e)
        {
            $_SESSION['error'] = "Verbindungsfehler: ". $e->getMessage();
            return false;
        }
    }
}
?>