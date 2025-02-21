<?php
namespace mvc;
use mvc\Api;
use mvc\Filme;

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
                hinweis_log('Keine seiten mehr nach seite: ' . $page); // Wenn es Probleme bei der Ermittlung der Seiten gibt
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
        $filmDaten = $this->filmeModel->select($id);// Nimmt einen Film aus der Datenbank nach dessen ID und gibt dessen Daten zurück

        if(!$filmDaten){ // Wenn es keine Daten gibt wird false zurück gegeben
            return false;
        }
        return $filmDaten; // Die Daten des Filmes werden zurückgegeben
    }

    public function getAlleFilme():array | false
    {
        $filme = $this->filmeModel->selectAll();

        if(!$filme){
            write_error("Fehler in: ". __METHOD__);
            return false;
        }
        return $filme;
    }
//Im FilmController

//Korrigierte Version
// FilmController.php (getFilmeAusDerDatenbank-Methode)
    public function getFilmeAusDerDatenbank(string $suchbegriff, int $seite, $sortOrder = 'DESC'): array|false
    {
        $limit = 10;
        $offset = ($seite - 1) * $limit;

        try {
            //  WHERE-Bedingung:
            $sql = "SELECT * FROM filme
                    WHERE LOWER(titel) LIKE LOWER(:suchbegriff)
                    ORDER BY erscheinungs_jahr $sortOrder
                    LIMIT :limit OFFSET :offset"; //WICHTIG: Wozu ist dieses OFFSET


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
    
    public function countFilme(string $suchbegriff): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM filme";

            $params = [];

            if ($suchbegriff !== '') {
                $sql .= " WHERE LOWER(titel) LIKE LOWER(:suchbegriff)"; // 
                $params[':suchbegriff'] = '%' . $suchbegriff . '%';
            }

            $stmt = $this->filmeModel->db->prepare($sql);
            if ($suchbegriff !== '') {
            $stmt->bindValue(':suchbegriff', '%' . $suchbegriff . '%', \PDO::PARAM_STR); // Nur binden wenn benötigt
            }

            $stmt->execute();

            return (int)$stmt->fetchColumn();

        } catch (\PDOException $e) {
            write_error("Fehler beim Zählen der Filme: " . $e->getMessage()); // Schreibe Fehler
            return 0;
        }
    }
    public function getFilmeIdNachName(String $titel):array|false
    {
        try{
            $sql = "SELECT id FROM filme WHERE lower(titel) LIKE :titel"; // Das ist der sql querey der ausgeführt werden soll
            $stmt = $this->filmeModel->db->prepare($sql); // 
            $stmt->bindValue(":titel", strtolower($titel). "%", \PDO::PARAM_STR);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_COLUMN); // Hier werden alle ids aufgerufen selbst wenn es nur eins ist

            if($results === false){
                write_error("Kein Film gefunden: $titel");
                return false;
            }else{
                return $results;
            }
        } catch (\PDOException $e) {
            write_error("Fehler in der getFilmNachNameMetdose: " .$e->getMessage() . " " . __METHOD__);
            return false;
        }
    }

    public function getFilmNachImdb($imdbID):array|false
    {
        try{
            $sql = "SELECT * FROM filme WHERE imdbid = :imdbid";
            $stmt = $this->filmeModel->db->prepare($sql);
            $stmt->bindValue(":imdbid", $imdbID, \PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            write_error("Fehler in der getFilmNachImdbMethode: " .$e->getMessage());

            return false;
        }
    }


    public function getZufallsFilm(): array | false
    {
        $filme = $this->getAlleFilme();
        if ($filme === false) {
            write_error("Fehler beim Abrufen der Filme." . __METHOD__);
            return false;
        }
    
        if (empty($filme)) { // Prüfe auf leeres Array, bevor du count() verwendest
            write_error("Keine Filme in der Datenbank gefunden." . __METHOD__);
            return false;
        }
        
        $zufall = array_rand($filme); // array_rand gibt den zufälligen Schlüssel zurück
        $zufallsFilm = $filme[$zufall]; // Hole den Film anhand des Schlüssels
    
        return $zufallsFilm; // Gib den Film direkt zurück, nicht die ID
    }
    public function getFilmeMitApi($titel){
        $filme = $this->api->getFilme($titel);

        return $filme; //
    }
}

?>