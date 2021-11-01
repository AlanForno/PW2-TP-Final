<?php

class resultadoController
{

    private $model;
    private $printer;

    public function __construct($model, $printer){
        $this->model = $model;
        $this->printer = $printer;
    }

    public function show(){

        echo $this->printer->render( "view/resultado.html");
    }
    public function mostrarResultado(){
        $this->model->mostrarResultado();
    }
}