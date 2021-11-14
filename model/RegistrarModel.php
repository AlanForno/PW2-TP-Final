<?php


class RegistrarModel
{


    private $database;
    private $resultado;

    public function __construct($database){
        $this->database = $database;
    }

    public function registrarUsuario($usuario,$password, $rol, $email, $validacion){

        if(!$this->existeUsuario($email, $usuario)){
            $md5password=md5($password);
            $sql="INSERT INTO `usuario` (`usuario`, `password`, `validacion`, `rol`, `email`  ) VALUES ('".$usuario."', '".$md5password."', '".$validacion."', '".$rol."' , '".$email."')";
            $this->database->insert($sql);
            return true;
        }else{
            return false;
        }


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
        $sql="UPDATE `usuario` SET `validacion` = NULL WHERE (`email` = '".$email."')";
        $this->database->insert($sql);
    }

    public function existeUsuario($email, $nombre)
    {
        $sql="SELECT * FROM usuario";
        $usuarios=$this->database->query($sql);
        foreach ($usuarios as $usuarioBuscado){
            if($usuarioBuscado["email"] == $email || $usuarioBuscado["usuario"] == $nombre){
                return true;
            }
        }
        return false;
    }

}