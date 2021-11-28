<?php


class PerfilModel
{
    private $database;
    private $resultado;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerUsuario($id){
        $sql="SELECT * FROM usuario WHERE usuario.id=".$id;
        $resultado=$this->database->query($sql);
        return $resultado;
    }

    public function obtenerReservasAcreditadas($id){
        $sql='SELECT * FROM reservavuelo JOIN vuelo ON vuelo.idVuelo=reservavuelo.idVuelo join origen on origen.id=vuelo.origen join destinos on destinos.id=vuelo.destino  WHERE reservavuelo.idUsuario='.$id.' AND Acreditada=true';
        $resultado=$this->database->query($sql);
        return $resultado;
    }
    public function obtenerReservasNoAcreditadas($id){
       // "select * from `vuelo` join `aeronave` as a on vuelo.idAeronave=a.id join `origen` as o on vuelo.origen=o.id join `destinos`  as d on vuelo.destino=d.id where idVuelo='$idVuelo'"
        $sql='SELECT * FROM reservavuelo JOIN vuelo ON vuelo.idVuelo=reservavuelo.idVuelo join origen on origen.id=vuelo.origen join destinos on destinos.id=vuelo.destino  WHERE reservavuelo.idUsuario='.$id.' AND Acreditada=false and enEspera=false';
        $resultado=$this->database->query($sql);
        return $resultado;
    }
    public function obtenerReservasEnEspera($id){
        $sql='SELECT * FROM reservavuelo JOIN vuelo ON vuelo.idVuelo=reservavuelo.idVuelo  join origen on origen.id=vuelo.origen join destinos on destinos.id=vuelo.destino WHERE reservavuelo.idUsuario='.$id.'  AND Acreditada=false and enEspera=true';
        $resultado=$this->database->query($sql);
        return $resultado;
    }

    public function acreditarPago($id){
        $sql="UPDATE `reservavuelo` SET `Acreditada` = 1 WHERE `idReserva` = '$id'";
        $this->database->insert($sql);
    }


    public function CargaDatosDeComprobante($idUsuario,$idReserva, $opcion){
        
        $PDFPrinter = new PDFPrinter();
        $sql = "select * from `vuelo` join `aeronave` as a on vuelo.idAeronave=a.id 
        join `origen` as o on vuelo.origen=o.id 
        join `destinos`  as d on vuelo.destino=d.id 
        join `reservavuelo` as r on vuelo.idVuelo=r.idVuelo 
        where r.idUsuario='$idUsuario' and r.idReserva='$idReserva'";
        $data=$this->database->query($sql);

        /** CODIGO QR  */
        $tempDir = 'public/';
        
        $codeContents = "Boarding Pass
        Vuelo: 
        COD: ".$data[0]["codAlfanumerico"] .", Nombre: ".$data[0]["nombreVuelo"].
        " Cabina tipo:".$data[0]["cabina"]." , Asiento numero: ".$data[0]["asiento"].
        " Origen: ".$data[0]["origen"].
        ", Destino: ".$data[0]["destino"].", duracion: ".
       $data[0]["duracion"]." horas 
       Valor Acreditado: $".$data[0]["precio"];
        
        $fileName = '005_file_'.md5($codeContents).'.png';   
        $pngAbsoluteFilePath = $tempDir.$fileName;
        $urlRelativeFilePath = $tempDir.$fileName;
        if (!file_exists($pngAbsoluteFilePath)) {
            QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_L, 3);
        }
        $imageData = base64_encode(file_get_contents($urlRelativeFilePath));           
        $src = 'data:'.mime_content_type($urlRelativeFilePath).';base64,'.$imageData;

        $html = "<h1>Boarding Pass</h1><br> '<img src=".$src.">
        <p>Vuelo:<br> COD: ".$data[0]["codAlfanumerico"] ." ".$data[0]["nombreVuelo"].
        "<br> Cabina tipo:".$data[0]["cabina"]." , Asiento numero: ".$data[0]["asiento"].
        "<br> Origen: ".$data[0]["origen"].
        "<br>Destino: ".$data[0]["destino"].
        "<br>Duracion: ".$data[0]["duracion"]." horas 
        <br>Valor Acreditado: $".$data[0]["precio"];
       
        if($opcion == 0){
            return $PDFPrinter->generarOutput($html);
        }else{
            $PDFPrinter->render($html, "BoardinPass.pdf", 0);
        }
        
    }
}