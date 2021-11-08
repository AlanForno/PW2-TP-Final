<?php
class TurnosController{

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
        //ENVIO DE MAIL
        $data2 = $this->model->buscarTurnoConMail($_SESSION['usuario']);
        $this->mail->enviarEmail($data2);


        $this->mostrarResultado();
    }

    public function mostrarResultado(){

        $data=$this->sesion->obtenerPermisos();

        $turno=$this->model->buscarTurno($_SESSION['usuario']);

        $data["turno"]=$turno;


        echo $this->printer->render( "view/resultado.html", $data);
    }

}