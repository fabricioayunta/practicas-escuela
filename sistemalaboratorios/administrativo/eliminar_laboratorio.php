<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_GET["id"];

// Verificar si tiene computadoras
$sql = "SELECT COUNT(*) AS total
        FROM computadoras
        WHERE id_laboratorio='$id'";

$resultado = mysqli_query($conexion,$sql);
$fila = mysqli_fetch_assoc($resultado);

if($fila["total"] > 0){

    echo "<script>
            alert('No se puede eliminar este laboratorio porque tiene computadoras asignadas.');
            window.location='laboratorios.php';
          </script>";
    exit();

}

// Eliminar
$sqlEliminar = "DELETE FROM laboratorios
                WHERE id_laboratorio='$id'";

mysqli_query($conexion,$sqlEliminar);

header("Location: laboratorios.php");
exit();
?>