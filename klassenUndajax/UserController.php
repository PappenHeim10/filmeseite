<?php
namespace mvc;

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
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
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


