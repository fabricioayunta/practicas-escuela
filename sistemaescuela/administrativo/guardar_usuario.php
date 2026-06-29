<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$email = $_POST["email"];
$contrasena = $_POST["contrasena"];
$id_rol = $_POST["id_rol"];

$sql = "INSERT INTO usuarios (nombre, apellido, email, contrasena, id_rol)
        VALUES ('$nombre', '$apellido', '$email', '$contrasena', '$id_rol')";

if (mysqli_query($conexion, $sql)) {
    header("Location: usuarios.php");
    exit();
} else {
    echo "Error al crear usuario";
}
?>