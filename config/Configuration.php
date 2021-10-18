<?php
class Configuration{

    private $config;



    private  function getDatabase(){
        require_once("helpers/MyDatabase.php");
        $config = $this->getConfig();
        return new MyDatabase($config["servername"], $config["username"], $config["password"], $config["dbname"]);
    }

    private  function getConfig(){
        if( is_null( $this->config ))
            $this->config = parse_ini_file("config/config.ini");

        return  $this->config;
    }
    public function  createLoginController()
    {
        require_once ("controller/LoginController.php");
        return new LoginController($this->createLoginModel(),$this->createPrinter());
    }
    public  function createHomeController(){
        require_once ("controller/HomeController.php");
        return new HomeController($this->createHomeModel(),$this->createPrinter());
    }
    private  function createLoginModel(){
        require_once("model/LoginModel.php");
        $database=$this->getDatabase();
        return new LoginModel($database);
    }

    private function createPrinter(){
        require_once ('third-party/mustache/src/Mustache/Autoloader.php');
        require_once("helpers/MustachePrinter.php");
        return new MustachePrinter("view/partials");
    }
    public function createRouter($defaultController, $defaultAction){
        include_once("helpers/Router.php");
        return new Router($this,$defaultController,$defaultAction);
    }

    private function createHomeModel()
    {
        require_once("model/HomeModel.php");
        return new HomeModel();
    }


}
