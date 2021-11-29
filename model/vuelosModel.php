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

     public function obtenerDestinos(){
         $sql="select * from `destinos` ";
         return $this->database->query($sql);
     }
     public function obtenerOrigenes(){
         $sql="select * from `origen` ";
         return $this->database->query($sql);
     }
     public function obtenerAeronaves(){
         $sql="select * from `aeronave` ";
         return $this->database->query($sql);
     }
     public function obtenerTipos(){
         $sql="select distinct tipo from aeronave";
         return $this->database->query($sql);
     }
     public function obtenerFechas(){
         $sql="select distinct fecha from vuelo";
         return $this->database->query($sql);
     }

     public function procesarReserva($idVuelo,$idUsuario,$asiento,$cabina){

         $sql="select idAeronave from `vuelo` where idVuelo='$idVuelo'";
         $idAeronave=$this->database->query($sql);
         foreach ($idAeronave as $id){
             $idAeronave=$id["idAeronave"];
         }
        if($this->chequearCompatibilidadDeTipo($idUsuario,$idVuelo)==true && $this->chequearDisponibiladDeAsiento($idVuelo,$asiento,$cabina)==true && $this->chequearCapacidad($idVuelo)==true){
            $codAlfanumerico = $this->generateRandomString(8);
            $sql="INSERT INTO `reservavuelo` (`idUsuario`, `idVuelo`,`aeronave`,`cabina`,`asiento`,`codAlfanumerico`) VALUES ('$idUsuario', '$idVuelo','$idAeronave','$cabina','$asiento','$codAlfanumerico')";
            $this->database->insert($sql);
            $this->reducirCapacidad($idVuelo,$cabina);
            return true;
        }else {
            return false;
        }
     }
     function generateRandomString($length) {
        return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
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
         if(intval($tipoVuelo) <= intval($tipoUsuario)){
             return true;
         }else{
             return false;
         }
     }
      public function chequearDisponibiladDeAsiento($idVuelo,$asiento,$cabina)
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
     public function getVuelosFiltradosPor($origen,$destino,$fecha){

         $SQL = "select * from `vuelo` join `aeronave` as a on vuelo.idAeronave=a.id join `origen` as o on vuelo.origen=o.id join `destinos`  as d on vuelo.destino=d.id where vuelo.origen='$origen'  OR vuelo.destino='$destino' OR vuelo.fecha = '$fecha'";
         return $this->database->query($SQL);
     }
     public function agregarReservaEnEspera($idVuelo,$idUsuario){
         $sql="select idAeronave from `vuelo` where idVuelo='$idVuelo'";
         $idAeronave=$this->database->query($sql);
         foreach ($idAeronave as $id){
             $idAeronave=$id["idAeronave"];
         }
         $codAlfanumerico = $this->generateRandomString(8);
         $sql="INSERT INTO `reservavuelo` (`idUsuario`, `idVuelo`,`aeronave`,`enEspera`,`codAlfanumerico`) VALUES ('$idUsuario', '$idVuelo','$idAeronave',true,'$codAlfanumerico')";
         $this->database->insert($sql);
     }

     public function ProcesarPdfReserva($idVuelo){
        $sql = "select * from `vuelo` join `aeronave` as a on vuelo.idAeronave=a.id 
        join `origen` as o on vuelo.origen=o.id 
        join `destinos`  as d on vuelo.destino=d.id 
        join `reservavuelo` as r on vuelo.idVuelo=r.idVuelo 
        where vuelo.idVuelo='$idVuelo' ORDER BY idReserva DESC";
       $data = $this->database->query($sql);
        $PDFPrinter = new PDFPrinter();
        $html ="<h1>Comprobante reserva de vuelo</h1><br>
             Se reservo el vuelo:<br> COD: ".$data[0]["codAlfanumerico"] .", Nombre: ".$data[0]["nombreVuelo"].
             "<br> Cabina tipo:".$data[0]["cabina"]." , Asiento numero: ".$data[0]["asiento"].
             "<br> Origen: ".$data[0]["origen"].
             ", Destino: ".$data[0]["destino"].", duracion: ".
            $data[0]["duracion"]." horas <br>
            Valor: $".$data[0]["precio"];
        return $PDFPrinter->generarOutput($html);
     }

     public function datosUsuario($idUsuario){
        $sql="select * from usuario where usuario.id=$idUsuario";
        $data = $this->database->query($sql);
        return $data;
     }

     public function darDeBajaReserva($idReserva,$cabina,$aeronave,$asiento,$idVuelo){

         $sql="delete from `reservavuelo` WHERE `idReserva` = '$idReserva'";
         $this->database->insert($sql);
         $sql="update aeronave set $cabina=$cabina+1 where id='$aeronave'";
         $this->database->insert($sql);
         $sql="update aeronave set capacidad=capacidad+1 where id='$aeronave'";
         $this->database->insert($sql);
         $this->consultarListaDeEspera($aeronave,$cabina,$asiento,$idVuelo);
     }
     public function darDeBajaReservaEnEspera($idReserva){

         $sql="delete from `reservavuelo` WHERE `idReserva` = '$idReserva'";
         $this->database->insert($sql);
     }
     public function consultarListaDeEspera($aeronave,$cabina,$asiento,$idVuelo){
         $sql="select * from reservaVuelo where enEspera=true and idVuelo='$idVuelo'";
         if($reservaEnEspera=$this->database->query($sql)){
             $sql="update aeronave set $cabina=$cabina-1 where id='$aeronave'";
             $this->database->insert($sql);
             $sql="update aeronave set capacidad=capacidad-1 where id='$aeronave'";
             $this->database->insert($sql);
             foreach ($reservaEnEspera as $reserva) {
                 $idReserva = $reserva['idReserva'];
                 $sql = "update reservavuelo set cabina='$cabina' where idReserva='$idReserva'";
                 $this->database->insert($sql);
                 $sql = "update reservavuelo set asiento='$asiento'where idReserva='$idReserva'";
                 $this->database->insert($sql);
                 $sql = "update reservavuelo set enEspera=false where idReserva='$idReserva'";
                 $this->database->insert($sql);
                 break;
             }
         }
     }

     public function obtenerCabinasReservadas(){
         $sql='SELECT * FROM reservavuelo WHERE enEspera = 0';
         $reservas=$this->database->query($sql);

         $cabinasCantidad=["suite"=>0, "general"=>0, "familiar"=>0];

         foreach ($reservas as $reservaActual){
             if(strcmp($reservaActual["cabina"], "cabinaSuite") == 0){
                 $cabinasCantidad["suite"]++;
             }
             if(strcmp($reservaActual["cabina"], "cabinaGeneral") == 0){
                 $cabinasCantidad["general"]++;
             }
             if(strcmp($reservaActual["cabina"], "cabinaFamiliar") == 0){
                 $cabinasCantidad["familiar"]++;
             }
         }

         return $cabinasCantidad;
     }
     public function obtenerTiposAeronave(){
        $sql='SELECT * FROM aeronave';
        $vuelos=$this->database->query($sql);
        $cantTipoDeVuelos=["tipo1"=>0, "tipo2"=>0, "tipo3"=>0];

        foreach($vuelos as $vueloActual){
            if(strcmp($vueloActual["tipo"], "1")==0)
                $cantTipoDeVuelos["tipo1"]++;
            if(strcmp($vueloActual["tipo"], "2")==0)
                $cantTipoDeVuelos["tipo2"]++;
            if(strcmp($vueloActual["tipo"], "3")==0)
                $cantTipoDeVuelos["tipo3"]++;
        }
        return $cantTipoDeVuelos;
    }
    public function obtenerTurnosHospitales(){
        $sql='SELECT * FROM turnos';
        $turnosHospitales=$this->database->query($sql);
        $cantTurnosHospitales=["bsas"=>0, "shangai"=>0, "ankara"=>0];

        foreach($turnosHospitales as $turnoActual){
            if(strcmp($turnoActual["hospital"], "1")==0)
                $cantTurnosHospitales["bsas"]++;
            if(strcmp($turnoActual["hospital"], "2")==0)
                $cantTurnosHospitales["shangai"]++;
            if(strcmp($turnoActual["hospital"], "3")==0)
                $cantTurnosHospitales["ankara"]++;
        }
        return $cantTurnosHospitales;
    }
 }