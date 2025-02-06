<?php
namespace Filmesite\views;
class singleMovies{
   private $movie = [];

   public function __construct($movie){
       $this->movie = $movie;
   }

   public function render(){
    if ($this->movie && $this->movie['Response'] == 'True') {
        $movie = $this->movie; // Assign to a shorter variable for easier use

        $output = "<div class='einzelFilm'>";
        $output .= "<div>";
        $output .= "<h2>" . $movie['Title'] . "</h2>";
        $output .= "<img src='" . $movie['Poster'] . "' alt='" . $movie['Title'] . "'>";
        $output .= "<p>Erscheinungs Jahr: " . $movie['Year'] . "</p>";
        $output.= "</div>";
        $output .= "<div>";
        $output .= "<p>Rated: " . $movie['Rated'] . "</p>"; // Added Rated
        $output .= "<p>Runtime: " . $movie['Runtime'] . "</p>"; // Added Runtime
        $output .= "<p>Genre: " . $movie['Genre'] . "</p>"; // Added Genre
        $output .= "<p>Director: " . $movie['Director'] . "</p>";// Added Director
        $output .= "<p>Writer: " . $movie['Writer'] . "</p>";// Added Writer
        $output .= "<p>Actors: " . $movie['Actors'] . "</p>";// Added Actors
        $output .= "<p>Plot: " . $movie['Plot'] . "</p>";// Added Plot
        $output .= "<p>Language: " . $movie['Language'] . "</p>";// Added Language
        $output .= "<p>Country: " . $movie['Country'] . "</p>";// Added Country
        $output .= "<p>Awards: " . $movie['Awards'] . "</p>";// Added Awards
        //Ratings are a nested array, so we need to loop through them
        $output .= "<p>Ratings: ";
        if (isset($movie['Ratings']) && is_array($movie['Ratings'])) {
            foreach ($movie['Ratings'] as $rating) {
                $output .= $rating['Source'] . ": " . $rating['Value'] . "<br>";
            }
        }
        $output .= "</p>";
        $output .= "<p>Metascore: " . $movie['Metascore'] . "</p>";// Added Metascore
        $output .= "<p>imdbRating: " . $movie['imdbRating'] . "</p>";// Added imdbRating
        $output .= "<p>imdbVotes: " . $movie['imdbVotes'] . "</p>";// Added imdbVotes
        $output .= "<p>imdbID: " . $movie['imdbID'] . "</p>";// Added imdbID
        $output .= "<p>Type: " . $movie['Type'] . "</p>";// Added Type
        $output .= "<p>DVD: " . $movie['DVD'] . "</p>";// Added DVD
        $output .= "<p>BoxOffice: " . $movie['BoxOffice'] . "</p>";// Added BoxOffice
        $output .= "<p>Production: " . $movie['Production'] . "</p>";// Added Production
        $output .= "<p>Website: " . $movie['Website'] . "</p>";// Added Website
        $output .= "</div>";


        $output .= "</div>"; // Close the main div

        echo $output;
    } else {
        return "<p>Movie data not available.</p>"; // Handle the case where data is missing
    }
}
}

if(isset($movie)){ // Check if $movies is set
    $singelMoviesView = new singleMovies($movie);
    $singelMoviesView->render();
}

?>





