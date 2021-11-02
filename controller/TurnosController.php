<?php
class TurnosController{

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
        if ($data["sesion"]) {
            echo $this->printer->render( "view/Turnos-y-chequeos-medicos.html", $data);
        }else{
            header("Location: /home");
        }

    }
    public function procesarTurno(){
        $usuario=$_SESSION['usuario'];
        $hospital=$_POST['hospital'];
        $fecha= $_POST['turno'];
        $idDelTurno=$this->model->procesarTurno($hospital,$fecha,$usuario);
        $this->mostrarResultado($idDelTurno);
    }

    public function mostrarResultado($idDelTurno){

        $data=$this->sesion->obtenerPermisos();

        $turno=$this->model->buscarTurno($idDelTurno);

        $data["turno"]=$turno;

        echo $this->printer->render( "view/resultado.html", $data);
    }
}