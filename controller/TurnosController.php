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
    public function procesarTurno(){
        $usuario=$_SESSION['usuario'];
        $hospital=$_POST['hospital'];
        $fecha= $_POST['turno'];
        $idDelTurno=$this->model->procesarTurno($hospital,$fecha,$usuario);
        $this->mostrarResultado($idDelTurno);
    }

    public function mostrarResultado($idDelTurno){
        $turno=$this->model->buscarTurno($idDelTurno);

        $data["turno"]=$turno;

        echo $this->printer->render( "view/resultado.html", $data);
    }
}