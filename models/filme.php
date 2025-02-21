<?php
namespace mvc;
require_once __DIR__. '/../include/datenbank.php';

class Filme extends \Datenbank {

    use \GetterSetter; // Die GS triait ist in namespace.php
    private $title;
    private $imdbid;
    private $year;
    private $rated;
    private $released;
    private $runtime;
    private $plot;
    private $metascore;
    private $imdbrating;
    private $imdbvotes;
    private $boxoffice;
    private $poster;

    // Konstruktor
    public function __construct(Array $daten = []) // Konstruktor wird mit den Daten initialisiert
    {
        parent::__construct(); // Datenbank wird initialisiert
        $this->setDaten($daten); // Die Werte werden mit der setDaten funktion den Classen Variablen zugeordnet
    }

    public function setDaten(array $daten) {

        if ($daten) { // Wenn die Daten vorhanden sind
            foreach ($daten as $key => $value) {
                // Die Daten Werden als werte Paare genomme
                $propertyName = strtolower($key); // Der Key des wertes wird zum Key in Kleinbuchstaben

                // Überprüfe, ob die Klassen Variable existiert
                if (property_exists($this, $propertyName)) {
                    // Es wird geprüft ob eine der Klassenariablen den key des Wertes als namen hat
                    if ($propertyName === 'released') { // wenn der key "released" heißt
                        try {
                            // Wird versucht das Datum zu formatieren
                            $date = new \DateTime($value);

                            $this->released = $date->format('Y-m-d');
                        } catch (\Exception $e) {
                               error_log("Ungültiges Datums Format: " . $value);
                                $this->released = null; // or a default date

                        }
                    } else {
                        // Wenn der Key nicht "released" ist werden die werte den Keys normal zugeordnet
                        $this->$propertyName = $value;
                    }
                }
            }
        }
    }

    public function insert():bool{ // Diese Methode wird bei der instanzieierung ausgeführt
        // Direkter Zugriff auf $this->db innerhalb der erbenden Klasse
        try
        {
            $sql = "INSERT INTO filme (titel, imdbid, plot, erscheinungs_jahr, erscheinungs_datum, laufzeit, jugendfreigabe, metascore, imdbbewertung, imdbvotes, boxoffice, poster) 
                    VALUES (:titel, :imdbid, :plot, :erscheinungs_jahr, :erscheinungs_datum, :laufzeit, :jugendfreigabe, :metascore, :imdbbewertung, :imdbvotes, :boxoffice, :poster)";
            $stmt = $this->db->prepare($sql);
            $attribute = [
                'titel' => $this->title,
                'imdbid' => $this->imdbid,
                'poster' => $this->poster,
                'plot' => $this->plot,
                'erscheinungs_jahr' => $this->year,
                'erscheinungs_datum' => $this->released, // Use the formatted date
                'laufzeit' => $this->runtime,
                'jugendfreigabe' => $this->rated,
                'metascore' => $this->metascore,
                'imdbbewertung' => $this->imdbrating,
                'imdbvotes' => $this->imdbvotes,
                'boxoffice' => $this->boxoffice
            ];
            $stmt->execute($attribute);
            return true;
        }
        catch(\PDOException $e){
            error_log($e->getMessage());
            write_error('Es gab einen Fehler beim einfügen in die Datenbank: '.$e->getMessage(). "<br>". __FILE__ );
            return false;
        }
    }

    public function update($id):bool 
    {
        try{
            $sql = "UPDATE filme SET titel = :titel, imdbid = :imdbid, poster = :poster, plot = :plot, erscheinungs_jahr = 
                    :erscheinungs_jahr, erscheinungs_datum = :erscheinungs_datum, laufzeit = :laufzeit, jugendfreigabe = 
                    :jugendfreigabe, metascore = :metascore, imdbbewertung = :imdbbewertung, imdbvotes = :imdbvotes, boxoffice = :boxoffice WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            $attribute = [
                'titel' => $this->title,
                'imdbid' => $this->imdbid,
                'poster' => $this->poster,
                'plot' => $this->plot,
                'erscheinungs_jahr' => $this->year,
                'erscheinungs_datum' => $this->released, //Es wird die formatierte detai genommen
                'laufzeit' => $this->runtime,
                'jugendfreigabe' => $this->rated,
                'metascore' => $this->metascore,
                'imdbbewertung' => $this->imdbrating,
                'imdbvotes' => $this->imdbvotes,
                'boxoffice' => $this->boxoffice,
                'id' => $id
            ];

            $stmt->execute($attribute);
            return true;
        }catch(\PDOException $e)
        {
            write_error('Fehler beim Aktualisieren: '. $e->getMessage(). "<br>". __FILE__ );
            return false;
        }
    }

    public function delete($id):bool
    {
        try
        {
            $sql = "DELETE FROM filme WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return true;

        }catch(\PDOException $e){
            error_log($e->getMessage());
            write_error('Fehler beim Löschen: '. $e->getMessage(). "<br>". __FILE__ );
            return false;
        
        }
    }

    public function select(int $id):array|false
    {
        try
        {
            $sql = "SELECT * FROM filme WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC); // Fetch a single result as an associative array
            
        }catch(\PDOException $e)
        {
            write_error('Fehler beim Auslesen: '. $e->getMessage(). "<br>". __FILE__ );
            return false;
        }
    }

    public function selectAll():array | false
    {
        try{
            $sql = "SELECT * FROM filme";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch(\PDOException $e)
        {
            $_SESSION['error'] = "Fehler beim Auslesen: ". $e->getMessage();
            return false;
        }
    }
}



?>