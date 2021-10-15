<?php
class LoginController{

    private $logginModel;
    private $printer;

    public function __construct($loginModel, $printer){
        $this->logginModel = $loginModel;
        $this->printer = $printer;
    }

    public function show(){

        echo $this->printer->render( "view/iniciarSesion.html");
    }
}