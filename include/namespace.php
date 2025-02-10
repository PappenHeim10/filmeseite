<?php
namespace mvc;
require_once 'functions.php';



trait GetterSetter {  
    /**
     * Diese Funktion wird automatisch aufgerufen, wenn auf eine Eigenschaft
     * des Objekts zugegriffen wird, die nicht direkt zugänglich ist (z.B. private).
     * Sie dient als "Getter" (Holer) für den Wert der Eigenschaft.
     *
     * @param string $attribut Der Name der Eigenschaft, auf die zugegriffen werden soll.
     * @return mixed Der Wert der Eigenschaft, falls sie existiert und zugänglich ist.
     *               Andernfalls wird kein Wert zurückgegeben (oder ein Fehler ausgelöst).
     */
    public function __get($attribut) { 
        // Überprüft, ob die Eigenschaft tatsächlich in der Klasse existiert.
        if (property_exists($this, $attribut)) {
            return $this->$attribut; // Gibt den Wert der Eigenschaft zurück.
        }
        // Hier könnte man optional eine Ausnahme auslösen oder einen Fehler protokollieren,
        // wenn die Eigenschaft nicht gefunden wurde.
    }

    /**
     * Diese Funktion wird automatisch aufgerufen, wenn versucht wird,
     * einer Eigenschaft des Objekts einen Wert zuzuweisen, die nicht direkt
     * zugänglich ist (z.B. private). Sie dient als "Setter" (Setzer) für den Wert.
     *
     * @param string $attribut Der Name der Eigenschaft, der ein Wert zugewiesen werden soll.
     * @param mixed $value Der Wert, der der Eigenschaft zugewiesen werden soll.
     * @return void
     */
    public function __set($attribut, $value) {
        // Überprüft, ob die Eigenschaft tatsächlich in der Klasse existiert.
        if (property_exists($this, $attribut)) {
            $this->$attribut = $value; // Setzt den Wert der Eigenschaft.
        }
        // Auch hier könnte man optional eine Ausnahme auslösen oder einen Fehler protokollieren,
        // wenn die Eigenschaft nicht gefunden wurde.
    }
}


interface iDatenbank
{
	function insert();
	function update($i);
	function delete($i);
	function select($i);
	function selectAll();
}

trait Validation {

    public function schonVergeben($anzahl){
        $fehler = [];

        if ($anzahl['emailCount'] > 0) {
            $fehler['emailused'] = "Diese E-Mail-Adresse ist bereits registriert.";
        }
        if ($anzahl['benutzernameCount'] > 0) {
            $fehler['benutzernameused'] = "Dieser Benutzername ist bereits vergeben.";
        }
        return $fehler;
    }

    function validirePasswort($passwort) {
    
        $fehler = [];
      
        if(strlen($passwort) < 8) {
          $fehler['kurz'] = "Das Passwort muss mindestens 8 Zeichen lang sein.";
        }
        if(!preg_match("/[0-9]/", $passwort)) {
          $fehler['zahl'] = "Das Passwort muss mindestens eine Zahl enthalten.";
        }
        if(!preg_match("/[^a-zA-Z0-9]/", $passwort)) {
          $fehler['sonderzeichen'] = "Das Passwort muss mindestens ein Sonderzeichen enthalten.";
        }
        return $fehler; 
      }

    public function validiereEmail($email){

        $fehler = [];
        if(strlen($email) < 3){
            $fehler['laenge'] = "Die E-Mail-Adresse muss mindestens 3 Zeichen lang sein.";
        }

        $lokalerTeil = explode('@', $email)[0];
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $lokalerTeil)) {
          $fehler['sonderzeichen_lokal'] = "Der lokale Teil der E-Mail-Adresse darf nur Buchstaben, Zahlen, _ und - enthalten.";
        }

        $domain = explode('@', $email)[1];
        if (!preg_match('/^[a-zA-Z.-]+$/', $domain)) { 
            $fehler['sonderzeichen_domain'] = "Der Domainname darf keine Sonderzeichen enthalten aus einem . und -.";
        }
          // Keine Zahlen im Domainnamen
        if (preg_match('/[0-9]/', $domain)) {
            $fehler['zahlen_domain'] = "Der Domainname darf keine Zahlen enthalten.";
        }

        // Mindestlänge Domainname 3 Zeichen
        if (strlen($domain) < 3) {
            $fehler['laenge_domain'] = "Der Domainname muss mindestens 3 Zeichen lang sein.";
        }

        // Top-Level-Domain (TLD)
        $domainParts = explode('.', $domain);
        if (count($domainParts) > 1) {
            $tld = $domainParts[1];
        } else {
            // Handle the case where there's no TLD (e.g., set a default, or add an error)
            $tld = null; // Or perhaps $tld = "invalid"; 
            $fehler['tld_domain'] = 'Die E-Mail-Adresse muss einen Punkt beinhalten.';
        }

        
        // Mindestlänge TLD 2 Zeichen
        if($tld){
            if (strlen($tld) < 2) {
            $fehler['laenge_tld'] = "Die Top-Level-Domain muss mindestens 2 Zeichen lang sein.";
            }
            if (!preg_match('/^[a-zA-Z]+$/', $tld)) {
                $fehler['sonderzeichen_tld'] = "Die Top-Level-Domain darf keine Sonderzeichen enthalten.";
            }
            if (preg_match('/[0-9]/', $tld)) {
                $fehler['zahlen_tld'] = "Die Top-Level-Domain darf keine Zahlen enthalten.";
            }

        }
        return $fehler;
    }


    public function bereinigen($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
?>