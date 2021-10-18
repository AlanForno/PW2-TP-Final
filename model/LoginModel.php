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
        $sql="SELECT * FROM usuario";
        $this->resultado=$this->database->query($sql);
        $this->validar($usuario,$password,$this->resultado);
    }

    private function validacionUsuario($resultado, $usuario)
    {
        foreach ($resultado as $primerUsuario) {
            $nombre=$primerUsuario["nombre"];
            if (strcmp($usuario, $nombre) == 0) {
                return true;
            }
        }
        return false;
    }
    private function validacionPassword($resultado,$password)
    {
        $md5= md5($password);
        foreach ($resultado as $primerUsuario) {
            $passwordDB=$primerUsuario['password'];
            $passwordDB=md5($passwordDB);
            if (strcmp($md5, $passwordDB) == 0) {
                return true;
            }
        }
        return false;
    }
    private function validar($usuario,$password,$resultado){
        if($this->validacionUsuario($resultado,$usuario)&&$this->validacionPassword($resultado,$password)){
            echo "te logeaste";
        }
        else {
            echo "fallo";
        }
    }
}