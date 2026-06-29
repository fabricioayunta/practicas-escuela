<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_GET["id"];

$sql = "DELETE FROM usuarios WHERE id_usuario = $id";

if (mysqli_query($conexion, $sql)) {
    header("Location: usuarios.php");
    exit();
} else {
    echo "Error al eliminar usuario";
}
?>