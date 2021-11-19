<?php


class TurnosModel
{
    private $database;


    public function __construct($database)
    {
        $this->database=$database;
    }
    /*falta agregar el usuario que reserva CON SESSIONES */
    public function procesarTurno($idHospital,$fecha,$idUsuario){

        $sql = "SELECT * FROM `turnos` where hospital='$idHospital' and fecha='$fecha'";
        $sql2= "SELECT `cantidadDeTurnos` FROM `hospitales` WHERE id='$idHospital'";
        $turnosActuales=$this->database->query($sql);
        $cantidadTurnosDiarios=$this->database->query($sql2);
        $this->validacionDeTurno($turnosActuales,$cantidadTurnosDiarios,$idHospital,$fecha,$idUsuario);

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

    private function validacionDeTurno($resultado,$cantidadTurnosDiarios,$idHospital,$fecha,$idUsuario){

        if($this->contarTurnos($resultado)<$this->extraerTurnosDiarios($cantidadTurnosDiarios)){
            $resultado=$this->generarResultado();
            $sql= "INSERT INTO `turnos` ( `fecha`, `hospital`, `usuario`,`resultado`) VALUES ( '".$fecha."', '".$idHospital."', '".$idUsuario."','".$resultado."')";
            $sql2= "update `usuario` set `tipoAceptado`='".$resultado."' where `id`='".$idUsuario."'";
            $this->database->insert($sql);
            $this->database->insert($sql2);
        }else(header("Location: /turnos"));
    }

    private function generarResultado(){
        return rand(1,3);
    }

    public function buscarTurno($id){
        $sql='select * from turnos join hospitales on turnos.hospital=hospitales.id where usuario="'.$id.'"';
        echo $sql;
        return $this->database->query($sql);
    }

    public function yaRealizoChequeo($nombre){
        $sql='select * from turnos';
        $turnos = $this->database->query($sql);
        foreach ($turnos as $turno){
            if($turno["usuario"]==$nombre){
                return true;
            }
        }
        return false;
    }

    public function buscarTurnoConMail($id){
        $sql='select * from turnos join hospitales on turnos.hospital=hospitales.id 
        join usuario on usuario.id = turnos.usuario 
        where turnos.usuario="'.$id.'"';
        $data=$this->database->query($sql);
        return $data;
    }


}