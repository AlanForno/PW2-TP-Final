<?php


class RegistrarModel
{
        private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function registrarUsuario($usuario,$password,$email){
        $validacion=md5(time());
        $md5password=md5($password);
       // $mensaje= "hola!" . $usuario . "este es su codigo de confirmacion" . $validacion;
       // echo "enviado";
      //  mail($email,"correo de confirmacion",$mensaje);
        $confirmacion=1;
        $sql="INSERT INTO `usuario` (`usuario`, `password`, `validacion`,`confirmado` ) VALUES ('".$usuario."', '".$md5password."', '".$validacion."','".$confirmacion."')";
        $this->database->insert($sql);
    }
    /*
    public function confirmacion($validacion){
        $sql="SELECT * from 'usuario' where validacion='$validacion'";
        if($this->database->query($sql)){
            $sql1="update confirmado set 1 where validacion='$validacion'";
            $this->database->update($sql);
        }
    }
*/
    /* DEMAS METODOS DE REGISTRAR*/
}