<?php
class LoginController{

    private $loginModel;
    private $printer;

    public function __construct($loginModel, $printer){
        $this->loginModel = $loginModel;
        $this->printer = $printer;
    }

    public function show(){

        echo $this->printer->render( "view/iniciarSesion.html");
    }
    public function procesarLogin(){
        $data["usuario"] = $_POST["usuario"];
        $data["password"] =  $_POST["password"];
        $this->loginModel->logearUsuario($data["usuario"],$data["password"]);
    }

}