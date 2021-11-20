<?php
class AdminController{

    private $adminModel;
    private $vuelosModel;
    private $printer;
    private $sesion;

    public function __construct($adminModel, $vuelosModel, $printer, $sesion){
        $this->adminModel = $adminModel;
        $this->vuelosModel = $vuelosModel;
        $this->printer = $printer;
        $this->sesion = $sesion;
    }

    public function show(){
        $data= $this->sesion->obtenerPermisos();

        echo $this->printer->render( "view/menuAdmin.html", $data);
    }
    public function lista(){
        $data=$this->sesion->obtenerPermisos();
        if($data["admin"]){
            $usuarios=$this->adminModel->getUsuarios();

            $data["usuarios"]=$usuarios;

            echo $this->printer->render( "view/listaUsuarios.html", $data);
        }else{
            header("Location: /home");
        }

    }
    public function vuelos(){
        $data= $this->sesion->obtenerPermisos();

        $data["vuelos"]=$this->vuelosModel->obtenerVuelos();


        echo $this->printer->render( "view/vuelosAdmin.html", $data);
    }

    public function buscar(){
        $filtro=$_GET["buscado"];

        $usuarios = $this->adminModel->getUsuariosFiltradosPor($filtro);
        $data["usuarios"] = $usuarios;
        if (empty($data["usuarios"])){
            $data["usuarios"]= $this->adminModel->getUsuarios();
            $data["error"]=true;
        }
        echo $this->printer->render( "view/listaUsuarios.html" , $data);
    }

    public function cambiarPermisos(){
        $emailBuscado=$_GET["email"];
        $accionAEfectuar=$_GET["accion"];
        $this->adminModel->cambiarPermisos($emailBuscado, $accionAEfectuar);
        $this->lista();
    }
    public function darDeAlta(){
        $nombreVuelo=$_POST["nombreVuelo"];
        $origen=$_POST["origen"];
        $destino=$_POST["destino"];
        $fecha=$_POST["fecha"];
        $duracion=$_POST["duracion"];
        $precio=$_POST["precio"];
        $idAeronave=$_POST["aeronave"];

        $this->adminModel->darDeAlta($nombreVuelo,$origen,$destino,$fecha,$duracion,$precio,$idAeronave);
        $this->vuelos();
    }

    public function eliminarVuelo(){
        $idVuelo=$_GET["idVuelo"];
        $this->adminModel->eliminarVuelo($idVuelo);
        $this->vuelos();
    }

}