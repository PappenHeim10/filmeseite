

<form action="?action=registrierung" method="post">
    <fieldset>
        <legend>Registrierung</legend>

        <label for="anrede">Anrede:</label>
        <select name="anrede" id="anrede">
            <option value="Herr">Herr</option>
            <option value="Frau">Frau</option>
            <option value="Divers">Divers</option>
        </select><br><br>

        <label for="vorname">Vorname:</label>
        <input type="text" name="vorname" id="vorname" required autocomplete="given-name"><br><br>

        <label for="nachname">Nachname:</label>
        <input type="text" name="nachname" id="nachname" required autocomplete="family-name"><br><br>

        <label for="email">E-Mail:</label>
        <input type="email" name="email" id="email" required autocomplete="email"><br><br>

        <label for="benutzername">Benutzername:</label>
        <input type="text" name="benutzername" id="benutzername" required autocomplete="username"><br><br>

        <label for="passwort">Passwort:</label>
        <input type="password" name="passwort" id="passwort" required autocomplete="new-password"><br><br>

        <label for="pww">Passwort wiederholen:</label>
        <input type="password" name="pww" id="pww" required autocomplete="new-password"><br><br>

        <input type="submit" value="Registrieren">
    </fieldset>
</form>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')

{ 
    $user = new User($_POST);
    write_error($user->validiereEmail);
}
?>