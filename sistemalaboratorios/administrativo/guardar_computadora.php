<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id_lab = $_POST["id_laboratorio"];
$numero = $_POST["numero_pc"];

$sql = "INSERT INTO computadoras (id_laboratorio, numero_pc, estado)
        VALUES ('$id_lab', '$numero', 'Alta')";

if(mysqli_query($conexion,$sql)){
    header("Location: computadoras.php");
}else{
    echo "Error";
}
?>