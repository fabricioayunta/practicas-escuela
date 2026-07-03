<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$nombre = $_POST["nombre"];

$sql = "INSERT INTO laboratorios (nombre)
        VALUES ('$nombre')";

if(mysqli_query($conexion,$sql)){

    header("Location: laboratorios.php");
    exit();

}else{

    echo "Error al guardar el laboratorio.";

}
?>