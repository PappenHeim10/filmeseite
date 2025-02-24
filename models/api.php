<?php
namespace mvc;

use SimpleXMLElement;

class Api { // TEST: Ich muss schauen ob der key noch funktioniert und zur not einen anderen key benutzen

    private String $apiKey = $_ENV['API_KEY2'];
    private String $basisURL = "http://www.omdbapi.com/"; // Basis-URL für die API

    // Generalisierte Methode für API-Anfragen
    private function apiRequestJSON($params) : array { // Es wird um einen parameter gebeten und ein array erwartet
        
        // Füge den API-Schlüssel zu den Parametern hinzu
        $params['apikey'] = $this->apiKey;

        // Erstelle den Query-String
        // die API-Anfrage wird in einen String umgeschrieben
        $queryString = http_build_query($params); // Macht das URL-Encoding automatisch!

        // Erstelle die vollständige URL
        $url = $this->basisURL . "?" . $queryString;


        //              --- cURL-Initialisierung für die API request verarbeitung---
        // cURL-Initialisierung
        $ch = curl_init(); // URL-Initialisierung
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10, // Timeout auf 10 Sekunden setzen
            CURLOPT_FAILONERROR => true, // Fehler bei HTTP-Statuscodes >= 400
        ]);

        $response = curl_exec($ch); // Hier wird die Antwort des API requests in ein associatives array entcoded
        $error = curl_error($ch); // Speichere mögliche cURL-Fehler
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // HTTP-Statuscode abrufen
        curl_close($ch); // die Initialiesirung ended hier
        //             --- cURL wird geschlossen --- 

        // Fehlerbehandlung des API requests
        if ($error) {
            error_log("cURL-Fehler: " . $error); // Fehler protokollieren
            return ['Response' => 'False', 'Error' => 'cURL-Fehler: ' . $error]; // TODO Ich brauche einen ort an dem ich das Ausgeben
        }

        // Prüfen, ob der HTTP-Statuscode OK ist
        if ($httpCode !== 200) {
             error_log("OMDb API Fehler (HTTP Code $httpCode): " . $response); // Fehler protokollieren
            return ['Response' => 'False', 'Error' => 'OMDb API Fehler (HTTP Code ' . $httpCode . ')']; //TODO auch hier brauche ich einen Ort an dem ich das Ausgeben kann
        }


        // Dekodieren der JSON-Antwort
        $data = json_decode($response, true);

        
        // Prüfen, ob die API-Antwort gültig ist (Response === 'True' und die Suche erfolgreich)
        if ($data === null) {
            error_log("JSON-Dekodierungsfehler: " . json_last_error_msg());
            return ['Response' => 'False', 'Error' => 'Fehler beim Dekodieren der JSON-Datei: ' . json_last_error_msg()];
        }
        return $data;
    }

    private function apiRequestXML($params):SimpleXMLElement|array{ // Es wird um einen parameter gebeten und ein array erwartet
        
        $params['apikey'] = $this->apiKey;

        $queryString = http_build_query($params); // Macht das URL-Encoding automatisch!

        $url = $this->basisURL . "?" . $queryString;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FAILONERROR => true,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($error){
            write_error("cURL-Fehler: " . $error);
            return ['Response' => 'False', 'Error' => 'cURL-Fehler: '. $error];
        }

        if($httpCode !== 200){
            write_error("OMDb API Fehler (HTTP Code $httpCode): " . $response);
            return ['Response' => 'False', 'Error' => 'OMDb API Fehler (HTTP Code ' . $httpCode . ')'];
        }

        try{
            $xml = simplexml_load_string($response);
            #$json = json_encode($xml);
            #return json_decode($json, true);
            if($xml === false){
                write_error("XML-Dekodierungsfehler: Kein gültiges XML gefunden");
                return ['Response' => 'False', 'Error' => 'Fehler beim Dekodieren des XML-Dokumentes'];
            }
            return $xml;
        }catch(\Exception $e){
            write_error("XML-Dekodierungsfehler: " . $e);
            return ['Response' => 'False', 'Error' => 'Fehler beim Dekodieren des XML-Dokumentes: ' . $e->getMessage()];
        }
    }


    // Methode für Filmlisten (Suchergebnisse)
    public function getFilme($title, $page = 1):array { 
        $params = [
            's' => $title,     // Suchbegriff
            'page' => $page,    // Seitenzahl
        ];
        return $this->apiRequestJSON($params);
    }

    public function getFilmeAsXML($title, $page = 1):array|SimpleXMLElement{ 
        $params = [
            's' => $title,     // Suchbegriff
            'page' => $page,    // Seitenzahl
            'r' => 'xml',      // XML-Antwort
        ];
        return $this->apiRequestXML($params);
    }

    // Methode für einzelne Filmdetails
    public function getFilmDetailsInJson($imdbId):array {
        $params = [
            'i' => $imdbId,    // IMDb-ID
            'plot' => 'full',  // Vollständigen Plot abrufen
            'r' => 'json',      // JSON-Antwort
        ];
        return $this->apiRequestJSON($params);
    }

    public function getFilmDetailsInXML($imdbId):array|SimpleXMLElement {
        $params = [
            'i' => $imdbId,    // IMDb-ID
            'plot' => 'full',  // Vollständigen Plot abrufen
            'r' => 'xml',      // XML-Antwort
        ];
        return $this->apiRequestXML($params);
    }

    // Methode, um die IMDb-ID anhand des Titels zu bekommen
    public function getFilmIDNachTitel($title) :int|false {
        $result = $this->getFilme($title); // Nutze getMovies für die Suche

        if (is_array($result) && isset($result['Search']) && !empty($result['Search'])) {
            // Gib die IMDb-ID des ERSTEN Suchergebnisses zurück
            return (int)$result['Search'][0]['imdbID'];
        }

        return false; // Kein Ergebnis oder Fehler
    }

    // Methode für nach Jahr sortierte Filme
       public function getFilmeNachJahr($title, $page = 1):array {
        $result = $this->getFilme($title, $page); // Nutze getMovies, nicht is_array prüfen, da makeApiRequest dies schon tut.

        if (isset($result['Search']) && is_array($result['Search'])) { // isset zuerst prüfen
               $movies = $result['Search'];
                usort($movies, function ($a, $b) {
                   $yearA = ($a['Year'] === 'N/A') ? 0 : intval($a['Year']);
                   $yearB = ($b['Year'] === 'N/A') ? 0 : intval($b['Year']);
                    return $yearB - $yearA;
               });
            $result['Search'] = $movies; // sortierte ergebnisse zu setzten
           }

        return $result; // Immer das Ergebnis zurückgeben (auch im Fehlerfall)
    }


    // Methode für einen zufälligen Film (optimiert)
    public function getZufallsFilm($title = 'Movie', $page = 1):array|false {
         $result = $this->getFilme($title, $page); // getMovies verwenden
        if (isset($result['Search']) && is_array($result['Search'])) {

            $movies = $result['Search'];
            $randomIndex = array_rand($movies); // Verwende array_rand für Zufallsauswahl
            $imdbId = $movies[$randomIndex]['imdbID'];
            return $this->getFilmDetailsInJson($imdbId); // getMovieDetails für Details verwenden
        }
        return false; // Kein Film gefunden oder Fehler
    }

    // Nicht mehr benötigte Methoden entfernen: movieZumstart, getEinenFilm
}