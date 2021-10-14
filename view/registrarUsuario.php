<?php

$db = new mysqli("localhost", "root", "", "pw2");
if($db->connect_error){
    echo "ha ocurrido un error". $db->connect_error;
}
if(!isset($_POST['nombre'], $_POST['clave'])){
  echo "<a href= 'iniciarSesion.html'>Volver atras</a><br>";
  exit('Ingrese un nombre y clave');
}
if(empty($_POST['nombre']) ||  empty($_POST['clave'])){
  echo "<a href= 'iniciarSesion.html'>Volver atras</a><br>";
  exit('formulario incompleto');
}

$nombre = $_POST["nombre"];
$password = $_POST["clave"];

$consulta = "SELECT * FROM usuario where usuario = ? and hash IS NULL";
if($comm = $db->prepare($consulta)){
  
  $comm->bind_param( "s", $nombre);
  $comm->execute();
  $resultado = $comm->get_result();

  if($resultado->num_rows> 0){
      echo "ya existe un usuario con este nombre <br>";
  } else{
    $hash =md5(time());
    $password =md5($password);
    $sql = "INSERT INTO usuario (usuario, clave, hash)
    VALUES ('$nombre', '$password', '$hash')";

    if ($db->query($sql) === TRUE) {
      
      echo "<a href='validar.php?hash=" .md5(time()) . "'> Valida tu mail </a><br><br>";

    } else {
      echo "Error: " . $sql . "<br>" . $db->error;
    }
      
  }
  $comm->close();
} 


$db->close();

echo "<br><a href= 'iniciarSesion.html'>Volver atras</a>";

/*
if($query->num_rows>0){
    echo "el usuario y la clave estan bien";
} else {
    echo "algo esta mal";
}
*/
//echo $db->num_rows() . "<br>";

/*
while ($resultado = $query->fetch_assoc()){
    echo $resultado["usuario"]. "<br>";
}
*/



?>