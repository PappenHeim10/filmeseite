<?php
namespace mvc;

class Schauspieler{
    use GetterSetter;
    private $id;
    private $name;
    function __construct(Array $daten = []){
        $this->setDaten($daten);
    }
    public function setDaten($daten){

    }
     
}



?>