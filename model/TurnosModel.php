<?php


class TurnosModel
{
    private $database;


    public function __construct($database)
    {
        $this->database=$database;
    }
    /*falta agregar el usuario que reserva CON SESSIONES */
    public function procesarTurno($idHospital,$fecha){
        $sql = "SELECT * FROM `turnos` where hospital='$idHospital' and fecha='$fecha'";
        $sql2= "SELECT `cantidadDeTurnos` FROM `hospitales` WHERE id='1'";
        $turnosActuales=$this->database->query($sql);
        $cantidadTurnosDiarios=$this->database->query($sql2);
        $this->validacionDeTurno($turnosActuales,$cantidadTurnosDiarios,$idHospital,$fecha);
    }
    private function extraerTurnosDiarios($cantidadTurnos){
        $turnosDiarios=0;
        foreach ($cantidadTurnos as $actual){
            foreach ($actual as $cantidad){
                $turnosDiarios=$cantidad;
            }
        }
        return $turnosDiarios;
    }
    private function contarTurnos($cantidadTurnosActuales){
        $turnosReservados=0;
        foreach ($cantidadTurnosActuales as $actual){
                $turnosReservados ++;
        }
        return $turnosReservados;
    }

    private function validacionDeTurno($resultado,$cantidadTurnosDiarios,$idHospital,$fecha){
        if($this->contarTurnos($resultado)<=$this->extraerTurnosDiarios($cantidadTurnosDiarios)){
            $sql= "INSERT INTO `turnos` (`reserva`, `fecha`, `hospital`, `usuario`) VALUES (NULL, '$fecha', '$idHospital', '2')";
            $this->database->insert($sql);
        }else(header("location:http://localhost/Turnos"));
    }
    /* DEMAS METODOS DE turnos*/


}