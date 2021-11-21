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
        $reservasAcreditadas=$this->model->obtenerReservasAcreditadas($_SESSION["id"]);
        $reservasNoAcreditadas=$this->model->obtenerReservasNoAcreditadas($_SESSION["id"]);
        $data["reservasAcreditadas"]=$reservasAcreditadas;
        $data["reservasNoAcreditadas"]=$reservasNoAcreditadas;
        $data["infoUsuario"]=$usuario;
        if($data["sesion"]){

            echo $this->printer->render( "view/perfil.html", $data);
        }else{
            header("Location: /home");
        }
    }

}