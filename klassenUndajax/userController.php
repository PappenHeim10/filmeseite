<?php
namespace mvc;
require_once 'include/datenbank.php';

class UserController{
    use Validation;
    private $UserModel;


    public function __construct(){
        $this->UserModel = new User();
    }

    public function registrierung(){
        
    }

}



?>