<?php


class LoginModel
{
    private $database;
    private $resultado;

    public function __construct($database){
        $this->database = $database;
    }

    /* METER VALIDACIONES Y DEMAS METODOS DE LOGIN*/
    public function logearUsuario($usuario,$password){
        session_start();
        $sql="SELECT * FROM usuario";
        $this->resultado=$this->database->query($sql);
        $this->validar($usuario,$password);
    }

    private function validacionUsuario($usuario)
    {
        foreach ($this->resultado as $primerUsuario) {
            $nombre=$primerUsuario["nombre"];
            if (strcmp($usuario, $nombre) == 0) {
                return true;
            }
        }
        return false;
    }
    private function validacionPassword($password)
    {
        $md5= md5($password);
        foreach ($this->resultado as $primerUsuario) {
            $passwordDB=$primerUsuario['password'];
            $passwordDB=md5($passwordDB);
            if (strcmp($md5, $passwordDB) == 0) {
                return true;
            }
        }
        return false;
    }
    private function validar($usuario,$password){
        if($this->validacionUsuario($usuario)&&$this->validacionPassword($password)){
            $_SESSION["usuario"]=$usuario;
            header("location:http://localhost/home");
            exit();
        }
        else {
            echo "fallo";
        }
    }
}