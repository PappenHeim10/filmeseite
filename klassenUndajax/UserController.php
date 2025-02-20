<?php
namespace mvc;
?>
<pre> 
    <?php
    print_r($_POST);
    ?>
</pre>
<?php
class UserController
{
    use \Validation;
    private $userModel;
    // Die Validation-Trait ist in namespace.php

    public function __construct()
    {
        $this->userModel = new User(); // Instanz der User-Klasse (dein Model)
    }

    public function registrierung()
    {
        $fehler = []; //
        $daten = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST'){ // Post
            $daten  = $_POST;
            $user = new User($daten);
            
            $fehler = $this->validateUser($daten); // Hier findet die gesamte Validierung statt
            if($this->userModel->benutzernameExistiert($daten['benutzername']))
            {
                $fehler['ver_benutzername'] = "Benutzername existiert bereits.";
            }
            if($this->userModel->emailExistiert($daten['email']))
            {
                $fehler['ver_email'] = "Email existiert bereits.";
            }

            if (empty($fehler)) {
                if ($user->insert()) { 
                   header('Location: registriert.php');
                    exit;
                } else {
                    $fehler['speicherung'] = "Fehler beim Speichern des Benutzers.";
                }
            }
        }
    foreach($fehler as $fehl){
        hinweis_log($fehl . ", ". __METHOD__);
    }
    return $fehler;
    }

    private function validateUser(array $daten):array
    {
        $fehler = [];
        
        // Anrede
        if (empty($daten['anrede']) || !in_array($daten['anrede'], ['Herr', 'Frau', 'Divers'])) {
            $fehler['anrede'] = "Ungültige Anrede.";
        }

        // Email
        if (is_array($this->validiereEmail($daten['email']))) {
            $fehler[] = $this->validiereEmail($daten['email']);
        }

        // Benutzername
        if (empty($daten['benutzername']) || strlen($daten['benutzername']) < 5) {
            $fehler['benutzernamen_leange'] = "Benutzername muss mindestens 5 Zeichen lang sein.";
        }

        // Passwort
       if (is_array($this->validirePasswort($daten['passwort']))) {
            $fehler[] = $this->validirePasswort($daten['passwort']);

            if(empty($fehler)){
                if($daten['passwort'] != $daten['pww']){
                    $fehler['pww'] = "Die Passwörter stimmen nicht überein.";
                }
            }
        }
        return $fehler;
    }

    public function listeBenutzer()
    {
        $users = $this->userModel->selectAll();
        include 'views/benutzerliste.php'; // DO!: Der path muss von der Adminseite ausgehen
    }

    public function loescheBenutzer($id)
    {
       // Keine Formulardaten, also direkt löschen
        if ($this->userModel->delete($id)) {
            header('Location: benutzerliste.php?delete_erfolgreich=1'); // Oder andere Erfolgsseite
            exit;
        } else {
            // Fehler beim Löschen (z.B. Benutzer existiert nicht)
             header('Location: benutzerliste.php?delete_fehlgeschlagen=1');
              exit;
        }
    }
    public function zeigeBenutzer($id) // WICHTIG: Dieser Funktion fehlt ein View.
    {
        $user = $this->userModel->select($id);
        if ($user) {
            // User-Daten an die View übergeben
            include 'views/zeige_benutzer.php'; // View erstellen!
        } else {
            // Fehlerbehandlung (z.B. 404-Seite)
              header('Location: 404.php'); // Fehlerseite
              exit;
        }
    }
}

?>



<pre> 
    <?php
    print_r($_POST);
    ?>
</pre>