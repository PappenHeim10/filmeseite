<?php
namespace Filmesite\views;

class MultipleMovies{
    private $movies = array();

    public function __construct($movies){ // Allow null for empty results
        $this->movies = $movies;
    }

    public function render(){
        if (is_array($this->movies) && !empty($this->movies['Search'])) { // Check 'Search' key
            foreach($this->movies['Search'] as $movie){
                $output = "<div class='movie'>";
                $output.= "<h2><a href='?action=singleMovies&title=" . urlencode($movie['Title']) . "'>". $movie["Title"]. "</a></h2>";
                $output.= "<img src='". $movie['Poster']. "' alt='". $movie['Title']. "'>";
                $output.= "<p>Erscheinungs Jahr:  ".$movie['Year']. "</p>";
                $output .= "<p>Art: ". $movie['Type']. "</p>";
                $output.= "<p>imdbID: ".$movie['imdbID']. "</p>";
                $output .= "</div>";

                echo $output; 
            }
        } else {
            echo "<p>No movies found or invalid search results.</p>"; // Handle empty or incorrect results
        }
    }
}


// In this file, after checking if $movies is not empty, create a MultipleMovies object and call render():
if(isset($movies)){ // Check if $movies is set
    $multipleMoviesView = new MultipleMovies($movies); // Pass $movies to the constructor
    $multipleMoviesView->render();
}

?>

