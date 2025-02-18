
<form action="?action=login" method="post">
    <label for="benutzername">Benutzername:</label>
    <input type="text" name="benutzername">
    <label for="passwort">Passwort:</label>
    <input type="password" name="passwort">
    <input type="submit" value="Login">
</form>



<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $user = new User($_POST);
    if($user->login() == false){
        echo "<script>function login(){alert('Login Fehlgeschlagen');}login();</script>";
    }else{
        $_SESSION['benutzername'] = $user->benutzername;
        $_SESSION['loggedIn'] = true;
        header('Location: index.php');
        
        exit;
    }
}
?>


    


