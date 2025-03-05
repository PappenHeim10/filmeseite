<?php

namespace mvc;

use mvc\Api;
use mvc\Filme;
use PDO;
use PDOException;

class FilmController
{
    private Api $api; // Type-Hinting für bessere Lesbarkeit und Typsicherheit
    public Filme $filmeModel; // Type-Hinting

    public function __construct()
    {
        $this->api = new Api();
        $this->filmeModel = new Filme();
    }

    /**
     * Holt Filmdetails von der externen API und speichert sie in der Datenbank.
     *
     * @param string $imdbId
     * @return void
     */
    public function getFilmInJsonDurchImdbId(string $imdbId): void
    {
        $filmdetails = $this->api->getFilmDetailsInJson($imdbId);

        if (!$filmdetails) { // API-Aufruf fehlgeschlagen
            error_log("API-Aufruf fehlgeschlagen für IMDb ID: $imdbId");
            return; // Frühzeitiger Abbruch, da keine Daten
        }

        // Versuche, den Film zu speichern.  Fehlerbehandlung im Filme-Model
        $film = new Filme($filmdetails);
        if (!$film->insert()) {
            // Fehlerprotokollierung erfolgt bereits in der insert()-Methode
            return;
        }
    }

    /**
     * Holt einen Film anhand seiner Datenbank-ID.
     *
     * @param int $id
     * @return array|false
     */
    public function getFilmNachId(int $id): array|false
    {
        return $this->filmeModel->select($id); // Direkte Rückgabe, keine zusätzliche Logik
    }

    /**
     * Holt alle Filme aus der Datenbank.
     * @return array|false
     */
    public function getAlleFilme(): array|false
    {
        return $this->filmeModel->selectAll(); // Direkte Rückgabe, Fehlerbehandlung in selectAll
    }

    /**
     * Holt Filme aus der Datenbank mit Suchbegriff, Paginierung und Sortierung.
     *
     * @param string $suchbegriff
     * @param int $seite
     * @param string $sortOrder
     * @return array|false
     */
    public function getFilmeAusDerDatenbank(string $suchbegriff, int $seite, string $sortOrder = 'DESC'): array|false
    {
        $limit = 10;
        $offset = ($seite - 1) * $limit;

        // Validierung der Sortierreihenfolge, um SQL-Injection zu verhindern.
        $sortOrder = strtoupper($sortOrder); // Immer Großbuchstaben
        if (!in_array($sortOrder, ['ASC', 'DESC'])) {
            $sortOrder = 'DESC'; // Standardwert, falls ungültig
        }

        try {
            $sql = "SELECT * FROM filme
                    WHERE LOWER(titel) LIKE LOWER(:suchbegriff)
                    ORDER BY erscheinungs_jahr $sortOrder
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->filmeModel->db->prepare($sql);
            $stmt->bindValue(':suchbegriff', "%$suchbegriff%", PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: false; // Gib das Ergebnis zurück oder false, wenn es leer ist

        } catch (PDOException $e) {
            error_log("Fehler in getFilmeAusDerDatenbank: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Zählt die Anzahl der Filme in der Datenbank (optional mit Suchbegriff).
     *
     * @param string $suchbegriff
     * @return int
     */
    public function countFilme(string $suchbegriff = ''): int
    {
        try {
            $sql = "SELECT COUNT(*) FROM filme";
            $params = [];

            if ($suchbegriff !== '') {
                $sql .= " WHERE LOWER(titel) LIKE LOWER(:suchbegriff)";
                $params[':suchbegriff'] = "%$suchbegriff%";
            }

            $stmt = $this->filmeModel->db->prepare($sql);
            $stmt->execute($params); // Einheitliche Ausführung mit Parametern

            return (int)$stmt->fetchColumn();

        } catch (PDOException $e) {
            error_log("Fehler in countFilme: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Holt die Datenbank-IDs von Filmen anhand ihres Titels (Teilübereinstimmung).
     *
     * @param string $titel
     * @return array|false
     */
    public function getFilmeIdNachName(string $titel): array|false
    {
        try {
            $sql = "SELECT id FROM filme WHERE LOWER(titel) LIKE :titel";
            $stmt = $this->filmeModel->db->prepare($sql);
            $stmt->bindValue(':titel', strtolower("$titel%"), PDO::PARAM_STR); // String-Verkettung
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $results ?: false; // Gibt entweder die Ergebnisse oder false zurück

        } catch (PDOException $e) {
            error_log("Fehler in getFilmeIdNachName: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Holt einen Film anhand seiner IMDb-ID aus der Datenbank.
     *
     * @param string $imdbID
     * @return array|false
     */
    public function getFilmNachImdb(string $imdbID): array|false
    {
        try {
            $sql = "SELECT * FROM filme WHERE imdbid = :imdbid";
            $stmt = $this->filmeModel->db->prepare($sql);
            $stmt->bindValue(':imdbid', $imdbID, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false; // Gibt entweder das Ergebnis oder false zurück

        } catch (PDOException $e) {
            error_log("Fehler in getFilmNachImdb: " . $e->getMessage());
            return false;
        }
    }
     /**
     * Holt einen zufälligen Film aus der Datenbank.
     *
     * @return array|false
     */
    public function getZufallsFilm(): array|false
    {
        $filme = $this->filmeModel->selectAll();
        if (empty($filme)) {
             error_log("Keine Filme für Zufallsauswahl gefunden.");
            return false;
        }

        return $filme[array_rand($filme)];
    }


    /**
     * Holt Filme von der externen API (basierend auf Titel).
     *
     * @param string $titel
     * @return mixed  // Typ anpassen, je nachdem, was Api::getFilme() zurückgibt.
     */
    public function getFilmeMitApi(string $titel)
    {
        return $this->api->getFilme($titel); // Direkte Rückgabe
    }

    /**
     * Holt eine Liste von IMDb-IDs aus der Datenbank.
     *
     * @return array|false
     */
    public function getImdbIdListe(): array|false
    {
        try {
            $sql = "SELECT imdbid FROM filme";
            $stmt = $this->filmeModel->db->prepare($sql);
            $stmt->execute();
            $imdbIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return $imdbIds ?: [];  // Gib ein leeres Array zurück, wenn keine IDs gefunden wurden.

        } catch (PDOException $e) {
            error_log("Fehler in getImdbIdListe: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Holt eine Liste von Filmtiteln und IMDb-IDs aus der Datenbank.
     *
     * @return array
     */
    public function getFilmTitelUndImdbIds(): array
    {
        try {
            $sql = "SELECT titel, imdbid FROM filme";
            $stmt = $this->filmeModel->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Fehler in getFilmTitelUndImdbIds: " . $e->getMessage());
            return []; // Leeres Array im Fehlerfall
        }
    }
    /**
     * Holt eine Liste der Filmtitel
     *
     * @return array|false
     */
    public function getFilmTitelListe(): array|false
    {
        try{
            $sql = "SELECT titel FROM filme";
            $stmt = $this->filmeModel->db->prepare($sql);
            $stmt->execute();
            $titel = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return $titel ?: [];
        }catch(PDOException $e){
            error_log("Fehler in getFilmTitelListe: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Holt die ID und anschließend alle details eines Filmes anhand seiner IMDb ID
     *
     * @param  mixed $imdbid
     * @return array|false
     */
    public function getFilmDetailsById(string $imdbid): array|false
    {
        try {
            $sql = "SELECT id FROM filme WHERE imdbid = :imdbid";
            $stmt = $this->filmeModel->db->prepare($sql);
            $stmt->bindValue(":imdbid", $imdbid, PDO::PARAM_STR);
            $stmt->execute();

            $filmId = $stmt->fetch(PDO::FETCH_COLUMN);
            
            // Kurzschluss, wenn keine Film-ID gefunden wurde.
            if (!$filmId) {
                return false;
            }

            return $this->filmeModel->select($filmId);

        } catch (PDOException $e) {
            error_log("Fehler in getFilmDetailsById: " . $e->getMessage());
            return false;
        }
    }
}