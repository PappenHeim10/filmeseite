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

    public function getGenres($id){
        try{
            $sql = "SELECT genre_id FROM filme_genres WHERE film_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);

            return $stmt->fetch(\PDO::FETCH_ASSOC);

        }catch(\PDOException $e){
            write_error('Fehler beim Auslesen des Genres: '. $e->getMessage(). "<br>". __FILE__ );
            return false;
        }
    }

    public function getSchauspieler ($id){
        try{
            $sql = "SELECT schauspieler_id FROM filme_schauspieler WHERE film_id = :id";
            $stmt =$this->db->prepare($sql);
            $stmt->execute(['id' => $id]);

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\Exception $e){
            write_error('Fehler beim Auslesen der');
        }
    }

    public function getDirectors($id){
        try{
            $sql = "SELECT director_id FROM film_director; WHERE film_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\Exception $e){
            write_error('Fehler beim Auslesen der');
        }
    }

    public function getCountries($id){
        try{
            $sql = "SELECT land_id FROM filme_land WHERE film_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);

            return $stmt->fetch(\PDO::FETCH_ASSOC);
            
        }catch(\Exception $e){
            write_error('Fehler beim Auslesen der');
        }
    }

    public function getSprachen($id){
        try{
            $sql = "SELECT sprache_id FROM filme_sprache WHERE film_id = :id";
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
            
        }catch(\Exception $e){
            write_error('Fehler beim Auslesen der');
        }
    }
    public function  getAutoren($id){
        try{
            $sql = "SELECT autor_id FROM filme_autoren WHERE film_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\Exception $e){
            write_error('Fehler beim Auslesen der');
        }
    }
    public function getBewertungen($id){
        try{
            $sql = "SELECT bewertung FROM bewertungen WHERE film_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
        }catch(\Exception $e){
            write_error('Fehler beim Auslesen der');
        }
    }

   public function select(int $filmid): array|false
{
    try {
        $sql = "SELECT 
                    filme.id,
                    filme.titel, 
                    filme.erscheinungs_jahr,
                    filme.imdbid,
                    filme.poster,
                    filme.plot, 
                    filme.laufzeit, 
                    filme.jugendfreigabe, 
                    filme.metascore, 
                    filme.imdbbewertung, 
                    filme.imdbvotes, 
                    filme.boxoffice, 
                    filme.erscheinungs_datum,
                    -- Weitere Spalten aus Filme
                    GROUP_CONCAT(DISTINCT genres.genre) AS genres,
                    GROUP_CONCAT(DISTINCT schauspieler.schauspieler) AS schauspieler,
                    GROUP_CONCAT(DISTINCT director.director) AS director,
                    GROUP_CONCAT(DISTINCT land.land) AS land,
                    GROUP_CONCAT(DISTINCT sprachen.sprache) AS sprache,
                    GROUP_CONCAT(DISTINCT autoren.autor) AS autor
                FROM filme
                LEFT JOIN filme_genres ON filme.id = filme_genres.film_id
                LEFT JOIN genres ON filme_genres.genre_id = genres.id

                LEFT JOIN filme_schauspieler ON filme.id = filme_schauspieler.film_id
                LEFT JOIN schauspieler ON filme_schauspieler.schauspieler_id = schauspieler.id

                LEFT JOIN film_director ON filme.id = film_director.film_id
                LEFT JOIN director ON film_director.director_id = director.id

                LEFT JOIN filme_bewertungen ON filme.id = filme_bewertungen.film_id
                LEFT JOIN bewertungen ON filme_bewertungen.bewertung_id = bewertungen.id

                LEFT JOIN filme_land ON filme.id = filme_land.film_id
                LEFT JOIN land ON filme_land.land_id = land.id

                LEFT JOIN filme_sprachen ON filme.id = filme_sprachen.film_id
                LEFT JOIN sprachen ON filme_sprachen.sprache_id = sprachen.id

                LEFT JOIN filme_autoren ON filme.id = filme_autoren.film_id
                LEFT JOIN autoren ON filme_autoren.autor_id = autoren.id

                WHERE filme.id = :filmId
                GROUP BY filme.id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':filmId', $filmid, \PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            // Genres und Schauspieler in Arrays umwandeln
            $result['genres'] = $result['genres'] ? explode(',', $result['genres']) : [];
            $result['schauspieler'] = $result['schauspieler'] ? explode(',', $result['schauspieler']) : [];
            return $result;
        } else {
            return false;
        }

    } catch (\PDOException $e) {
        write_error("Fehler in getFilmDetailsById: " . $e->getMessage());
        return false;
    }
}
   
}


?>