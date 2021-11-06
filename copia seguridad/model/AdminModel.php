<?php


class AdminModel
{
    private $database;
    private $resultado;

    public function __construct($database){
        $this->database = $database;
    }

    public function getUsuarios(){
        return $this->database->query("SELECT * FROM usuario WHERE `validacion` is NULL ORDER BY rol");
    }

    public function getUsuariosFiltradosPor($filtro){
        $SQL = "SELECT * FROM usuario WHERE usuario LIKE '%".$filtro."%' OR email LIKE '%".$filtro."%' OR rol = '".$filtro."'";
        return $this->database->query($SQL);
    }

    public function cambiarPermisos($email, $accion){
        if($accion==1){
            $sql= "UPDATE `pw2`.`usuario` SET `rol` = 'admin' WHERE (`email` = '".$email."')";
            $this->database->insert($sql);
        }else{
            $sql= "UPDATE `pw2`.`usuario` SET `rol` = 'cliente' WHERE (`email` = '".$email."')";
            $this->database->insert($sql);
        }
    }

}