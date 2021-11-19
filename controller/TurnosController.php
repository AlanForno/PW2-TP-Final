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
        $usuario=$_SESSION['id'];
        $hospital=$_POST['hospital'];
        $fecha= $_POST['turno'];
        $this->model->procesarTurno($hospital,$fecha,$usuario);
        //ENVIO DE MAIL
        $this->enviarMailMedico();

        $this->mostrarResultado();
    }

    public function enviarMailMedico(){
        $data = $this->model->buscarTurnoConMail($_SESSION['id']);

        $emailUsuario =$data[0]["email"];
        $asunto= "Validacion y resultado de su turno medico";
        $mensaje='Buenos dias, '.$data[0]["usuario"].'<br>Su turno es esta programado para el dia '. $data[0]['fecha'] 
        .'<br> En el hospital: '.$data[0]['hospital']. '<br> Su resultado es: '.$data[0]['resultado'];
        $nombreUsuario=$data[0]["usuario"];

        $this->mail->enviarMail($emailUsuario, $asunto, $mensaje, $nombreUsuario);
    }

    public function mostrarResultado(){

        $data=$this->sesion->obtenerPermisos();

        $turno=$this->model->buscarTurno($_SESSION['id']);

        $data["turno"]=$turno;


        echo $this->printer->render( "view/resultado.html", $data);
    }

}