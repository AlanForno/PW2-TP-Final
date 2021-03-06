<?php


class LoginModel
{
    private $database;
    private $resultado;

    public function __construct($database){
        $this->database = $database;
    }

    public function logearUsuario($usuario,$password){
        $sql="SELECT * FROM usuario";
        $this->resultado=$this->database->query($sql);
        return $this->validar($usuario,$password,$this->resultado);
    }

    public function validar($usuario,$password,$resultado){
        $password=md5($password);
        foreach ($resultado as $primerUsuario) {
            $usuarioAComparar=$primerUsuario['usuario'];
            $passwordAComparar=$primerUsuario['password'];

            if (strcmp($usuario, $usuarioAComparar) == 0 && strcmp($password, $passwordAComparar) == 0 && $primerUsuario['validacion']==NULL)  {
                return true;
            }
        }
        return false;
    }

    public function iniciarSesion($usuario){
        $sql="SELECT * FROM usuario WHERE `usuario`='$usuario'";
        $this->resultado=$this->database->query($sql);
        foreach ($this->resultado as $usuarioRecorrido){
            $_SESSION["id"]=$usuarioRecorrido["id"];
            $_SESSION["rol"]=$usuarioRecorrido["rol"];
            $_SESSION["usuario"]=$usuarioRecorrido["usuario"];
        }
    }

}