<?php
namespace Filmesite\models;

class Api {

    private $apiKey = "f9d69f9c";

    public function getMovies($title, $view) {
        $url = "http://www.omdbapi.com/?apikey=" . $this->apiKey;
    
        if ($view == 'multipleMovies') {
            $url .= "&s=" . urlencode($title); // URL encode the title
        } elseif ($view == 'singleMovies') {
            $url .= "&t=" . urlencode($title); // URL encode the title
        }
    
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