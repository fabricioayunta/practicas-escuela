<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$insumo = $_POST["insumo"];
$cantidad = $_POST["cantidad"];
$persona = $_POST["persona"];
$observaciones = $_POST["observaciones"];
$id_usuario = $_SESSION["pi_id_usuario"];

$sql = "INSERT INTO prestamos_insumos
        (insumo, cantidad, persona, estado, observaciones, id_usuario)
        VALUES
        ('$insumo', '$cantidad', '$persona', 'Pendiente', '$observaciones', '$id_usuario')";

if(mysqli_query($conexion,$sql)){

    header("Location: prestamos.php");
    exit();

}else{

    echo "Error al guardar la entrega.";

}
?>
