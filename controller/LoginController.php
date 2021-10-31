<?php
class LoginController{

    private $loginModel;
    private $printer;

    public function __construct($loginModel, $printer){
        $this->loginModel = $loginModel;
        $this->printer = $printer;
    }

    public function show(){

        if (!isset($_SESSION["rol"])) {
            $data["error"] = false;
            echo $this->printer->render("view/iniciarSesion.html", $data);
        }else{
            header("Location: /home");
        }
    }


    public function procesarLogin(){
        $data["usuario"] = $_POST["usuario"];
        $data["password"] =  $_POST["password"];
        if($this->loginModel->logearUsuario($data["usuario"],$data["password"])){
            $this->iniciarSesion($data["usuario"]);
            header("Location: /home");
            die();
        }else{
            $data["error"]=true;
            echo $this->printer->render( "view/iniciarSesion.html", $data);
        }
    }

    public function iniciarSesion($usuario){
        $this->loginModel->iniciarSesion($usuario);

    }

    public function cerrarSesion(){
        session_destroy();
        header("Location: /home");
        die();

    }

}