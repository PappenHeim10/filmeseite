<?php
namespace mvc;
?>


<form action="?action=registrierung" method="post">
  <label for="anrede">Anrede:</label>
      <select name="anrede" id="anrede">
          <option value="Herr">Herr</option>
          <option value="Frau">Frau</option>
          <option value="Divers">Divers</option>
      </select><br><br>
      <label for="vorname">Vorname:</label>
      <input type="text" name="vorname" id="vorname" required><br><br>

      <label for="nachname">Nachname:</label>
      <input type="text" name="nachname" id="nachname" required><br><br>

      <label for="email">E-Mail:</label>
      <input type="email" name="email" id="email" required><br><br>

      <label for="benutzername">Benutzername:</label>
      <input type="text" name="benutzername" id="benutzername" required><br><br>

      <label for="passwort">Passwort:</label>
      <input type="password" name="passwort" id="passwort" required><br><br>

      <label for="pww">Passwort wiederholen:</label>
      <input type="password" name="pww" id="pww" required><br><br>

      <input type="submit" value="Registrieren">
</form>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')

{ 
    $user = new User($_POST);
    $user->insert();
}
?>