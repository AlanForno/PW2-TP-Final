<?php


class vuelosController
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
        $data["vuelos"]=$this->model->obtenerVuelos();
        echo $this->printer->render( "view/vuelosCliente.html", $data);
    }
    public function reserva(){

        $data=$this->sesion->obtenerPermisos();
        $data["vuelos"]=$this->model->obtenerVuelos();
        if($data["sesion"]==false && $data["admin"]==false){
            $data["error"]=true;
            echo $this->printer->render("view/vuelosCliente.html", $data);
        }else {
            header("Location: /reserva");
        }
    }
}