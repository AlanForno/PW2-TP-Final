<?php

class PerfilController
{

    private $model;
    private $printer;
    private $sesion;

    public function __construct($model, $printer, $sesion){
        $this->model = $model;
        $this->printer = $printer;
        $this->sesion = $sesion;
    }

    public function show(){
        $data=$this->sesion->obtenerPermisos();
        $usuario=$this->model->obtenerUsuario($_SESSION["id"]);
        if(is_null($usuario[0]["tipoAceptado"])){
            $usuario[0]["tipoAceptado"]='Realize su chequeo medico';
        }
        $reservas=$this->model->obtenerReservas($_SESSION["id"]);
        $data["reservas"]=$reservas;
        $data["infoUsuario"]=$usuario;
        if($data["sesion"]){

            echo $this->printer->render( "view/perfil.html", $data);
        }else{
            header("Location: /home");
        }
    }

}