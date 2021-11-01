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

       // $mensaje= "hola!" . $usuario . "este es su codigo de confirmacion" . $validacion;
       // echo "enviado";
      //  mail($email,"correo de confirmacion",$mensaje);
        $confirmacion=1;
       // $sql="INSERT INTO `usuario` (`usuario`, `password`, `validacion`,`confirmado` ) VALUES ('".[$usuario]."', '".[$md5password]."', '".[$validacion]."','".[$confirmacion]."')";

        $sql="INSERT INTO `usuario` (`id`,`usuario`, `password`, `validacion`, `rol`,`tipoAceptado`, `email`  ) VALUES (NULL ,'$usuario', '$md5password', '$validacion', '$rol', NULL , '$email')";
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
        $sql="UPDATE `usuario` SET `validacion` = NULL WHERE (`email` = '".$email."')";
        $this->database->insert($sql);
        header("location:http://localhost/");
    }

}