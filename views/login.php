
<form action="?action=login" method="post">
    <fieldset>
    <legend>Login</legend>
        <div>
            <label for="benutzername">Benutzername:</label>
            <input type="text" name="benutzername">
        </div>
        <div>
            <label for="passwort">Passwort:</label>
            <input type="password" name="passwort">
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </fieldset>
</form>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //DO!: Zuende schreiben
}
?>


    


