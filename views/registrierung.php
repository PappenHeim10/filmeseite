<?php
namespace mvc;

class Registrierung {
    public $fehler = [];

  public function __construct() {
    // Initialisierung von Fehlern
    $this->fehler = [];
  }

  public function render() {
    // HTML-Code für das Formular generieren

    $output = '<div class="reg">';
    $output .= '<form action="?seite=registrierung&tableName=studenten" method="POST">';
    $output .= '<div class="anrede">';
    $output.= '<label for="anrede">Anrede: </label>';
    $output.= '<select id="anrede" name="anrede">';
    $output.= '<option value="Frau">Frau</option>';
    $output.= '<option value="Herr">Herr</option>';
    $output.= '<option value="Divers">Divers</option>';
    $output.= '</select>';
    $output.= '<div></div>';
    $output.= '</div>';
    if(isset($this->fehler['db'])){
      $output.= "<span class='error'>". $this->fehler['db']. "</span><br>";
    }
    $output.= '<div>';
    $output.= '<div class="vorname">';
    $output.= '<label for="vorname">Vorname:</label>';
    $output.= '<input type="text" id="vorname" name="vorname" placeholder="Vorname" required>';
    $output.= '</div>';
    $output.= '<div class="vornameE">';
    $output.= '</div>';
    $output.= '</div>';
    
    $output.= '<div>';
    $output.= '<div class="nachname">';
    $output.= '<label for="nachname">Nachname:</label>';
    $output.= '<input type="text" id="nachname" name="nachname" placeholder="Nachname" required>';
    $output.= '</div>';
    $output.= '<div class="nachnameE">';
    $output.= '</div>';
    $output.= '</div>';
    
    $output.= '<div>';
    $output .= '<div class="email">';
    $output .= '<label for="email">E-Mail:</label>';
    $output .= '<input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
       title="Bitte geben Sie eine gültige E-Mail-Adresse ein. 
       Die Adresse muss ein @-Symbol und einen Punkt enthalten.">';
    $output.= '</div>';
    $output .= '<div class="emailE">';
    if(isset($this->fehler['emailused'])){
      $output.= "<span class='error'>". $this->fehler['emailused']. "</span><br>";
    }
    if(isset($this->fehler['laenge'])){
      $output.= "<span class='error'>". $this->fehler['laenge']. "</span><br>";
    }
    if(isset($this->fehler['sonderzeichen_lokal'])){
      $output.= "<span class='error'>". $this->fehler['sonderzeichen_lokal']. "</span><br>";
    }
    if(isset($this->fehler['sonderzeichen_domain'])){
      $output.= "<span class='error'>". $this->fehler['sonderzeichen_domain']. "</span><br>";
    }
    if(isset($this->fehler['zahlen_domain'])){
      $output.= "<span class='error'>". $this->fehler['zahlen_domain']. "</span><br>";
    }
    if(isset($this->fehler['laenge_domain'])){
      $output.= "<span class='error'>". $this->fehler['laenge_domain']. "</span><br>";
    }
    if(isset($this->fehler['sonderzeichen_tld'])){
      $output.= "<span class='error'>". $this->fehler['sonderzeichen_tld']. "</span><br>";
    }
    if(isset($this->fehlerfehler['zahlen_tld'])){
      $output.= "<span class='error'>". $this->fehler['zahlen_tld']. "</span><br>";
    }
    if(isset($this->fehler['tld_domain'])){
      $output.= "<span class='error'>". $this->fehler['tld_domain']. "</span><br>";
    }
    $output .= '</div>';
    $output .= '</div>';
    
    $output.= '<div>';
    $output .= '<div class="benutzername">';
    $output.= '<label for="benutzername">Benutzername:</label>';
    $output.= '<input type="text" id="benutzername" name="benutzername" placeholder="Benutzername" required>';
    $output.= '</div>';
    $output.= '<div class="benutzernameE">';
    if (isset($this->fehler['benutzernameused'])) {
      $output.= "<span class='error'>". $this->fehler['benutzernameused']. "</span><br>";
    }
    $output.= '</div>';
    $output.= '</div>';
    
    $output.= '<div>';
    $output .= '<div class="passwort">';
    $output.= '<label for="passwort">Passwort:</label>';
    $output.= '<input type="password" id="passwort" name="passwort" placeholder="Passwort" required>';
    $output.= '</div>';
    $output.= '<div class="passwortE">';
    if (isset($this->fehler['kurz'])) {
      $output.= "<span class='error'>". $this->fehler['kurz']. "</span><br>";
    }
    if (isset($this->fehler['zahl'])) {
      $output.= "<span class='error'>". $this->fehler['zahl']. "</span><br>";
    }
    if (isset($this->fehler['sonderzeichen'])) {
      $output.= "<span class='error'>". $this->fehler['sonderzeichen']. "</span><br>";
    }
    $output.= '</div>';
    $output .= '</div>';
    
    $output.= '<div>';
    $output .= '<div class="pww">';
    $output .= '<label for="pww">Passwort wiederholen:</label>';
    $output .= '<input type="password" id="pww" name="pww" placeholder="Passwort wiederholen" required>';
    $output.= '</div>';
    $output .= '<div class="pwwE">';
    if (isset($this->fehler['stimmennichtu'])) {
      $output.= "<span class='error'>". $this->fehler['stimmennichtu']. "</span><br>";
    }
    $output.= '</div>';
    $output .= '</div>';
    $output .= '<button type="submit">Registrieren</button>';
    $output .= '</form>';
    $output .= '</div>';

    echo $output;
  }
}
$reg = new Registrierung();
$reg->render();


if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $user = new User($_POST);
    $user->insert();
}

?>
