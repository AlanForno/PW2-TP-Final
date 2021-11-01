<?php
class HomeController{

    private $homeModel;
    private $printer;

    public function __construct($homeModel, $printer){
        $this->homeModel = $homeModel;
        $this->printer = $printer;
    }

    public function show(){

        echo $this->printer->render( "view/home.html");
    }

}