<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $fehler = $userController->login($_POST);
}
?>


<form action="?action=login" method="post">
    <fieldset>
    <legend>Login</legend>
        <div>
            <label for="benutzername">Benutzername:</label>
            <input required type="text" name="benutzername">
        </div>
        <div>
            <label for="passwort">Passwort:</label>
            <input required type="password" name="passwort">
        </div>
        <div>
            <?php if(isset($fehler['login'])) echo "<span class='error'>". htmlspecialchars($fehler['login']) ."</span>"; ?>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </fieldset>
</form>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
}
?>


    


