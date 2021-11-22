<?php


class vuelosController
{
    private $model;
    private $printer;
    private $sesion;
    private $mail;


    public function __construct($model, $printer, $sesion, $mail){
        $this->model = $model;
        $this->printer = $printer;
        $this->sesion = $sesion;
        $this->mail = $mail;
    }


       public function show(){
        $data=$this->sesion->obtenerPermisos();
        if(isset($_GET['exito'])){
            $data["exito"]=$_GET['exito'];
        }
        $data["vuelos"]=$this->model->obtenerVuelos();
        $data["origen"]=$this->model->obtenerOrigenes();

        $data["destino"]=$this->model->obtenerDestinos();

        $data["fecha"]=$this->model->obtenerFechas();

        echo $this->printer->render( "view/vuelosCliente.html", $data);
    }
    public function vuelosDisponibles(){

        $data=$this->sesion->obtenerPermisos();

        if($data["sesion"]==false && $data["admin"]==false){
            $data["error"]=true;
            echo $this->printer->render("view/vuelosCliente.html", $data);
        }else {
            $idVuelo=$_POST["idVuelo"];
            $data['vuelo']=$this->model->obtenerVuelosPorId($idVuelo);
            echo $this->printer->render("view/reservaVuelo.html", $data);

        }
    }
    public function procesarReserva(){
        $data=$this->sesion->obtenerPermisos();
        $idUsuario=$_SESSION["id"];
        $idVuelo=$_POST["idVuelo"];
        $asiento=$_POST["asiento"];
        $cabina=$_POST["cabina"];
        $data['vuelo']=$this->model->obtenerVuelosPorId($idVuelo);

        if(!$this->model->procesarReserva($idVuelo,$idUsuario,$asiento,$cabina)){
            $data["error"]=true;
            echo $this->printer->render("view/reservaVuelo.html", $data);
        }else {
            $attachment = $this->model->ProcesarPdfReserva($idVuelo);
            $data = $this->model->datosUsuario($idUsuario);
            $this->mail->EnviarMailConArchivo($data[0]["email"],"Comprobante reserva",
                "Comprobate de reserva del vuelo",$data[0]["usuario"] ,$attachment, "Comprobante reserva.pdf");
            header("location:http://localhost/vuelos?exito=true");
        }
    }
    public function buscarVuelosFiltrados(){
        $data=$this->sesion->obtenerPermisos();
        $origen=$_POST['origen'];
        $destino=$_POST['destino'];
        $fecha=$_POST['fecha'];
        $data['vuelos']=$this->model->getVuelosFiltradosPor($origen,$destino,$fecha);
        $data["origen"]=$this->model->obtenerOrigenes();
        $data["destino"]=$this->model->obtenerDestinos();
        $data["fecha"]=$this->model->obtenerFechas();
        echo $this->printer->render("view/vuelosCliente.html", $data);
    }
    public function agregarReservaEnEspera(){
        $idVuelo=$_GET['id'];
        $this->model->agregarReservaEnEspera($idVuelo,$_SESSION['id']);
        header("location:http://localhost/vuelos?exito=true");
    }
}
