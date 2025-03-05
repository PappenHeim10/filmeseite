<?php
namespace mvc;
#echo "BeDugging: View=$view , action=$action, title=$title, page=$page, imdbId=$imdbId";

$film = $filmController->getFilmDetailsById($imdbId);

if($imdbId != ""){
?>
<div class="singelSeite">
<div>
<h2><?php echo $film['titel']; ?></h2>
<img src="<?php echo $film['poster']; ?>" alt="">
<p>Jugendfreigabe: <?php echo $film['jugendfreigabe'] ?></p>
<p>Erscheinungsjahr: <?php echo $film['erscheinungs_jahr'];?></p>
</div>
<div>
    

<h3>Beschreibung:</h3>
    <p><?php echo $film['plot'];?></p>
</div>
<div>
<h3>Details:</h3>
    <p>Genre:
        <?php
            if (isset($film['genre']) && is_array($film['genre'])){
                $genreListe = implode(", ", $film['genre']);
                echo $genreListe;
            } elseif(isset($filme['genre'])) {
                echo $filme['genre'];
            }else{
                echo "Keine Genre gefunden.";
            }
        ?>
    </p>
    <p>Runtime: <?php echo $film['laufzeit'];?> Minuten</p>
    <p>Autor: <?php 
                if (isset($film['autor']) && is_array($film['autor'])){
                    $genreListe = implode(", ", $film['autor']);
                    echo $genreListe;
                } elseif(isset($filme['autor'])) {
                    echo $filme['autor'];
                }else{
                    echo "Keine Autoren gefunden.";
                }
    ?></p>
    <p>Erscheinungsdatum:  <?php echo $film['erscheinungs_datum'];?></p>
    <p>Directoren: <?php 
            if (isset($film['director']) && is_array($film['director'])){
                $genreListe = implode(", ", $film['director']);
                echo $genreListe;
            } elseif(isset($filme['director'])) {
                echo $filme['director'];
            }else{
                echo "Keine Director gefunden.";
            }
    ?></p>
    <p>Schauspieler:
        <?php
            if (isset($film['schauspieler']) && is_array($film['schauspieler'])) {
                $schauspielerListe = implode(", ", $film['schauspieler']);
                echo $schauspielerListe;
            } else {
                echo "Keine Schauspieler gefunden.";
            }
        ?>
    </p>
    <p>Sprachen: <?php 

if (isset($film['sprache']) && is_array($film['sprache'])) {
    $sprachenListe = implode(", ", $film['sprache']);
    echo $sprachenListe;
} elseif(isset($film['sprache'])) {
    echo $film['sprache'];
}else{
    echo "Keine sprachen gefunden.";
}
    

    
    ?></p>
</p>
</div>
<div>
<h3>Bewertungen:</h3>
    <p>Metascore: <?php echo $film['metascore'];?></p>
    <p>IMDB Rating: <?php echo $film['imdbbewertung'];?></p>
    <p>IMDB Votes: <?php echo $film['imdbvotes'];?></p>
    <p>Box Office: <?php echo $film['boxoffice'];?></p>
</div> 
</div>
<?php
}else{

$film = $filmController->getZufallsFilm();
?>
<div class="singelSeite">
<div>
<h2><?php echo $film['titel']; ?></h2>
<img src="<?php echo $film['poster']; ?>" alt="">
<p>Jugendfreigabe: <?php echo $film['jugendfreigabe'] ?></p>
<p>Erscheinungsjahr: <?php echo $film['erscheinungs_jahr'];?></p>
</div>
<div>
    

<h3>Beschreibung:</h3>
    <p><?php echo $film['plot'];?></p>
</div>
<div>
<h3>Details:</h3>
    <p>Genre:
        <?php
            if (isset($film['genre']) && is_array($film['genre'])){
                $genreListe = implode(", ", $film['genre']);
                echo $genreListe;
            } else {
                echo "Kein Genre gefunden.";
            }
        ?>
    </p>
    <p>Runtime: <?php echo $film['laufzeit'];?></p>
    <p>Autor: <?php echo $film['autor'];?></p>
    <p>ErscheinungsDatum: <?php echo $film['erscheinungs_datum'];?></p>
    <p>Directoren: <?php echo $film['director'];?></p>
    <p>Schauspieler:
        <?php
            if (isset($film['schauspieler']) && is_array($film['schauspieler'])) {
                $schauspielerListe = implode(", ", $film['schauspieler']);
                echo $schauspielerListe;
            } elseif(isset($film['schauspieler'])) {
                echo $film['schauspieler'];
            }else{
                echo "Keine Schauspieler gefunden.";
            }
        ?>
    </p>
    <p>Sprachen: <?php
                if (isset($film['sprache']) && is_array($film['sprache'])) {
                    $sprachenListe = implode(", ", $film['sprache']);
                    echo $sprachenListe;
                } elseif(isset($film['sprache'])) {
                    echo $film['sprache'];
                }else{
                    echo "Keine sprachen gefunden.";
                }
            ?></p>
</p>
</div>
<div>
<h3>Bewertungen:</h3>
    <p>Metascore: <?php echo $film['metascore'];?></p>
    <p>IMDB Rating: <?php echo $film['imdbbewertung'];?></p>
    <p>IMDB Votes: <?php echo $film['imdbvotes'];?></p>
    <p>Box Office: <?php echo $film['boxoffice'];?></p>
</div> 
</div>
<?php  
}
?>
<form action="" method="post">
    <label for="titel">Titel:</label>
    <input type="text" name="titel">
    <label for="inhalt">Inhalt:</label>
    <textarea name="inhalt"></textarea>
    <input type="submit" value="Posten">
</form>
