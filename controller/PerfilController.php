<?php

class PerfilController
{

    private $model;
    private $printer;
    private $sesion;
    private $data;
    private $PDFPrinter;

    public function __construct($model, $printer, $sesion, $PDFPrinter){
        $this->model = $model;
        $this->printer = $printer;
        $this->sesion = $sesion;
        $this->PDFPrinter = $PDFPrinter;
    }

    public function show(){
        $this->data=$this->sesion->obtenerPermisos();
        $this->cargarDatos();
        if($this->data["sesion"]){

            echo $this->printer->render( "view/perfil.html", $this->data);
        }else{
            header("Location: /home");
        }
    }

    public function cargarDatos(){
        $usuario=$this->model->obtenerUsuario($_SESSION["id"]);
        if(is_null($usuario[0]["tipoAceptado"])){
            $usuario[0]["tipoAceptado"]='Realize su chequeo medico';
        }
        $reservasAcreditadas=$this->model->obtenerReservasAcreditadas($_SESSION["id"]);
        $reservasNoAcreditadas=$this->model->obtenerReservasNoAcreditadas($_SESSION["id"]);
        $this->data["reservasAcreditadas"]=$reservasAcreditadas;
        $this->data["reservasNoAcreditadas"]=$reservasNoAcreditadas;
        $this->data["infoUsuario"]=$usuario;
    }

    public function acreditarPago(){

        $this->model->acreditarPago($_POST["idReserva"]);
        $this->data=$this->sesion->obtenerPermisos();
        $this->cargarDatos();

        if($this->data["sesion"]){
            echo $this->printer->render( "view/perfil.html", $this->data);
            die();
        }else{
            header("Location: /home");
        }
    }

    public function generarComprobante(){
        $id=$_GET["id"];
        $this->PDFPrinter->render("HOLA IAN - ".$id, "documento.pdf", 1);
    }

}