<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $userController->registrierung(); //
}
?>

<form action="" method="post">
    <fieldset class="registrierung">
        <legend>Registrierung</legend>
        <div class="registrierungBox">
            <div>
                <label for="anrede">Anrede:</label>
                <select name="anrede" id="anrede">
                    <option value="Herr" <?php if (isset($daten['anrede']) && $daten['anrede'] === 'Herr') echo 'selected'; ?>>Herr</option>
                    <option value="Frau" <?php if (isset($daten['anrede']) && $daten['anrede'] === 'Frau') echo 'selected'; ?>>Frau</option>
                    <option value="Divers" <?php if (isset($daten['anrede']) && $daten['anrede'] === 'Divers') echo 'selected'; ?>>Divers</option>
                </select><br><br>
            </div>

            <div>
                <label for="vorname">Vorname:</label>
                <input type="text" name="vorname" id="vorname" required autocomplete="given-name" value="<?php echo isset($daten['vorname']) ? htmlspecialchars($daten['vorname']) : ''; ?>"><br><br>
                <div>
                    <?php if(isset($fehler['vorname'])) echo "<span class='error'>" . htmlspecialchars($fehler['vorname']) . "</span>"; ?>
                </div>
            </div>

            <div>
                <label for="nachname">Nachname:</label>
                <input type="text" name="nachname" id="nachname" required autocomplete="family-name" value="<?php echo isset($daten['nachname']) ? htmlspecialchars($daten['nachname']) : ''; ?>"><br><br>
                <div>
                     <?php if(isset($fehler['nachname'])) echo "<span class='error'>" . htmlspecialchars($fehler['nachname']) . "</span>"; ?>
                </div>
            </div>

            <div>
                <label for="email">E-Mail:</label>
                <input type="email" name="email" id="email" required autocomplete="email" value="<?php echo isset($daten['email']) ? htmlspecialchars($daten['email']) : ''; ?>"><br><br>
                <div>
                    <?php if(isset($fehler['email'])) echo "<span class='error'>" . htmlspecialchars($fehler['email']) . "</span>"; ?>
                </div>
            </div>

            <div>
                <label for="benutzername">Benutzername:</label>
                <input type="text" name="benutzername" id="benutzername" required autocomplete="username" value="<?php echo isset($daten['benutzername']) ? htmlspecialchars($daten['benutzername']) : ''; ?>"><br><br>
                <div>
                    <?php if(isset($fehler['benutzername'])) echo "<span class='error'>" . htmlspecialchars($fehler['benutzername']) . "</span>"; ?>
                </div>
            </div>

            <div>
                <label for="passwort">Passwort:</label>
                <input type="password" name="passwort" id="passwort" required autocomplete="new-password"><br><br>
                <div>
                    <?php if(isset($fehler['kurz'])) echo "<span class='error'>" . htmlspecialchars($fehler['kurz']) . "</span>"; ?>
                </div>
            </div>

            <div>
                <label for="pww">Passwort wiederholen:</label>
                <input type="password" name="pww" id="pww" required autocomplete="new-password"><br><br>
                <div>
                    <?php if(isset($fehler['pww'])) echo "<span class='error'>" . htmlspecialchars($fehler['pww']) . "</span>"; ?>
                </div>
            </div>
            <div><input type="submit" value="Registrieren"></div>
        </div>
    </fieldset>
</form>
