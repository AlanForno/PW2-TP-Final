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
    public function  createPerfilController()
    {
        require_once ("controller/perfilController.php");
        return new perfilController($this->createPerfilModel(),$this->createPrinter(), $this->createManejoDeSession(), $this->createMailer(), $this->createVuelosModel());
    }
    public function  createVuelosController()
    {
        require_once ("controller/vuelosController.php");
        return new vuelosController($this->createVuelosModel(),$this->createPrinter(), $this->createManejoDeSession(), $this->createMailer());
    }
    public function  createLoginController()
    {
        require_once ("controller/LoginController.php");
        return new LoginController($this->createLoginModel(),$this->createPrinter(), $this->createManejoDeSession());
    }
    public function  createTurnosController()
    {
        require_once ("controller/TurnosController.php");
        return new TurnosController($this->createTurnosModel(),$this->createPrinter(), $this->createManejoDeSession(), $this->createMailer());
    }
    public  function createHomeController(){
        require_once ("controller/HomeController.php");
        return new HomeController($this->createHomeModel(),$this->createPrinter(), $this->createManejoDeSession());
    }
    public function createRegistrarController(){
        require_once("controller/RegistrarController.php");
        return new registrarController($this->createRegistrarModel(),$this->createPrinter(), $this->createManejoDeSession(), $this->createMailer());
    }
    public  function createAdminController(){
        require_once ("controller/AdminController.php");
        return new AdminController($this->createAdminModel(),$this->createVuelosModel(),$this->createPrinter(), $this->createManejoDeSession());
    }

    private  function createLoginModel(){
        require_once("model/LoginModel.php");
        $database=$this->getDatabase();
        return new LoginModel($database);
    }

    private  function createPerfilModel(){
        require_once("model/PerfilModel.php");
        require_once ('helpers/PDFPrinter.php');
        include('third-party/phpqrcode/qrlib.php');
        $database=$this->getDatabase();
        return new PerfilModel($database);
    }

    private  function createTurnosModel(){
        require_once("model/TurnosModel.php");
        $database=$this->getDatabase();
        return new TurnosModel($database);
    }

    private  function createRegistrarModel(){
        require_once("model/RegistrarModel.php");
        $database=$this->getDatabase();
        return new registrarModel($database);
    }

    private  function createAdminModel(){
        require_once("model/AdminModel.php");
        $database=$this->getDatabase();
        return new AdminModel($database);
    }
    private  function createVuelosModel(){
        require_once("model/vuelosModel.php");
        require_once ('helpers/PDFPrinter.php');
        $database=$this->getDatabase();
        return new vuelosModel($database);
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

    private function createManejoDeSession(){
        require_once ("helpers/ManejoDeSession.php");
        return new ManejoDeSession();
    }

    private function createPDFPrinter(){
        require_once ("helpers/PDFPrinter.php");
        return new PDFPrinter();
    }

    private function createMailer(){
        require_once ("helpers/Mailer.php");
        return  new Mailer();
        
    }


}
