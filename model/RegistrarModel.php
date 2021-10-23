<?php


class RegistrarModel
{


    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function registrarUsuario($usuario,$password){

        $md5password=md5($password);
        $validacion=md5(time());
        $sql="INSERT INTO `pw2`.`usuario` (`usuario`, `password`, `validacion` ) VALUES ('".$usuario."', '".$md5password."', '".$validacion."')";
        $this->database->insert($sql);

    }

    /* DEMAS METODOS DE REGISTRAR*/

}