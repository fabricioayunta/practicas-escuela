<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_POST["id_usuario"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$email = $_POST["email"];
$contrasena = $_POST["contrasena"];
$id_rol = $_POST["id_rol"];

$sql = "UPDATE usuarios SET
        nombre='$nombre',
        apellido='$apellido',
        email='$email',
        contrasena='$contrasena',
        id_rol='$id_rol'
        WHERE id_usuario=$id";

if (mysqli_query($conexion, $sql)) {
    header("Location: usuarios.php");
    exit();
} else {
    echo "Error al actualizar usuario";
}
?>