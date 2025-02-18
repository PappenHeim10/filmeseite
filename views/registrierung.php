

<form action="?action=registrierung" method="post">
    <fieldset class="registrierung">
        <legend>Registrierung</legend>
        <div class="registrierungBox">
        <div>
        <label for="anrede">Anrede:</label>
        <select name="anrede" id="anrede">
            <option value="Herr">Herr</option>
            <option value="Frau">Frau</option>
            <option value="Divers">Divers</option>
        </select><br><br>
        </div>

        <div>
        <label for="vorname">Vorname:</label>
        <input type="text" name="vorname" id="vorname" required autocomplete="given-name"><br><br>
        </div>

        <div>
        <label for="nachname">Nachname:</label>
        <input type="text" name="nachname" id="nachname" required autocomplete="family-name"><br><br>
        </div>

        <div>
        <label for="email">E-Mail:</label>
        <input type="email" name="email" id="email" required autocomplete="email"><br><br>
        </div>

        <div>
        <label for="benutzername">Benutzername:</label>
        <input type="text" name="benutzername" id="benutzername" required autocomplete="username"><br><br>
        </div>

        <div>
        <label for="passwort">Passwort:</label>
        <input type="password" name="passwort" id="passwort" required autocomplete="new-password"><br><br>
        </div>

        <div>
        <label for="pww">Passwort wiederholen:</label>
        <input type="password" name="pww" id="pww" required autocomplete="new-password"><br><br>
        </div>

        <div><input type="submit" value="Registrieren"></div>
        </div>
    </fieldset>
</form>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')

{ 
    $user = new User($_POST);
	$user->registrierung();

    write_error($user->validiereEmail);
}
?>