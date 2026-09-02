<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_POST["id"];
$id_lab = $_POST["id_laboratorio"];
$numero = $_POST["numero_pc"];
$estado = $_POST["estado"];

$sql = "UPDATE computadoras SET
        id_laboratorio='$id_lab',
        numero_pc='$numero',
        estado='$estado'
        WHERE id_computadora='$id'";

if(mysqli_query($conexion,$sql)){
    header("Location: computadoras.php");
}else{
    echo "Error al actualizar";
}
?>