<?php

class PerfilController
{

    private $model;
    private $printer;
    private $sesion;
    private $data;
    private $PDFPrinter;
    private $mail;

    public function __construct($model, $printer, $sesion, $PDFPrinter, $mail){
        $this->model = $model;
        $this->printer = $printer;
        $this->sesion = $sesion;
        $this->PDFPrinter = $PDFPrinter;
        $this->mail = $mail;
    }

    public function show(){

        $this->data=$this->sesion->obtenerPermisos();
        $this->cargarDatos();
        if($this->data["sesion"]){
            if(isset($_GET['exito'])){
                $this->data["exito"]=$_GET['exito'];
            }
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

        $this->data=$this->sesion->obtenerPermisos();
        if($this->data["sesion"]){

            if($this->validarDatos($_POST["cardname"], $_POST["cardnumber"], $_POST["expmonth"], $_POST["expyear"], $_POST["cvv"] )){
                $this->model->acreditarPago($_POST["idReserva"]);
                $this->generarComprobante($_POST["idReserva"]);
                $this->data["exitoCompra"]=true;
            }else{
                $this->data["errorCompra"]=true;
            }
            $this->cargarDatos();
            echo $this->printer->render( "view/perfil.html", $this->data);

        }else{
            header("Location: /home");
        }
    }

    public function generarComprobante($idReserva){
        $idUsuario=$_SESSION["id"];
        $attachment = $this->model->CargaDatosDeComprobante($idUsuario,$idReserva, 0); /**FALTA $OPCIONES */
        $data = $this->model->obtenerUsuario($idUsuario);
        $body ="Boarding Pass";

        $this->mail->EnviarMailConArchivo($data[0]["email"],"Boarding Pass",
        $body, $data[0]["usuario"] ,$attachment, "Boarding Pass.pdf");

    }

    public function imprimirBoardingPass(){
        $idReserva= $_GET["idReserva"];
        $idUsuario=$_SESSION["id"];
        $this->model->CargaDatosDeComprobante($idUsuario,$idReserva, 1);


    }
    public function darDeBajaReserva(){
        $idReserva=$_GET['id'];
        $cabina=$_GET['cabina'];
        $aeronave=$_GET['aeronave'];
        $asiento=$_GET['asiento'];
        $idVuelo=$_GET['idVuelo'];
        $this->model->darDeBajaReserva($idReserva,$cabina,$aeronave,$asiento,$idVuelo);
        header("location: /perfil?exito=true");
    }
    public function darDeBajaReservaEnEspera(){
        $idReserva=$_GET['id'];
        $this->model->darDeBajaReservaEnEspera($idReserva);
        header("location: /perfil?exito=true");
    }

    private function validarDatos($cardname, $cardnumber, $expmonth, $expyear, $cvv)
    {
        $validacion=0;
        if(strcmp($cardname, "Agustin Martinez") == 0){
            $validacion++;
        }
        if($cardnumber == 5031755734538923){
            $validacion++;
        }
        if(strcmp($expmonth, "Diciembre") == 0){
            $validacion++;
        }
        if($expyear == 2025){
            $validacion++;
        }
        if($cvv == 590){
            $validacion++;
        }

        if ($validacion == 5){
            return true;
        }else{
            return false;
        }
    }


}