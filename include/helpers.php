<?php
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
	function insert():bool;
	function update(int $i):bool;
	function delete(int $i):bool;
	function select(int $i):array|false;
	function selectAll():array|false;
}



trait Validation { // DO!: Die Validierung zuende Kommentieren

    private function validateUser(array $daten):bool|array
    {
        $fehler = [];
        
        // Anrede
        if (empty($daten['anrede']) || !in_array($daten['anrede'], ['Herr', 'Frau', 'Divers'])) {
            $fehler['anrede'] = "Ungültige Anrede.";
        }

        // Email
        if (is_array($this->validiereEmail($daten['email']))) {
            $fehler[] = $this->validiereEmail($daten['email']);
        }

        // Benutzername
        if (empty($daten['benutzername']) || strlen($daten['benutzername']) < 5) {
            $fehler['benutzernamen_leange'] = "Benutzername muss mindestens 5 Zeichen lang sein.";
        }

        // Passwort
       if (is_array($this->validirePasswort($daten['passwort']))) {
            $fehler[] = $this->validirePasswort($daten['passwort']);

            if(empty($fehler)){
                if($daten['passwort'] != $daten['pww']){
                    $fehler['pww'] = "Die Passwörter stimmen nicht überein.";
                }
                
            }
        }
        if(empty($fehler)){
            return true;
        }else{
            return $fehler;
        }
    }

    private function validirePasswort($passwort) :array|bool
    {
    
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

        if(empty($fehler)){
            return true;
        }else{
            foreach($fehler as $fehl){
                hinweis_log($fehl);
            }
            return $fehler;
        }
    }

    private function validiereEmail($email):array|bool
    {
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
        if(empty($fehler)){
            return true;
        }else{
            foreach($fehler as $fehl){
                hinweis_log($fehl);
            }
            return $fehler;
        }
    }

    private function bereinigen($data):string // OPTIM: Bin mir nich ganz sicher wann ich diese Funktionenn benutzez kann
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        return $data;
    }

    private function test_input($data) :String{
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
}

?>