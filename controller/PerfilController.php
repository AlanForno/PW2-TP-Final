<?php

class PerfilController
{

    private $model;
    private $printer;
    private $sesion;

    public function __construct($model, $printer, $sesion){
        $this->model = $model;
        $this->printer = $printer;
        $this->sesion = $sesion;
    }

    public function show(){
        $data=$this->sesion->obtenerPermisos();
        if($data["sesion"]){

            echo $this->printer->render( "view/perfil.html", $data);
        }else{
            header("Location: /home");
        }
    }

}