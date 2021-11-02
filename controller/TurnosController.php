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
            if(!$this->model->yaRealizoChequeo($_SESSION["usuario"])){
                echo $this->printer->render( "view/Turnos-y-chequeos-medicos.html", $data);
            }else{
                $this->mostrarResultado();
            }
        }else{
            header("Location: /home");
        }

    }
    public function procesarTurno(){
        $usuario=$_SESSION['usuario'];
        $hospital=$_POST['hospital'];
        $fecha= $_POST['turno'];
        $this->model->procesarTurno($hospital,$fecha,$usuario);
        $this->mostrarResultado();
    }

    public function mostrarResultado(){

        $data=$this->sesion->obtenerPermisos();

        $turno=$this->model->buscarTurno($_SESSION['usuario']);

        $data["turno"]=$turno;

        echo $this->printer->render( "view/resultado.html", $data);
    }

}