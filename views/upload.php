<div class="upload">
    <h1>Filmedatei im JSON-Format Hochladen</h1>
    <form action="?action=upload" method="post" enctype="multipart/form-data">
        Film in die Datenbank Einfügen:<br>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Datei Hochladen" name="submit">
    </form>
    <p>Anforderungen sind</p>
    <ul>
        <li>Maximal 100Mb</li>
        <li>.json oder .xml</li>
    </ul>
</div>

<?php
// Die Titel und die ID werden gerufen



if (isset($_POST["submit"])) { 
    $fehler = []; // Hier werden alle uploads gespeichert
    $uploadOk = 1; // das ok wird auf ja gestellt
    $target_dir_for_xml = "admin/data/library_in_XML"; // Die zielverzeichnisse werden für xml und json festgeleg
    $target_dir_for_json = "admin/data/library_in_JSON";

    // Prüfen, OB eine Datei hochgeladen wurde, BEVOR auf $_FILES zugegriffen wird.
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] === UPLOAD_ERR_OK) {
        $dateiTyp = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


        if($dateiTyp == "xml"){
            $target_dir = $target_dir_for_xml;
            

        }elseif($dateiTyp == "json"){
            $target_dir = $target_dir_for_json;
        }else{
            $fehler[] = "Falscher Dateitype.";
            $uploadOk = 0;
        }


        





        $target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);
        hinweis_log("Eine Datei ist bereit zum hochladen.");

        // Datei existiert bereits?
        if (file_exists($target_file)) {
            $fehler[] = "Datei bereits vorhanden.";
            $uploadOk = 0;
            hinweis_log('Eine Datei ist bereits vorhanden.');
        }

        // Dateigröße prüfen (100 MB)
        if ($_FILES["fileToUpload"]["size"] > 100 * 1024 * 1024) {
            $fehler[] = "Datei ist zu groß (maximal 100 MB).";
            $uploadOk = 0;
            hinweis_log('Eine Datei ist zu groß.');
        }

        // Dateityp prüfen (Kleinbuchstaben!)
        if ($dateiTyp != "xml" && $dateiTyp != "json") {
            $fehler[] = "Nur JSON und XML-Dateien sind erlaubt.";
            $uploadOk = 0;
        }

        // Upload durchführen, WENN $uploadOk noch 1 ist.  UND: Fehler beim Verschieben prüfen!
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "<p>Die Datei " . basename($_FILES["fileToUpload"]["name"]) . " wurde hochgeladen.</p>";
            } else {
                $fehler[] = "Es gab einen Fehler beim Hochladen der Datei."; // Oder detailliertere Fehlermeldung
            }
        }
    } else {
      
        //Keine Datei hochgeladen, oder ein Fehler ist aufgetreten.
        switch($_FILES['fileToUpload']['error']){
            case UPLOAD_ERR_INI_SIZE:
                $fehler[] = "Die Datei ist größer als die maximal erlaubte Dateigröße (upload_max_filesize in php.ini).";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $fehler[] = "Die Datei ist größer als die maximal erlaubte Dateigröße (MAX_FILE_SIZE im HTML-Formular).";
                break;
            case UPLOAD_ERR_PARTIAL:
                $fehler[] = "Die Datei wurde nur teilweise hochgeladen.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $fehler[] = "Es wurde keine Datei hochgeladen.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $fehler[] = "Der temporäre Ordner fehlt.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $fehler[] = "Fehler beim Schreiben der Datei auf die Festplatte.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $fehler[] = "Eine PHP-Erweiterung hat den Datei-Upload gestoppt.";
                break;
            default:
                $fehler[] = "Unbekannter Fehler beim Hochladen.";
                break;

        }
    }
}
?>

<div class="error">
<?php
if(isset($fehler)){
    if(is_array($fehler) && !empty($fehler)){
        foreach ($fehler as $fehl) {
            echo "<p>".htmlspecialchars($fehl)."</p><br>";
            hinweis_log($fehl);
        }
    }
}

// NOTE: Es sollen 2 besipiel dateien zur verfügung stehen die als beispiel musster benutzt werden
// NOTE: Auf die Datei wird zugegriffen und die Anzahl , Beschaffenheit, Reihenfolge der keys übeprüft
?>
</div>