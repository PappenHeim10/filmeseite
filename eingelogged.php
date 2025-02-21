<div class="registrierung">
<h1>Erforlgreich eingelogged!!</h1>
</div>
<?php
session_start(); // Die Session muss gestarted werden bevor man auf die Session Variablen zugreifen kann
$_SESSION['loggedIn'] = true;// 
?>

<script>
setTimeout(function() {
    window.location.href = "index.php";
}, 2000);
</script>
