<?php
namespace mvc;
include_once 'include/namespace.php';

class Film extends Datenbank {

    use GetterSetter; // Die GS triait ist in namespace.php
    private $title;
    private $year;
    private $rated;
    private $released;
    private $runtime;
    private $genre;
    private $director;
    private $writer;
    private $actors;
    private $plot;
    private $language;
    private $country;
    private $awards;
    private $metascore;
    private $imdbrating;
    private $imdbvotes;
    private $type;
    private $boxoffice;
    private $website;



    // Konstruktor
    public function __construct(Array $daten = [])
    {
        parent::__construct();

        $this->title = null;
        $this->year = null;
        $this->rated = null;
        $this->released = null;
        $this->runtime = null;
        $this->genre = null;
        $this->director = null;
        $this->writer = null;
        $this->actors = null;
        $this->plot = null;
        $this->language = null;
        $this->country = null;
        $this->awards = null;
        $this->metascore = null;
        $this->imdbrating = null;
        $this->imdbvotes = null;
        $this->type = null;
        $this->boxoffice = null;
        $this->website = null;

        $this->setDaten($daten);
    }

    public function setDaten(array $daten) {
        if ($daten) {
            foreach ($daten as $key => $value) {
                $propertyName = strtolower($key);
                if (property_exists($this, $propertyName)) {
                    // Special handling for 'released' (erscheinungs_datum)
                    if ($propertyName === 'released') {
                        try {

                            $date = new \DateTime($value);

                            $this->released = $date->format('Y-m-d');
                        } catch (\Exception $e) {
                               error_log("Invalid date format for 'released': " . $value);
                                $this->released = null; // or a default date

                        }
                    } else {
                        $this->$propertyName = $value;
                    }
                }
            }
        }
    }

    public function insert(){
        try{
            $sql = "INSERT INTO filme (titel, beschreibung, erscheinungs_jahr, erscheinungs_datum, 
            director, genre, land, cast, auszeichnungen, autor,
             laufzeit, jugendfreigabe, metascore, imdbbewertung, sprache, imdbvotes, art, boxoffice, website)
             VALUES (:titel,  :beschreibung, :erscheinungs_jahr, :erscheinungs_datum, 
             :director, :genre, :land, :cast, :auszeichnungen, :autor,
           :laufzeit, :jugendfreigabe, :metascore, :imdbbewertung, :sprache, :imdbvotes, :art, :boxoffice, :website)";  //All placeholders are correctly listed

             $stmt = $this->db->prepare($sql);

             $attribute =[
                 'titel' => $this->title,
                 'beschreibung' => $this->plot,
                 'erscheinungs_jahr' => $this->year,
                 'erscheinungs_datum' => $this->released,
                 'director' => $this->director,
                 'genre' => $this->genre,
                 'land' => $this->country,
                 'cast' => $this->actors,
                 'auszeichnungen' => $this->awards,
                 'autor' => $this->writer,
                 'laufzeit' => $this->runtime,
                 'jugendfreigabe' => $this->rated,
                 'metascore' => $this->metascore,
                 'imdbbewertung' => $this->imdbrating,
                 'sprache' => $this->language,
                 'imdbvotes' => $this->imdbvotes,
                 'art' => $this->type,
                 'boxoffice' => $this->boxoffice,
                 'website' => $this->website
             ];

            // Use execute() with the associative array directly
            $stmt->execute($attribute);

             return $this->db->lastInsertId();
        }
        catch(\PDOException $e)
        {
            $_SESSION['error'] = "Fehler beim Einfügen: ". $e->getMessage();
            return false;
        }
    }

    
    public function update($id)
    {
        try{
            $sql = "UPDATE filme SET titel = :titel, poster = :poster, beschreibung = :beschreibung, erscheinungs_jahr = :erscheinungs_jahr, erscheinungs_datum = :erscheinungs_datum, director = :director, genre = :genre, land = :land, cast = :cast, auszeichnungen = :auszeichnungen, autor = :autor, laufzeit = :laufzeit, jugendfreigabe = :jugendfreigabe, metascore = :metascore, imdbbewertung = :imdbbewertung, sprache = :sprache, imdbvotes = :imdbvotes, art = :art, boxoffice = :boxoffice, website = :website WHERE id = :id"; // Added erscheinungs_datum

            $stmt = $this->db->prepare($sql);

            $attribute = [
                'titel' => $this->title,
                'poster' => $this->poster,
                'beschreibung' => $this->plot,
                'erscheinungs_jahr' => $this->year,
                'erscheinungs_datum' => $this->released, // Use the formatted date
                'director' => $this->director,
                'genre' => $this->genre,
                'land' => $this->country,
                'cast' => $this->actors,
                'auszeichnungen' => $this->awards,
                'autor' => $this->writer,
                'laufzeit' => $this->runtime,
                'jugendfreigabe' => $this->rated,
                'metascore' => $this->metascore,
                'imdbbewertung' => $this->imdbrating,
                'sprache' => $this->language,
                'imdbvotes' => $this->imdbvotes,
                'art' => $this->type,
                'boxoffice' => $this->boxoffice,
                'website' => $this->website,
                'id' => $id,
            ];


            $stmt->execute($attribute);

            return true;
        }
        catch(\PDOException $e)
        {
            $_SESSION['error'] = "Fehler beim Ändern: ". $e->getMessage();
            return false;
        }
    }

    public function delete($id)
    {
        try{
            $sql = "DELETE FROM filme WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]); // Simplified binding
            return true;
        }catch(\PDOException $e)
        {
            $_SESSION['error'] = "Fehler beim Löschen: ". $e->getMessage();
            return false;
        }
    }

    public function select($id)
    {
        try
        {
            $sql = "SELECT * FROM filme WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]); // Simplified binding
            return $stmt->fetch(\PDO::FETCH_ASSOC); // Fetch a single result as an associative array
        }
        catch(\PDOException $e)
        {
            $_SESSION['error'] = "Fehler beim Abfragen: ". $e->getMessage();
            return false;
        }
    }

    public function selectAll()
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