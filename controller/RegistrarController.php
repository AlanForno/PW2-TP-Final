<?php
class RegistrarController{

    private $registrarModel;
    private $printer;

    public function __construct($registrarModel, $printer){
        $this->registrarModel = $registrarModel;
        $this->printer = $printer;
    }

    public function show(){

        echo $this->printer->render( "view/registrar.html");
    }

    public function registrarUsuario(){
        $data["usuario"] = $_POST["usuario"];
        $data["password"] =  $_POST["password"];
        $this->registrarModel->registrarUsuario($data["usuario"],$data["password"]);
        header("Location: /home");
        die();

    }
}