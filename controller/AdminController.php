<?php
class AdminController{

    private $adminModel;
    private $printer;

    public function __construct($adminModel, $printer){
        $this->adminModel = $adminModel;
        $this->printer = $printer;
    }

    public function show(){

        if($_SESSION["rol"]=="admin"){
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