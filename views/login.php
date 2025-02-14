
<form action="?action=login" method="post">
    <label for="benutzername">Benutzername:</label>
    <input type="text" name="benutzername">
    <label for="passwort">Passwort:</label>
    <input type="password" name="passwort">
    <input type="submit" value="Login">
</form>


<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $benutzername = $_POST['benutzername'];
    $passwort = $_POST['passwort'];
    $user = new User();
    $user->einLoggen($benutzername, $passwort);

    if(empty($fehler)){
        header('Location: index.php');
        exit;
    }else {
        $_SESSION['error'] = $fehler;
    }
}
?>