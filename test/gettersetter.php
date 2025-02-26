<?php
namespace mvc;
include_once '../include/namespace.php';

class TestKlasse {
    use \GetterSetter;

    private $attribut;

    public function __construct($param) {
        $this->attribut = $param;
    }

    public function render() {
        echo "<div>Initial value: " . $this->attribut . "</div>"; // Access using __get

        $this->attribut = "New Value"; // Set using __set

        echo "<div>Value after setting: " . $this->attribut . "</div>"; // Access again

        // More explicit test
        echo "<div>Explicit get: " . $this->__get($this->attribut) . "</div>";
         if(method_exists($this, 'setAttribut')){
          $this->__set($this->attribut,'setter test');
          echo "<div>Explicit set" . $this->attribut . "</div>";

         }
    }
}


$test = new TestKlasse('Initial Value');
$test->render();
?>
