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


?>