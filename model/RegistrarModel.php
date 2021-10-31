<?php


class RegistrarModel
{


    private $database;
    private $resultado;

    public function __construct($database){
        $this->database = $database;
    }

    public function registrarUsuario($usuario,$password, $rol, $email, $validacion){

        $md5password=md5($password);
        $sql="INSERT INTO `pw2`.`usuario` (`usuario`, `password`, `validacion`, `rol`, `email`  ) VALUES ('".$usuario."', '".$md5password."', '".$validacion."', '".$rol."' , '".$email."')";
        $this->database->insert($sql);


    }

    public function validarUsuario($validacion, $email){

        $sql="SELECT * FROM usuario";
        $this->resultado=$this->database->query($sql);
        if($this->verificar($email,$validacion,$this->resultado)){
            $this->validacionCorrecta($email);
        }else{
            // AQUI DEBERIA ENVIAR UN MENSAJE DE ERROR
        }
    }

    public function verificar($email,$validacion,$resultado){
        foreach ($resultado as $primerUsuario) {
            $emailAComparar=$primerUsuario['email'];
            $validacionAComparar=$primerUsuario['validacion'];

            if (strcmp($email, $emailAComparar) == 0 && strcmp($validacion, $validacionAComparar) == 0) {
                return true;
            }
        }
        return false;
    }

    public function validacionCorrecta($email)
    {
        $sql="UPDATE `pw2`.`usuario` SET `validacion` = NULL WHERE (`email` = '".$email."')";
        $this->database->insert($sql);
    }

}