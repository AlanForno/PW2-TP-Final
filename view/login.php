 <?php

session_start();


$db = new mysqli("localhost", "root", "", "pw");
if($db->connect_error){
    exit("ha ocurrido un error". $db->connect_error);
}
if(!isset($_POST['nombre'], $_POST['clave'])){
    
    exit('Ingrese un nombre y clave');
}
$nombre = $_POST["nombre"];
$password = $_POST["clave"];


$consulta = "SELECT * FROM usuario where usuario = ? and clave = ? and hash IS NULL";
$comm = $db->prepare($consulta);

$password = md5($password);

$comm->bind_param( "ss", $nombre, $password);
$comm->execute();
$resultado = $comm->get_result();
if($resultado->num_rows> 0){
    echo "Te pudiste logear exitosamente <br>";
} else{
    echo "usuario o contraseña incorrectos";
    exit("<br><a href= 'iniciarSesion.html'>Volver atras</a>");
    
}

while($fila = $resultado->fetch_assoc()){
    echo $fila["usuario"]. " - ". $fila["clave"]. "<br>";
    var_dump($resultado);
}

$db->close();

echo "<br><a href= 'iniciarSesion.html'>Volver atras</a>";

?>