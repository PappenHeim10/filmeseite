<?php
namespace mvc;

class UserController
{
    use \Validation;
    private User $userModel;
    // Die Validation-Trait ist in namespace.php

    public function __construct()
    {
        $this->userModel = new User(); // Instanz der User-Klasse (dein Model)
    }

    public function registrierung():array|bool
    {
        $fehler = [];
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $daten  = $_POST;
            $user = new User($daten);
            
            $fehler = $this->validateUser($daten);
            if($this->userModel->benutzernameExistiert($daten['benutzername']))
            {
                $fehler['ver_benutzername'] = "Benutzername existiert bereits.";
            }
            if($this->userModel->emailExistiert($daten['email']))
            {
                $fehler['ver_email'] = "Email existiert bereits.";
            }

            if ($fehler == true) {
                if ($user->insert()) { 
                    header('Location: registriert.php');
                    exit;
                } else {
                    write_error("Fehler beim Speichern des Benutzers.");
                }
            }
        }
        return $fehler;
    }

    private function validateUser(array $daten):array|bool
    {
        $fehler = [];
        
        // Anrede
        if (empty($daten['anrede']) || !in_array($daten['anrede'], ['Herr', 'Frau', 'Divers'])) {
            $fehler['anrede'] = "Ungültige Anrede.";
        }

        // Email
        if (is_array($this->validiereEmail($daten['email']))) {
            $fehler['email'] = $this->validiereEmail($daten['email']);
        }

        // Benutzername
        if (empty($daten['benutzername']) || strlen($daten['benutzername']) < 5) {
            $fehler['benutzernamen_leange'] = "Benutzername muss mindestens 5 Zeichen lang sein.";
        }

        // Passwort
       if (is_array($this->validirePasswort($daten['passwort']))) {
            $fehler['passwort'] = $this->validirePasswort($daten['passwort']);
       }

        if($daten['passwort'] != $daten['pww']){
            $fehler['pww'] = "Die Passwörter stimmen nicht überein.";
        }

        if(empty($fehler)){
            return true;
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


    public function login(array $daten){
        $user = $this->userModel->benutzernameExistiert($daten['benutzername']);
        if($user){
            $user = $this->userModel->giveUserInfo($daten['benutzername']);

            if(password_verify($daten['passwort'], $user['passwort'])){
                $_SESSION['user_id'] = $user;//OPTIM:Die user_id in die Session_variabel tun. 
                header('Location: eingelogged.php');
                exit;
            }
        }
        $fehler['login'] = "Benutzername oder Passwort falsch.";
        return $fehler;
    }
}
?>
