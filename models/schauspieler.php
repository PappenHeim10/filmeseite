<?php
namespace mvc;

class Schauspieler{
    use GetterSetter;
    private $id;
    private $vorname;
    private $nachname;
    function __construct(Array $daten = [])
    {

        $this->setDaten($daten);
    }

    public function setDaten(array $daten)
    {
        if ($daten) {
            foreach ($daten as $key => $value) {
                $propertyName = strtolower($key); // Konvertiere den Schlüssel in Kleinbuchstaben

                // Überprüfe, ob die Eigenschaft existiert, bevor du sie setzt.
                if (property_exists($this, $propertyName)) {
                     $this->$propertyName = $value; // Dynamisch die Eigenschaft setzen
                }
            }
        }
    }
    
}



?>