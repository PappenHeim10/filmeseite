<?php
namespace mvc;

class Api {

    private $apiKey = "f9d69f9c";

    public function getMovies($title, $view, $page = 1) {
        $url = "http://www.omdbapi.com/?apikey=" . $this->apiKey;

        $url .= $view == 'liste' ? "&s=" . urlencode($title) : "&t=" . urlencode($title);
        $url .= urldecode($title)."$page=". $page;
    

        $response = file_get_contents($url);
        if ($response === false) {
            return ['Response' => 'False', 'Error' => 'Fehler bei der API Anfrage.']; // Handle file_get_contents error
        }
    
        $data = json_decode($response, true);
    
        if ($data === null) {
            return ['Response' => 'False', 'Error' => 'Fehler beim Decoden der JSON Datei.']; // Handle JSON decoding error
        }
    
    
        if ($data['Response'] == 'True') {
            return $data;
        } else {
            return ['Response' => 'False', 'Error' => $data['Error']]; // Return API error
        }
    }
}

?>