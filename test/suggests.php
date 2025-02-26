<?php
require_once 'gethint.php';
require_once 'family.php';
?>

<html>
<head>
<script>
function showHint(str) { // Eine Funktion wird deklariert und nimmt einen Text als parameter
  if (str.length == 0) {
    document.getElementById("txtHint").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest(); // Erstellt ein XMLHttpRequest Objekt
    xmlhttp.onreadystatechange = function() { // Erstellt die auszuführende Funktion 
      if (this.readyState == 4 && this.status == 200) { //wenn der Server berweit zu antworten ist
        document.getElementById("txtHint").innerHTML = this.responseText;  // Die Antwort das Servers wird hier ausgegeben
      }
    };
    xmlhttp.open("GET", "gethint.php?q=" + str, true);// Der request wird zu einer anderen datei auf dem server gesendet
    xmlhttp.send();
  }
}


function showUser(str){ 
  if(str == null){
    document.getElementById("txtHint2").innerHTML = ""; // wenn der Parameter leer ist ist die zeile auch leer
    return;
  }
  else{
    var xmlhttp = new XMLHttpRequest(); // XMLHttpRequest wird wieder vorbereitet
    xmlhttp.onreadystatechange = function(){
      if(this.readyState == 4 && this.status == 200){
        document.getElementById("txtHint2").innerHTML = this.responseText;
      }
    };
    xmlhttp.open('GET', 'family.php?q=' + str, true);
    xmlhttp.send();
  }
}
</script>
</head>

<body>
<p><b>Start typing a name in the input field below:</b></p>
<form action="">
  <label for="fname">First name:</label>
  <input type="text" id="fname" name="fname" onkeyup="showHint(this.value)">
</form>
<p>Suggestions: <span id="txtHint"></span></p>
<p>Suggestions 2: <span id="txtHint2"></span></p>
</body>
<form action="" method="get">
    <label for="suche">Suche</label><br>
    <input type="text" name="suche">
    <input type="button" value="Suchen">
</form>
