<?php

class reservaController{

    private $registrarModel;
    private $printer;
    private $sesion;

    public function __construct($registrarModel, $printer, $sesion){
        $this->registrarModel = $registrarModel;
        $this->printer = $printer;
        $this->sesion = $sesion;
    }
    public function show(){
        $data=$this->sesion->obtenerPermisos();
        echo $this->printer->render( "view/reservaVuelo.html",$data);
    }
    public function procesarReserva(){
        $vuelo=$_GET["id"];

    }
}