<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id_usuario = $_SESSION["id_usuario"];
$titulo = $_POST["titulo"];
$descripcion = $_POST["descripcion"];

$sql = "INSERT INTO tickets (id_usuario, titulo, descripcion)
        VALUES ('$id_usuario', '$titulo', '$descripcion')";

if (mysqli_query($conexion, $sql)) {
    header("Location: mis_tickets.php");
    exit();
} else {
    echo "Error al crear ticket";
}
?>