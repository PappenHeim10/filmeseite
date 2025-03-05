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

    public function selectAll(): array|false
    {
        try {
            $sql = "SELECT
                f.id,
                f.titel,
                f.erscheinungs_jahr,
                f.imdbid,
                f.poster,
                f.plot,
                f.laufzeit,
                f.jugendfreigabe,
                f.metascore,
                f.imdbbewertung,
                f.imdbvotes,
                f.boxoffice,
                f.erscheinungs_datum,
                GROUP_CONCAT(DISTINCT g.genre) AS genres,
                GROUP_CONCAT(DISTINCT s.schauspieler) AS schauspieler,
                GROUP_CONCAT(DISTINCT d.director) AS director,
                GROUP_CONCAT(DISTINCT l.land) AS land,
                GROUP_CONCAT(DISTINCT sp.sprache) AS sprache,
                GROUP_CONCAT(DISTINCT a.autor) AS autor
            FROM filme f
            LEFT JOIN filme_genres fg ON f.id = fg.film_id
            LEFT JOIN genres g ON fg.genre_id = g.id
            LEFT JOIN filme_schauspieler fs ON f.id = fs.film_id
            LEFT JOIN schauspieler s ON fs.schauspieler_id = s.id
            LEFT JOIN film_director fd ON f.id = fd.film_id
            LEFT JOIN director d ON fd.director_id = d.id
            LEFT JOIN filme_land fl ON f.id = fl.film_id
            LEFT JOIN land l ON fl.land_id = l.id
            LEFT JOIN filme_sprachen fsp ON f.id = fsp.film_id
            LEFT JOIN sprachen sp ON fsp.sprache_id = sp.id
            LEFT JOIN filme_autoren fa ON f.id = fa.film_id
            LEFT JOIN autoren a ON fa.autor_id = a.id
            GROUP BY f.id"; //Wichtig, damit GROUP_CONCAT richtig funktioniert.

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $results;

        }catch(\PDOException $e){
            write_error("Fehler beim in". __METHOD__.": ".$e->getMessage());
            return false;
        }
    }


    public function select(int $filmid): array|false
    {
        try {
            $sql = "SELECT
                f.id,
                f.titel,
                f.erscheinungs_jahr,
                f.imdbid,
                f.poster,
                f.plot,
                f.laufzeit,
                f.jugendfreigabe,
                f.metascore,
                f.imdbbewertung,
                f.imdbvotes,
                f.boxoffice,
                f.erscheinungs_datum,
                GROUP_CONCAT(DISTINCT g.genre) AS genres,
                GROUP_CONCAT(DISTINCT s.schauspieler) AS schauspieler,
                GROUP_CONCAT(DISTINCT d.director) AS director,
                GROUP_CONCAT(DISTINCT l.land) AS land,
                GROUP_CONCAT(DISTINCT sp.sprache) AS sprache,
                GROUP_CONCAT(DISTINCT a.autor) AS autor
            FROM filme f
            LEFT JOIN filme_genres fg ON f.id = fg.film_id
            LEFT JOIN genres g ON fg.genre_id = g.id
            LEFT JOIN filme_schauspieler fs ON f.id = fs.film_id
            LEFT JOIN schauspieler s ON fs.schauspieler_id = s.id
            LEFT JOIN film_director fd ON f.id = fd.film_id
            LEFT JOIN director d ON fd.director_id = d.id
            LEFT JOIN filme_land fl ON f.id = fl.film_id
            LEFT JOIN land l ON fl.land_id = l.id
            LEFT JOIN filme_sprachen fsp ON f.id = fsp.film_id
            LEFT JOIN sprachen sp ON fsp.sprache_id = sp.id
            LEFT JOIN filme_autoren fa ON f.id = fa.film_id
            LEFT JOIN autoren a ON fa.autor_id = a.id
            WHERE f.id = :filmId
            GROUP BY f.id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':filmId', $filmid, \PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result) {
                // Konvertiere kommagetrennte Strings in Arrays
                $result['genres'] = $result['genres'] ? explode(',', $result['genres']) : [];
                $result['schauspieler'] = $result['schauspieler'] ? explode(',', $result['schauspieler']) : [];
                $result['director'] = $result['director'] ? explode(',', $result['director']) : [];
                $result['land'] = $result['land'] ? explode(',', $result['land']) : [];
                $result['sprache'] = $result['sprache'] ? explode(',', $result['sprache']) : [];
                $result['autor'] = $result['autor'] ? explode(',', $result['autor']) : [];
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