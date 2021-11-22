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
        $reservasEnEspera=$this->model->obtenerReservasEnEspera($_SESSION["id"]);
        $this->data["reservasEnEspera"]=$reservasEnEspera;
        $this->data["reservasAcreditadas"]=$reservasAcreditadas;
        $this->data["reservasNoAcreditadas"]=$reservasNoAcreditadas;
        $this->data["infoUsuario"]=$usuario;
    }

    public function acreditarPago(){

        $this->model->acreditarPago($_POST["idReserva"]);
        $this->data=$this->sesion->obtenerPermisos();
        $this->cargarDatos();

        //Falta validar los datos y enviar un mensaje de exito o error

        if($this->data["sesion"]){
            echo $this->printer->render( "view/perfil.html", $this->data);
            die();
        }else{
            header("Location: /home");
        }
    }

    public function generarComprobante(){
        $id=$_GET["id"];
        $html= file_get_contents("view/BoardingPass.html") ;
        //echo var_dump($html);
        $this->PDFPrinter->render($html, "documento.pdf", 0);
    }
    public function darDeBajaReserva(){
        $idReserva=$_GET['id'];
        $cabina=$_GET['cabina'];
        $aeronave=$_GET['aeronave'];
        $asiento=$_GET['asiento'];
        $idVuelo=$_GET['idVuelo'];
        $this->model->darDeBajaReserva($idReserva,$cabina,$aeronave,$asiento,$idVuelo);
    }
    public function darDeBajaReservaEnEspera(){
        $idReserva=$_GET['id'];
        $this->model->darDeBajaReservaEnEspera($idReserva);
    }

}