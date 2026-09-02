<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$cantidad = $_POST["cantidad"];

$sql = "INSERT INTO articulos (nombre, descripcion, cantidad, estado)
        VALUES ('$nombre', '$descripcion', '$cantidad', 'Alta')";

if(mysqli_query($conexion,$sql)){

    header("Location: articulos.php");
    exit();

}else{

    echo "Error al guardar el artículo.";

}
?>
