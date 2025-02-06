<?php
namespace mvc;



class Liste{
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

                $totalresults = isset($this->movies['totalResults']) ? (int)$this->movies['totalResults'] : 0;
                $totalPages = ceil($totalresults / 10);
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                $output .= "<div class='pagination'>";
                for ($i = 1; $i <= $totalPages; $i++) {
                    $url = "?action=liste&title=" . urlencode($_GET['title']) . "&page=" . $i;
                    $output .= "<a href='" . $url . "'" . ($i == $currentPage ? " class='current'" : "") . ">" . $i . "</a> ";
                }
                $output .= "</div>";
                
                echo $output; 
            }
        } else {
            echo "<p>No movies found or invalid search results.</p>"; // Handle empty or incorrect results
        }
    }
}

$liset = new Liste($movies);
$liset->render();
?>

