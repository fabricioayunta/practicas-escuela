<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$id = $_POST["id_articulo"];
$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$cantidad = $_POST["cantidad"];

$sql = "UPDATE articulos
        SET nombre='$nombre',
            descripcion='$descripcion',
            cantidad='$cantidad'
        WHERE id_articulo='$id'";

if(mysqli_query($conexion,$sql)){

    header("Location: articulos.php");
    exit();

}else{

    echo "Error al actualizar.";

}
?>
