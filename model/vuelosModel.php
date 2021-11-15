<?php
 class vuelosModel {
     private $database;
     public function __construct($database)
     {
         $this->database=$database;
     }
     public function obtenerVuelos(){
         $sql = "select * from `vuelo` join `aeronave` as a on vuelo.idAeronave=a.id join `origen` as o on vuelo.origen=o.id join `destinos`  as d on vuelo.destino=d.id";
         return $this->database->query($sql);
     }
     public function obtenerVuelosPorId($idVuelo){
         $sql = "select * from `vuelo` join `aeronave` as a on vuelo.idAeronave=a.id join `origen` as o on vuelo.origen=o.id join `destinos`  as d on vuelo.destino=d.id where idVuelo='$idVuelo'";
         return $this->database->query($sql);
     }
     public function procesarReserva($idVuelo,$idUsuario,$asiento,$cabina){
         //return $this->chequearDisponibiladDeAsiento($idVuelo,$asiento,$cabina);
         //return $this->chequearCompatibilidadDeTipo($idUsuario,$idVuelo);
         //return$this->chequearCapacidad($idVuelo);
         $sql="select idAeronave from `vuelo` where idVuelo='$idVuelo'";
         $idAeronave=$this->database->query($sql);
         foreach ($idAeronave as $id){
             $idAeronave=$id["idAeronave"];
         }
        if($this->chequearCompatibilidadDeTipo($idUsuario,$idVuelo)==true && $this->chequearDisponibiladDeAsiento($idVuelo,$asiento,$cabina)==true && $this->chequearCapacidad($idVuelo)==true){

            $sql="INSERT INTO `reservavuelo` (`idUsuario`, `idVuelo`,`aeronave`,`cabina`,`asiento`) VALUES ('$idUsuario', '$idVuelo','$idAeronave','$cabina','$asiento')";
            $this->database->insert($sql);
            $this->reducirCapacidad($idVuelo,$cabina);
            return true;
        }else {
            return false;
        }
     }
     private function reducirCapacidad($idVuelo,$cabina){
         $sql="select idAeronave from `vuelo` where idVuelo='$idVuelo'";
         $idAeronave=$this->database->query($sql);
         foreach ($idAeronave as $id){
             $idAeronave=$id["idAeronave"];
         }
         $sql="select capacidad from `aeronave` where id='$idAeronave'";
         $capacidad=$this->database->query($sql);
         foreach ($capacidad as $capacidadActual){
             $capacidadActual=$capacidadActual["capacidad"];
         }
         $capacidadActual=intval($capacidadActual)-1;
         $sql="update aeronave set capacidad='$capacidadActual' where id='$idAeronave'";
         $this->database->insert($sql);
         switch ($cabina){
             case "cabinaFamiliar":  $sql="select cabinaFamiliar from `aeronave` where id='$idAeronave'";
                 $cabina=$this->database->query($sql);
                 foreach ($cabina as $cabinaActual){
                     $cabinaActual=$cabinaActual["cabinaFamiliar"];
                 } $cabinaActual=intval($cabinaActual)-1;
                 $sql="update aeronave set cabinaFamiliar='$cabinaActual' where id='$idAeronave'";
                 $this->database->insert($sql);
                 break;

             case "cabinaSuite": $sql="select cabinaSuite from `aeronave` where id='$idAeronave'";
                 $cabina=$this->database->query($sql);
                 foreach ($cabina as $cabinaActual){
                     $cabinaActual=$cabinaActual["cabinaSuite"];
                 } $cabinaActual=intval($cabinaActual)-1;
                 $sql="update aeronave set cabinaSuite='$cabinaActual' where id='$idAeronave'";
                 $this->database->insert($sql);
                 break;

             case "cabinaGeneral": $sql="select cabinaGeneral from `aeronave` where id='$idAeronave'";
                 $cabina=$this->database->query($sql);
                 foreach ($cabina as $cabinaActual){
                     $cabinaActual=$cabinaActual["cabinaGeneral"];
                 } $cabinaActual=intval($cabinaActual)-1;
                 $sql="update aeronave set cabinaGeneral='$cabinaActual' where id='$idAeronave'";
                 $this->database->insert($sql);
                 break;
         }
     }
     private function chequearCompatibilidadDeTipo($idUsuario,$idVuelo){
         $sql="select tipo from `vuelo` inner join `aeronave` on vuelo.idAeronave=aeronave.id where idVuelo='$idVuelo'";
         $tipoVuelo= $this->database->query($sql);
         foreach ($tipoVuelo as $tipoActual){
             $tipoVuelo=$tipoActual['tipo'];
         }
         $sql1= "select tipoAceptado from usuario where id='$idUsuario'";
         $tipoUsuario= $this->database->query($sql1);
         foreach ($tipoUsuario as $tipoActual){
             $tipoUsuario=$tipoActual['tipoAceptado'];
         }
         if(intval($tipoVuelo)== intval($tipoUsuario)||intval($tipoVuelo) <= intval($tipoUsuario)){
             return true;
         }else{
             return false;
         }
     }
     private function chequearDisponibiladDeAsiento($idVuelo,$asiento,$cabina)
     {
         $sql = "select asiento,cabina from `reservavuelo` where `idVuelo`='$idVuelo'";
            $result = $this->database->query($sql);
         foreach ($result as $resultActual) {
             $cabinaActual = $resultActual['cabina'];
             $asientoActual = $resultActual['asiento'];
             if (strcmp("$cabinaActual", "$cabina") == 0 and $asiento == $asientoActual) {
                 return false;
             }
         }
         return true;
     }
     private function chequearCapacidad($idVuelo){
        $sql="select * from vuelo join aeronave on vuelo.idAeronave=aeronave.id where idVuelo='$idVuelo' ";
        $capacidad=$this->database->query($sql);
        foreach ($capacidad as $capacidadActual){
            $capacidadActual=$capacidadActual['capacidad'];
            if(intval($capacidadActual)==0){

                return false;
            }
        }
        return true;
     }

 }