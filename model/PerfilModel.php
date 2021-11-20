<?php


class PerfilModel
{
    private $database;
    private $resultado;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerUsuario($id){
        $sql='SELECT * FROM usuario WHERE usuario.id='.$id;
        $resultado=$this->database->query($sql);
        return $resultado;
    }

    public function obtenerReservas($id){
        $sql='SELECT * FROM reservavuelo JOIN vuelo ON vuelo.idVuelo=reservavuelo.idVuelo WHERE reservavuelo.idUsuario='.$id;
        $resultado=$this->database->query($sql);
        
        return $resultado;
    }
}