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

        if($data["sesion"] && $data["admin"]){
            echo $this->printer->render( "view/menuAdmin.html", $data);
        }else{
            header("Location: /home");
        }
    }

    public function reportes(){
        $data= $this->sesion->obtenerPermisos();
        /**Busqueda cantidad cavinas reservadas */
        $reservasCabinas=$this->vuelosModel->obtenerCabinasReservadas();
        $data["cabinaSuiteReservadas"]=$reservasCabinas["suite"];
        $data["cabinaFamiliarReservadas"]=$reservasCabinas["familiar"];
        $data["cabinaGeneralReservadas"]=$reservasCabinas["general"];

        /**Busqueda tipos de aeronave */
        $cantTipoDeVuelos = $this->vuelosModel->obtenerTiposAeronave();
        $data["tipo1"]=$cantTipoDeVuelos["tipo1"];
        $data["tipo2"]=$cantTipoDeVuelos["tipo2"];
        $data["tipo3"]=$cantTipoDeVuelos["tipo3"];
        
        /**Busqueda turnos dados por hospital */
        $cantTurnosHospitales = $this->vuelosModel->obtenerTurnosHospitales();
        $data["bsas"]=$cantTurnosHospitales["bsas"];
        $data["shangai"]=$cantTurnosHospitales["shangai"];
        $data["ankara"]=$cantTurnosHospitales["ankara"];

        echo $this->printer->render( "view/reportes.html", $data);
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
        if(isset($_GET['avionNoDisponible'])){
            $data["avionNoDisponible"]=$_GET['avionNoDisponible'];
        }
        $data["vuelos"]=$this->vuelosModel->obtenerVuelos();
        $data["aeronave"]=$this->vuelosModel->obtenerAeronaves();
        $data["destinos"]=$this->vuelosModel->obtenerDestinos();
        $data["origen"]=$this->vuelosModel->obtenerOrigenes();
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
        if(!$this->adminModel->darDeAlta($nombreVuelo,$origen,$destino,$fecha,$duracion,$precio,$idAeronave)){
            header("location:http://localhost/admin/vuelos?avionNoDisponible=true");
        }
        $this->vuelos();
    }

    public function eliminarVuelo(){
        $idVuelo=$_POST["idVuelo"];
        $this->adminModel->eliminarVuelo($idVuelo);
        $this->vuelos();
    }

}