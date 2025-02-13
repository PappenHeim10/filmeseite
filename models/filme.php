<?php
namespace mvc;
require_once 'include/datenbank.php';

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

    public function select($id):array |false
    {
        try
        {
            $sql = "SELECT * FROM filme WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]); // Simplified binding
            return $stmt->fetch(\PDO::FETCH_ASSOC); // Fetch a single result as an associative array
            
        }catch(\PDOException $e)
        {
            error_log($e->getMessage());
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
    public function countFilme(string $suchbegriff = ''): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM filme";
            $params = [];
            if ($suchbegriff !== '') {
                $sql .= " WHERE (vollstaendig = 0 OR LOWER(titel) LIKE LOWER(:suchbegriff))"; //hier muss die WHERE angepasst werden
                $params[':suchbegriff'] = '%' . $suchbegriff . '%';
            }

            $stmt = $this->filmeModel->db->prepare($sql);

            if ($suchbegriff !== '') {
            $stmt->bindValue(':suchbegriff', '%' . $suchbegriff . '%', \PDO::PARAM_STR); // Nur binden wenn benötigt
            }

            $stmt->execute();
            return (int)$stmt->fetchColumn();

        } catch (\PDOException $e) {
            error_log("Fehler beim Zählen der Filme: " . $e->getMessage());
            write_error("Fehler beim Zählen der Filme: " . $e->getMessage()); // Schreibe Fehler
            return 0;
        }
    }
}


class FilmController
{
    private $api; // Der API Key 
    public $filmeModel; // Die Filme Model mit der die Basis Methoden aufgerugen werden

    public function __construct(Array $daten = []) // Konstruktor wird mit den Daten initialisiert
    {
        $this->api = new Api(); // Die API wird initialisiert
        $this->filmeModel = new Filme(); // Die Filme Model wird initialisiert
    }

    // In FilmController

    public function filmeMassenEinfuegen(string $suchbegriff): void
    {
        $page = 1; // Es wird die einz als Variable gesetzt
        $filmeVonApi = []; // Es wird ein Array erstellt

        do{
            $filmliste = $this->api->getFilme($suchbegriff, $page); // Die film Liste wird mit filmen gefüllt

            if($filmliste && isset($filmliste['Search']) && is_array($filmliste['Search'])) // Wenn es die Filmliste gibt
            {
                $filmeVonApi = array_merge($filmeVonApi, $filmliste['Search']); // wird sie zu dem filmeVonApi Array hinzugefügt
                $page++; // Die Seite wird um 1 erhöht
                $totalresults = isset($filmliste['totalResults']) ? (int)$filmliste['totalResults'] : 0;
                $totalPages = ceil($totalresults / 10); // Die Seiten Anzahl wird ermittelt
            }else{
                hinweise_log('Keine seiten mehr nach seite: ' . $page); // Wenn es Probleme bei der Ermittlung der Seiten gibt
                break;
            }  
        }while($page <= $totalPages); //NOTE: Der Obige code block wird sollange ausgeführt
        foreach($filmeVonApi as $film){ // Sobald es Keine Filmemehr gibt
            $filmdetails = $this->api->getFilmDetails($film['imdbID']); // NOTE:  Das wird am ende ausgeführt wenn es keine Filme mehr gibt
            if ($filmdetails && $filmdetails['Response'] === 'True'){
                if ($this->filmExistiert($filmdetails['imdbID'])) {
                    continue; // Film wird übersprungen
                } else {
                        // Film einfügen
                    $this->filmeModel->setDaten($filmdetails);
                    if (!$this->filmeModel->insert()) {
                        write_error("Fehler beim Einfügen von Film: " . print_r($filmdetails, true). "<br>". __FILE__ );
                    }
                }
            }
        }
        $this->setVollstaendig($suchbegriff);
    }

    private function setVollstaendig(string $suchbegriff): void
    {
        $sql = "UPDATE filme SET vollstaendig = 1 WHERE LOWER(titel) LIKE LOWER(:suchbegriff)";
        $stmt = $this->filmeModel->db->prepare($sql);
        $stmt->execute([':suchbegriff' => $suchbegriff]);

        $stmt->execute();
    }


    //HILFS FUNKTION für filmeMasseneinfuegen()
    private function filmExistiert(string $imdbId): bool 
    {
        $sql = "SELECT COUNT(*) FROM filme WHERE imdbid = :imdbid";
        $stmt = $this->filmeModel->db->prepare($sql);
        $stmt->execute([':imdbid' => $imdbId]);
    
        $count = $stmt->fetchColumn();

        return $count > 0;
    }


    public function einzelInsertNachID($imdbId) :void{ 
        $filmdetails = $this->api->getFilmDetails($imdbId);

        if ($this->filmeModel->db === null) { 
            error_log("Datenbankverbindung in bulkInsert fehlgeschlagen.");
            write_error("Datenbankverbindung in bulkInsert fehlgeschlagen.". "<br>". __FILE__ );
            return; // Oder Exception werfen, je nach Fehlerbehandlungsstrategie
        }
        $film = new Filme($filmdetails); // Wird ein neus Objekt erstellt

        // Hier ist die Fehlerbehandlung
        if(!$film->insert()) {
            error_log("Fehler beim Einfügen des Films: " . print_r($filmdetails, true));
            write_error("Fehler beim Einfügen des Films: " . $film->title. "<br>". __FILE__ );
        }
    }

    public function getFilmNachId($id):array | false{ 
        $filmDaten = $this->filmeModel->select($id);REVIEW:// Ich versteh diesen teil des codes nicht ganz

        if(!$filmDaten){ // 
            return false;
        }
        return $filmDaten;
    }

    public function getAlleFilme():array | false
    {
        $filme = $this->filmeModel->selectAll();

        if(!$filme){
            return false;
        }
        return $filme;
    }
//Im FilmController

//Korrigierte Version
// FilmController.php (getFilmeAusDerDatenbank-Methode)
public function getFilmeAusDerDatenbank(string $suchbegriff, int $seite): array|false
{
    $limit = 10;
    $offset = ($seite - 1) * $limit;

    try {
        //  WHERE-Bedingung:
        $sql = "SELECT * FROM filme
                WHERE LOWER(titel) LIKE LOWER(:suchbegriff)
                LIMIT :limit OFFSET :offset";


        $stmt = $this->filmeModel->db->prepare($sql);
        $stmt->bindValue(':suchbegriff', '%' . $suchbegriff . '%', \PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($result)) { // Verwende empty()
            return false;
        }
        return $result;

    } catch (\PDOException $e) {
        error_log("Fehler beim Abrufen: " . $e->getMessage());
        write_error("Fehler beim Abrufen: " . $e->getMessage());
        return false;
    }
}

    // FilmController.php

}

?>