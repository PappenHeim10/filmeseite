<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="bilder/movie-player.png">
    <title>MovieHouse</title>
</head>
<body>
<?php
class Header{
    public function render(){
        $output = '<div class="header">';
        $output.= '<div><img src="bilder/movie-player(1).png" alt="MoviePlayer"></div>';
        $output .= '<div><h1>MovieHouse</h1></div>';
        $output .= '<div></div></div>';

        echo $output;
    }
}
class Navigation{
    public function render(){
        $output = '<div class="navigation">';
        $output .= '<a href="?action=home">Home</a>';
        $output .= '<a href="?action=multipleMovies">Mehrere Filme ansehen</a>';
        $output .= '<a href="?action=singleMovies">Einzelne Filme</a>';
        $output .= '<a href="">Seite 3</a>';
        $output .= '</div>';

        echo $output; // Die render Funktion gibt alle elemente aus
    }
}
class Footer { // Die footer Klasse wird definiert
    public function render() { // Die render funktion wird beutzt um ihn wiederzugeben
      setlocale(LC_TIME, 'deu'); // Die Zeit wird gesetzt und das format wird auf deutsch umgeschaltet
  
  
      $output = '<div class="footer">'; // Hier werden die einzelnen Elemente in der Variable gespeichert
      $output .= '<footer>&copy; Cohen D. Imperial<br>'; 
      $output .= strftime("%A, %d. %B %Y");
      $output .= '</footer>';
      $output .= '</div>';
  
      
      echo $output; // Die render Runktion gibt alle elemente aus
    }
  }
?>
<script src="/js/script.js"></script>