<?php
namespace mvc;

class Api {

    private $apiKey = "f9d69f9c";
    private $baseUrl = "http://www.omdbapi.com/"; // Basis-URL für die API


    public function getMovies($title, $view, $page = 1, $imdbId = '') {
        $url = "http://www.omdbapi.com/?apikey=" . $this->apiKey;

        if ($view == 'liste') {
            $url .= "&s=" . rawurldecode($title);
            $url .= "&page=" . urlencode($page); // Seitenzahl hinzufügen (urlencode ist hier OK)
        } elseif ($view == 'singleMovies' && !empty($imdbId)) { //Nutze ImdbID
            $url .= "&plot=full";
            $url .= "&i=" . rawurlencode($imdbId); // i für IMDb-ID, rawurlencode
        } else {
             // Fehlerbehandlung für ungültige Parameter-Kombination
             return ['Response' => 'False', 'Error' => 'Ungültige Parameter-Kombination.'];
        }

        // cURL verwenden (robuster als file_get_contents)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout hinzufügen

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // cURL-Fehler
            $error_message = curl_error($ch);
            $_SESSION['error'] = "cURL-Fehler: ". $error_message; 
            curl_close($ch);
            return ['Response' => 'False', 'Error' => 'cURL-Fehler: ' . $error_message];
        }
        
        curl_close($ch);


        $data = json_decode($response, true); // Hier wird die Antwort des API requests in ein associatives array entcoded

        if ($data === null) { // Testet ob der Array leer ist
            return ['Response' => 'False', 'Error' => 'Fehler beim Decoden der JSON-Datei.'];
        }

        return $data; // Gib IMMER die dekodierten Daten zurück, auch wenn Response False ist.
    }

    public function getMovieIdByTitle($title) { // Methode um den Titel eines Filmes nach Dem nacmen zu durchsuchen
        $url = "http://www.omdbapi.com/?apikey={$this->apiKey}&s=" . rawurlencode($title);

        $ch = curl_init(); // Session wird initilisiert und so gestartet
        curl_setopt($ch, CURLOPT_URL, $url); // Die URL wird als das session ziel bestimmt
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Es wird um ein Datantransfer gebeten
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Nach 5 sekunden gibt es einen Timeout

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            $_SESSION['error'] = "cURL-Fehler: ". $error_message;
            curl_close($ch);
            return false; // Fehler behandlung
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if ($data === null || $data['Response'] === 'False' || !isset($data['Search'][0]['imdbID'])) {
            return false; // Oder Fehlercode
        }
        return $data['Search'][0]['imdbID']; // Gib die IMDb-ID des ERSTEN Suchergebnisses zurück
    }
    

     // Neue Methode für sortierte Filme
      public function getMoviesSortedByYear($title, $page = 1) {
        $result = $this->getMovies($title, 'liste', $page); // Benutze die bestehende Methode
        if (is_array($result) && isset($result['Search']) && is_array($result['Search'])) {
            $movies = $result['Search'];

            // Sortiere die Filme nach dem Jahr (absteigend)
            usort($movies, function($a, $b) { // usort wird auch usersort genannt
                // Wenn das Feld 'Year' 'N/A' ist, behandle es für die Sortierung als 0.
                // Andernfalls konvertiere das 'Year' in eine Ganzzahl.
                // Dies ist ein ternärer Operator:  Bedingung ? Wert_wenn_wahr : Wert_wenn_falsch
                $yearA = ($a['Year'] === 'N/A') ? 0 : intval($a['Year']); // wenn yearA gleich N/A ist dann 0 sonst wird es in ein integer umgewandelt
                $yearB = ($b['Year'] === 'N/A') ? 0 : intval($b['Year']);

                // Vergleiche die Jahre und gib das Ergebnis zurück:
                //  - Wenn $yearB größer als $yearA ist, gib eine positive Zahl zurück (sortiere $b vor $a).
                //  - Wenn $yearB kleiner als $yearA ist, gib eine negative Zahl zurück (sortiere $a vor $b).
                //  - Wenn $yearB gleich $yearA ist, gib 0 zurück (behalte ihre relative Reihenfolge bei).
                //  Dies erreicht eine absteigende Reihenfolge (neueste zuerst).
                return $yearB - $yearA; 
            });
           $result['Search'] = $movies;
        }
          return $result;
    }


    public function movieZumstart($title = 'Movie', $page = 1) {
        $url = "http://www.omdbapi.com/?apikey=" . $this->apiKey;
        $url .= "&s=" . rawurlencode($title);
        $url .= "&page=" . urlencode($page);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            $_SESSION['error'] = "cURL-Fehler: ". $error_message;
            curl_close($ch);
            return false;
        }else{
            $data = json_decode($response, true);
            curl_close($ch);
            return $data;
        }
    }


    public function getEinenFilm($title) {
        $titel = $this->movieZumstart($title); // Siese Funktion gibt ein Array mit Filminformationen zurück
    
        if (is_array($titel) && isset($titel['Search']) && !empty($titel['Search'])) {
            $filme = $titel['Search']; // Sie werden in einer Variable gespeichert
            $anzahlFilme = count($filme); // Die anzahl der Filme in diesem array werden in einer Variable
    
            $versuche = 0;
            $maxVersuche = 10; // Maximale Anzahl von Versuchen, um einen gültigen Index zu finden
    
            do {
                $zufallsIndex = rand(0, $anzahlFilme - 1); // Zufälliger Index von index 0 bis anzahl filmen -1
                $imdbID = $filme[$zufallsIndex]['imdbID']; 
    
                $url = "http://www.omdbapi.com/?apikey={$this->apiKey}&i={$imdbID}";
    
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $response = curl_exec($ch);
    
                if (curl_errno($ch)) {
                    $error_message = curl_error($ch);
                    // Hier könntest du loggen oder eine alternative Fehlerbehandlung durchführen
                    curl_close($ch);
                    return false; // Oder eine Fehler-Response zurückgeben
                } else {
                    $data = json_decode($response, true);
                    curl_close($ch);
    
                    if ($data !== null && $data['Response'] === 'True') { // Überprüfen ob die API geantwortet hat und der Film gefunden wurde.
                        return $data; // Erfolgreiche Rückgabe des Filmes
                    } else {
                        $versuche++;
                        // Optional: Bei ungültigem oder nicht gefundenem Film eine Meldung ausgeben.
                        // echo "Film mit imdbID {$imdbID} nicht gefunden. Neuer Versuch. <br>";
                    }
                }
    
            } while ($versuche < $maxVersuche);
    
            // Wenn nach mehreren Versuchen kein gültiger Film gefunden wurde.
            return false; // Oder eine Meldung, dass kein gültiger Film gefunden wurde.
        } else {
            return false; // Wenn $titel kein gültiges Array ist oder keine Filme gefunden wurden.
        }
    }

    // Methode für API anfragen
    private function einenAPIRequestMachen($params){
        // Fügt den API-SChüssel ein
        $pararms['apikey'] = $this->apiKey;

        // Erstelle den Query-String
        $queryString = http_build_query($params);

        // Erstelle die volständige URL
        $url = $this->baseUrl . '?' . $queryString;

        // cURL verwenden
        $ch = curl_init();

        curl_setopt_array($ch, [ // Die Curll Optionenwerden festgelegt
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10, // Timeout auf 10 Sekunden setzen
            CURLOPT_FAILONERROR => true, // Fehler bei HTTP-Statuscodes >= 400
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch); // Speichere mögliche cURL-Fehler
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // HTTP-Statuscode abrufen
        curl_close($ch);



        //-- Hier beginnt die Fehlerbehandlung --
        if($error){
            error_log("cURL-Fehler: " . $error); // Fehler ins Error-Log schreiben
            write_error("cURL-Fehler: " . $error); // Fehler ausgeben lassen
            return ['Response' => 'False', 'Error' => 'cURL-Fehler: ' . $error];
        }

        if ($httpCode !== 200) {
            error_log("OMDb API Fehler (HTTP Code $httpCode): " . $response);
            write_error("OMDb API Fehler (HTTP Code $httpCode): " . $response);
           return ['Response' => 'False', 'Error' => 'OMDb API Fehler (HTTP Code ' . $httpCode . ')'];
       }
       //-- Hier endet die Fehlerbehandlung --

       // JSON-Dekodieren
       $data = json_decode($response, true);

       if ($data === null) {
            error_log("JSON-Dekodierung fehlgeschlagen: " . $response);
            write_error("JSON-Dekodierung fehlgeschlagen: " . $response);
            return ['Response' => 'False', 'Error' => 'JSON-Dekodierung fehlgeschlagen'];
       }

       return $data;    
       
    }
        // Methode für Filmlisten (Suchergebnisse)
    
}

?>