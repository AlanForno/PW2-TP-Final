<?php
class HomeController{

    private $homeModel;
    private $printer;
    private $sesion;

    public function __construct($homeModel, $printer, $sesion){
        $this->homeModel = $homeModel;
        $this->printer = $printer;
        $this->sesion = $sesion;
    }

    public function show(){

        $data= $this->sesion->obtenerPermisos();
        echo $this->printer->render( "view/home.html", $data);
    }

}