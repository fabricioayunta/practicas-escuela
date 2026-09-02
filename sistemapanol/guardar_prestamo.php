<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$id_articulo = $_POST["id_articulo"];
$cantidad = $_POST["cantidad"];
$persona = $_POST["persona"];
$observaciones = $_POST["observaciones"];
$id_usuario = $_SESSION["panol_id_usuario"];

$sql = "INSERT INTO prestamos_articulos
        (id_articulo, cantidad, persona, estado, observaciones, id_usuario)
        VALUES
        ('$id_articulo', '$cantidad', '$persona', 'Pendiente', '$observaciones', '$id_usuario')";

if(mysqli_query($conexion,$sql)){

    header("Location: prestamos.php");
    exit();

}else{

    echo "Error al guardar el préstamo.";

}
?>
