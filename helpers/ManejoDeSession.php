<?php

class ManejoDeSession{

    public function __construct(){

    }
    public function obtenerPermisos(){
        $data=array();
        $data["sesion"]=false;
        $data["admin"]=false;

        if(isset($_SESSION["rol"])){
            $data["sesion"]=true;
        }
        if($_SESSION["rol"]=="admin"){
            $data["admin"]=true;
        }
        return $data;
    }
}