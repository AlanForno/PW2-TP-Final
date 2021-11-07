<?php
 class vuelosModel {
     private $database;
     public function __construct($database)
     {
         $this->database=$database;
     }
     public function obtenerVuelos(){
         $sql = "select * from vuelo";
         return $this->database->query($sql);
     }

 }