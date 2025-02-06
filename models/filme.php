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
    private $poster;
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

        // Initialize ALL properties, even if they get overwritten by setDaten
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
        $this->poster = null;
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
                            // Attempt to create a DateTime object from the input

                            $date = new \DateTime($value);
                            // Format the date as YYYY-MM-DD for MySQL
                            $this->released = $date->format('Y-m-d');
                        } catch (\Exception $e) {
                            // Handle invalid date input.  Options:
                            // 1. Set to NULL:
                            // $this->released = null;
                            // 2. Set to a default date:
                            // $this->released = '1900-01-01';
                            // 3. Throw an exception to halt execution:
                            // throw new \InvalidArgumentException("Invalid date format for released: $value");
                            // 4. Log the error (best practice):
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
            $sql = "INSERT INTO filme (titel, poster, beschreibung, erscheinungs_jahr, erscheinungs_datum, 
            director, genre, land, cast, auszeichnungen, autor,
             laufzeit, jugendfreigabe, metascore, imdbbewertung, sprache, imdbvotes, art, boxoffice, website)
             VALUES (:titel, :poster, :beschreibung, :erscheinungs_jahr, :erscheinungs_datum, 
             :director, :genre, :land, :cast, :auszeichnungen, :autor,
           :laufzeit, :jugendfreigabe, :metascore, :imdbbewertung, :sprache, :imdbvotes, :art, :boxoffice, :website)";  //All placeholders are correctly listed

             $stmt = $this->db->prepare($sql);

             $attribute =[
                 'titel' => $this->title,
                 'poster' => $this->poster,
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
            $sql = "UPDATE filme SET titel = :titel, poster = :poster, beschreibung = :beschreibung, erscheinungs_jahr = :erscheinungs_jahr, director = :director, genre = :genre, land = :land, cast = :cast, auszeischnungen = :auszeischnungen, autor = :autor,
            laufzeit = :laufzeit, jugendfreigabe = :jugendfreigabe, bewertungen = :bewertungen, metascore = :metascore, imdbbewertung = :imdbbewertung, imdbvotes = :imdbvotes, type = :type, boxoffice = :boxoffice, website = :website
            WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':titel', $this->title, \PDO::PARAM_STR);
            $stmt->bindParam(':poster', $this->poster, \PDO::PARAM_STR);
            $stmt->bindParam(':beschreibung', $this->plot, \PDO::PARAM_STR);
            $stmt->bindParam(':erscheinungs_jahr', $this->year, \PDO::PARAM_INT);
            $stmt->bindParam(':director', $this->director, \PDO::PARAM_STR);
            $stmt->bindParam(':genre', $this->genre, \PDO::PARAM_STR);
            $stmt->bindParam(':land', $this->country, \PDO::PARAM_STR);
            $stmt->bindParam(':cast', $this->actors, \PDO::PARAM_STR);
            $stmt->bindParam(':auszeischnungen', $this->awards, \PDO::PARAM_STR);
            $stmt->bindParam(':autor', $this->writer, \PDO::PARAM_STR);
            $stmt->bindParam(':laufzeit', $this->runtime, \PDO::PARAM_STR);
            $stmt->bindParam(':jugendfreigabe', $this->rated, \PDO::PARAM_STR);
            $stmt->bindParam(':bewertungen', $this->ratings, \PDO::PARAM_STR);
            $stmt->bindParam(':metascore', $this->metascore, \PDO::PARAM_INT);
            $stmt->bindParam(':sprache', $this->language, \PDO::PARAM_STR);
            $stmt->bindParam(':imdbbewertung', $this->imdbRating, \PDO::PARAM_STR);
            $stmt->bindParam(':imdbvotes', $this->imdbVotes, \PDO::PARAM_INT);
             $stmt->bindParam(':type', $this->type, \PDO::PARAM_STR);
             $stmt->bindParam(':boxoffice', $this->boxOffice, \PDO::PARAM_STR);
             $stmt->bindParam(':website', $this->website, \PDO::PARAM_STR);
             $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

             $stmt->execute();

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
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();

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
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
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