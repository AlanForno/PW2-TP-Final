<?php

$hash = $_GET["hash"];
$db = new mysqli("localhost", "root", "", "pw2");
if($db->connect_error){
    echo "ha ocurrido un error al conectarse a la db". $db->connect_error;
}
$consulta ="UPDATE usuario SET hash= NULL WHERE hash='$hash'";
if ($db->query($consulta) === TRUE) {
    echo "Se valido el mail correctamente";
  } else {
    echo "Error al validar el mail: " . $db->error;
  }

echo "<a href= 'view/iniciarSesion.html'>Volver atras</a>";
echo "<br>". $hash;
 