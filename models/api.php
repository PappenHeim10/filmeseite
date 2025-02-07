<?php
namespace mvc;

class Api {

    private $apiKey = "f9d69f9c";

    public function getMovies($title, $view, $page = 1, $imdbId = '') {
        $url = "http://www.omdbapi.com/?apikey=" . $this->apiKey;

        if ($view == 'liste') {
            $url .= "&s=" . rawurldecode($title);
            $url .= "&page=" . urlencode($page); // Seitenzahl hinzufügen (urlencode ist hier OK)
        } elseif ($view == 'singleMovies' && !empty($imdbId)) { //Nutze ImdbID
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
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // HTTP-Statuscode abrufen

        if (curl_errno($ch)) {
            // cURL-Fehler
            $error_message = curl_error($ch);
            $_SESSION['error'] = "cURL-Fehler: ". $error_message;
            curl_close($ch);
            return ['Response' => 'False', 'Error' => 'cURL-Fehler: ' . $error_message];
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            // API-Fehler (z.B. 400 Bad Request, 404 Not Found)
            return ['Response' => 'False', 'Error' => 'API-Fehler: HTTP-Statuscode ' . $httpCode];
        }

        $data = json_decode($response, true);

        if ($data === null) {
            return ['Response' => 'False', 'Error' => 'Fehler beim Decoden der JSON-Datei.'];
        }

        return $data; // Gib IMMER die dekodierten Daten zurück, auch wenn Response False ist.
    }

    public function getMovieIdByTitle($title) {
        $url = "http://www.omdbapi.com/?apikey={$this->apiKey}&s=" . rawurlencode($title);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_message = curl_error($ch);
            curl_close($ch);
            return false; // Oder Fehlercode, z.B. -1 oder ein Array
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            return false; // Oder Fehlercode
        }

        $data = json_decode($response, true);

        if ($data === null || $data['Response'] === 'False' || !isset($data['Search'][0]['imdbID'])) {
            return false; // Oder Fehlercode
        }


        return $data['Search'][0]['imdbID']; // Gib die IMDb-ID des ERSTEN Suchergebnisses zurück
    }
    
     // Neue Methode für sortierte Filme
      public function getMoviesSortedByYear($title, $page = 1) {
        $result = $this->getMovies($title, 'liste', $page); // Nutze die bestehende Methode
        if (is_array($result) && isset($result['Search']) && is_array($result['Search'])) {
            $movies = $result['Search'];

            // Sortiere die Filme nach dem Jahr (absteigend)
            usort($movies, function($a, $b) {
                // Behandle 'N/A'-Jahre
                $yearA = ($a['Year'] === 'N/A') ? 0 : intval($a['Year']);
                $yearB = ($b['Year'] === 'N/A') ? 0 : intval($b['Year']);

                // Sortiere absteigend (neueste zuerst)
                return $yearB - $yearA;
            });
           $result['Search'] = $movies;
        }
          return $result;
    }
}

?>