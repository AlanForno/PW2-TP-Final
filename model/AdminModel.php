<?php


class AdminModel
{
    private $database;
    private $resultado;

    public function __construct($database){
        $this->database = $database;
    }

    public function getUsuarios(){

        return $this->database->query("SELECT * FROM usuario WHERE validacion is NULL ORDER BY rol");

    }

    public function getUsuariosFiltradosPor($filtro){
        $SQL = "SELECT * FROM usuario WHERE `validacion` is NULL AND ( usuario LIKE '%".$filtro."%' OR email LIKE '%".$filtro."%' OR rol = '".$filtro."')";
        return $this->database->query($SQL);
    }

    public function cambiarPermisos($email, $accion){
        if($accion==1){
            $sql= "UPDATE `usuario` SET `rol` = 'admin' WHERE (`email` = '".$email."')";
            $this->database->insert($sql);
        }else{
            $sql= "UPDATE `usuario` SET `rol` = 'cliente' WHERE (`email` = '".$email."')";
            $this->database->insert($sql);
        }
    }

    public function darDeAlta($nombreVuelo,$origen,$destino,$fecha,$duracion,$precio,$idAeronave){
        $sql="select * from vuelo where idAeronave='$idAeronave'";
        if(!$this->database->query($sql)){
            $sql= "INSERT INTO `vuelo` (`nombreVuelo`,`origen`,`destino`,`fecha`,`duracion`,`precio`,`idAeronave`) VALUES ('$nombreVuelo','$origen','$destino','$fecha','$duracion','$precio','$idAeronave')";
            $this->database->insert($sql);
            return true; 
        }else{
            return false;
        }

    }
    public function eliminarVuelo($id){
        $this->existeReservas($id);
        $sql="DELETE FROM reservavuelo WHERE idVuelo='$id'";
        $this->database->insert($sql);
        $sql="DELETE FROM vuelo WHERE idVuelo='$id'";
        $this->database->insert($sql);

    }
    private function existeReservas($idVuelo){
        $sql="select * from `reservavuelo` where `idVuelo`='$idVuelo'";
        $cabina='';
        $asiento='';
        $aeronave='';
        $idUsuario='';
        $reservasActuales=array();

        if(!$reserva=$this->database->query($sql)){

        }else {
            foreach ($reserva as $reservaDatos){

                $cabina=$reservaDatos['cabina'];
                $asiento=$reservaDatos['asiento'];
                $aeronave=$reservaDatos['aeronave'];
                $idUsuario=$reservaDatos['idUsuario'];
                $sql="update aeronave set $cabina=$cabina+1 where id='$aeronave'";
                $this->database->insert($sql);
                $sql="update aeronave set capacidad=capacidad+1 where id='$aeronave'";
                $this->database->insert($sql);
                //agregar el update a tabla mis reservas.

            }
            return $reserva;
        }
    }

}