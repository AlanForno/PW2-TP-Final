<?php

class resultadoController
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

        echo $this->printer->render( "view/resultado.html", $data);
    }
    public function mostrarResultado(){
        $this->model->mostrarResultado();
    }
}