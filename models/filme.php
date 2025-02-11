<?php
namespace mvc;
require_once 'include/namespace.php';
require_once 'include/datenbank.php';

class Filme extends Datenbank {

    use GetterSetter; // Die GS triait ist in namespace.php
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
    public function __construct(Array $daten = [])
    {
        parent::__construct(); // Datenbank wird initialisiert
        $this->setDaten($daten); // Die Werte werden mit der setDaten funktion den Classen Variablen zugeordnet
    }

    public function setDaten(array $daten) {

        if ($daten) { // Wenn die Daten vorhanden sind
            foreach ($daten as $key => $value) {
                // Die Daten Werden als werte Paare genomme
                $propertyName = strtolower($key); // Der Key des wertes wird zum der Key in Kleinbuchstaben

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

    public function insert() {
        // Direkter Zugriff auf $this->db innerhalb der erbenden Klasse
        try {
            $sql = "INSERT INTO filme (titel, imdbid, plot, erscheinungs_jahr, erscheinungs_datum, laufzeit, jugendfreigabe, metascore, imdbbewertung, imdbvotes, boxoffice, poster) 
                    VALUES (:titel, :imdbid, :plot, :erscheinungs_jahr, :erscheinungs_datum, :laufzeit, :jugendfreigabe, :metascore, :imdbbewertung, :imdbvotes, :boxoffice, :poster)";
            $stmt = $this->db->prepare($sql); // $this->db ist das PDO-Objekt

            // ... (Rest deiner insert-Methode) ...
            $attribute = [
                'titel' => $this->title,
                'imdbid' => $this->imdbid,
                'poster' => $this->poster,
                'plot' => $this->plot,  // Korrigiere "beschreibung" zu "plot"
                'erscheinungs_jahr' => $this->year,
                'erscheinungs_datum' => $this->released,
                'laufzeit' => $this->runtime,
                'jugendfreigabe' => $this->rated,
                'metascore' => $this->metascore,
                'imdbbewertung' => $this->imdbrating,
                'imdbvotes' => $this->imdbvotes,
                'boxoffice' => $this->boxoffice,
            ];
            $stmt->execute($attribute);

        } catch (\PDOException $e) {
            error_log("Fehler beim Einfügen: " . $e->getMessage());
            return false; // Oder Exception weiterwerfen
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


class FilmController extends Filme
{
    private $api;

    public function __construct(Array $daten = [])
    {
        parent::__construct($daten);
        $this->api = new Api();
    }

    public function filmeMasseneinfuegen($suchbegriff, $anzahlSeiten) {
        $filmeZumEinfuegen = []; // Sammle alle Filme in einem Array

        for ($seite = 1; $seite <= $anzahlSeiten; $seite++) 
        { // es wird von der ersen bis zur öetzten seite iteriert
            $filmliste = $this->api->getMovies($suchbegriff, 'liste', $seite);
            // filmeliste wird mit der getMovies Methode der API Klasse aufgerufen

            if (is_array($filmliste) && isset($filmliste['Search']) && is_array($filmliste['Search']))
            { // Die 
                foreach ($filmliste['Search'] as $film) 
                {
                    $filmdetails = $this->api->getMovies('', 'singleMovies', 0, $film['imdbID']); // Die Filmdetails werden in der $filmdetails Variable gespeichert

                    if (is_array($filmdetails) && $filmdetails['Response'] === 'True') // Wenn es noch Filme zum einfügen gibt
                    {  
                        if (!isset($filmdetails['Title']) || !isset($filmdetails['imdbID'])) 
                        { // wenn der titel oder die imdbid nicht gesetzt sind
                            error_log("Fehlende Pflichtfelder für Film: " . print_r($filmdetails, true)); // Zeige eine Fehlermeldung
                            continue;
                        }
                        $filmeZumEinfuegen[] = $filmdetails; // Füge die Filmdetails dem Array hinzu
                    } else {
                        error_log("Fehler beim Abrufen von Film-Details für imdbID: " . $film['imdbID'] . " - API-Antwort: " . print_r($filmdetails, true));
                    }
                }
            } 
            else
            {
                error_log("Fehler bei der API-Anfrage für Seite $seite: " . print_r($filmliste, true));
            }
        }
        // Jetzt alle Filme auf einmal einfügen (Bulk Insert)
        $this->bulkInsert($filmeZumEinfuegen);
    }
    private function bulkInsert($filme) {
        if (empty($filme)) {
            return; // Keine Filme zum Einfügen
        }

        // Baue den SQL-Befehl dynamisch auf
        $sql = "INSERT INTO filme (titel, imdbid, plot, erscheinungs_jahr, erscheinungs_datum, laufzeit, jugendfreigabe, metascore, imdbbewertung, imdbvotes, boxoffice, poster) VALUES ";
        $placeholders = [];
        $values = [];

        foreach ($filme as $filmDaten) {
          // Stelle sicher, dass alle Werte vorhanden und richtig formatiert sind
            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // Anzahl der Fragezeichen muss mit Spalten übereinstimmen.

            // Werte in der richtigen Reihenfolge hinzufügen. Behandle fehlende Werte.
           $values[] = $filmDaten['Title'] ?? null;          // Titel
           $values[] = $filmDaten['imdbID'] ?? null;        // IMDb ID
           $values[] = $filmDaten['Plot'] ?? null;         // Beschreibung / Plot
           $values[] = $filmDaten['Year'] ?? null;         // Erscheinungsjahr
           // Erscheinungsdatum formatieren
           $released = $filmDaten['Released'] ?? null;
           if ($released && $released != 'N/A') {
              try {
                  $date = new \DateTime($released);
                  $values[] = $date->format('Y-m-d'); // Formatieren zu 'YYYY-MM-DD'
              } catch (\Exception $e) {
                   $values[] = null; // Bei ungültigem Datum, NULL einfügen
                   error_log("Ungültiges Datums Format: ". $released);
              }
            }else{
              $values[] = null;
            }
           $values[] = $filmDaten['Runtime'] ?? null;     // Laufzeit
           $values[] = $filmDaten['Rated'] ?? null;       // Jugendfreigabe
           $values[] = $filmDaten['Metascore'] ?? null;   // Metascore
           $values[] = $filmDaten['imdbRating'] ?? null;  // IMDb Bewertung
           $values[] = $filmDaten['imdbVotes'] ?? null;    // IMDb Votes
           $values[] = $filmDaten['BoxOffice'] ?? null;   // Box Office
           $values[] = $filmDaten['Poster'] ?? null; // Poster URL
        }

        $sql .= implode(",", $placeholders); // Verbinde die Platzhalter

        try {
            $stmt = $this->db->prepare($sql); // Erstelle ein Prepared Statement
            $stmt->execute($values); // Führe das Prepared Statement aus

            write_message("Erfolgreich eingefügt: " . $stmt->rowCount() . " Filme.\n");
        } catch (\PDOException $e) {

            error_log("Fehler beim Bulk Insert: " . $e->getMessage());
        }

    }


    public function einzelnenFilmEinfuegenNachID($imdbId) { 
        $filmdetails = $this->api->getMovies('', 'singleMovies', 0, $imdbId);

        if (is_array($filmdetails) && $filmdetails['Response'] === 'True')
        {
          if (!isset($filmdetails['Title']) || !isset($filmdetails['imdbID'])) {
              error_log("Fehlende Pflichtfelder für Film: " . print_r($filmdetails, true));
              return;
          }
          $filmObjekt = new Filme($filmdetails);
          try {
            $filmObjekt->insert();
          } catch (\Exception $e) {
              error_log("Fehler beim Einfügen des Films: " . $e->getMessage() . " - Filmdaten: " . print_r($filmdetails, true));
          }
      }else {
            error_log("Fehler beim Abrufen von Film-Details für imdbID: " . $imdbId . " - API-Antwort: " . print_r($filmdetails, true));
        }
    }
}


?>