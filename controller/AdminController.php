<?php
class AdminController{

    private $adminModel;
    private $printer;
    private $sesion;

    public function __construct($adminModel, $printer, $sesion){
        $this->adminModel = $adminModel;
        $this->printer = $printer;
        $this->sesion = $sesion;
    }

    public function show(){

        $data=$this->sesion->obtenerPermisos();

        if($data["admin"]){
            $usuarios=$this->adminModel->getUsuarios();

            $data["usuarios"]=$usuarios;

            echo $this->printer->render( "view/listaUsuarios.html", $data);
        }else{
            header("Location: /home");
        }

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
        $this->show();
    }

}