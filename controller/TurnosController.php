<?php
class TurnosController{

    private $model;
    private $printer;

    public function __construct($model, $printer){
        $this->model = $model;
        $this->printer = $printer;
    }

    public function show(){

        echo $this->printer->render( "view/Turnos-y-chequeos-medicos.html");
    }
}