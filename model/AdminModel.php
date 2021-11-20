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
        $sql= "INSERT INTO `vuelo` (`nombreVuelo`,`origen`,`destino`,`fecha`,`duracion`,`precio`,`idAeronave`) VALUES ('$nombreVuelo','$origen','$destino','$fecha','$duracion','$precio','$idAeronave')";

        $this->database->insert($sql);
    }
    public function eliminarVuelo($id){

        $sql="DELETE FROM reservavuelo WHERE idVuelo='$id'";
        $this->database->insert($sql);
        $sql="DELETE FROM vuelo WHERE idVuelo='$id'";
        $this->database->insert($sql);

    }

}