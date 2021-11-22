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
        $data["vuelos"]=$this->model->obtenerVuelos();
        echo $this->printer->render( "view/vuelosCliente.html", $data);

    }
    public function vuelosDisponibles(){

        $data=$this->sesion->obtenerPermisos();
        $data["vuelos"]=$this->model->obtenerVuelos();
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
        $idUsuario=$_SESSION["id"];
        $idVuelo=$_POST["idVuelo"];
        $asiento=$_POST["asiento"];
        $cabina=$_POST["cabina"];
        $data['vuelo']=$this->model->obtenerVuelosPorId($idVuelo);

        if(!$this->model->procesarReserva($idVuelo,$idUsuario,$asiento,$cabina)){
            $data["error"]=true;
            echo $this->printer->render("view/reservaVuelo.html", $data);
        }else {
            echo "RESERVASTE EL PASAJE";
            // aca poner lo que se haga con el pdf .

            $attachment = $this->model->ProcesarPdfReserva($idVuelo);
            $data = $this->model->datosUsuario($idUsuario);
       
            $this->mail->EnviarMailConArchivo($data[0]["email"],"Comprobante reserva",
             "Comprobate de reserva del vuelo",$data[0]["usuario"] ,$attachment, "Comprobante reserva.pdf");

        }
    }
}
