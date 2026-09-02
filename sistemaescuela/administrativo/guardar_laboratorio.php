<?php

session_start();

/* Verificar sesión y rol de administrador */
if (
    !isset($_SESSION["id_usuario"]) ||
    !isset($_SESSION["id_rol"]) ||
    (int)$_SESSION["id_rol"] !== 1
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");

/* Obtener y limpiar el nombre */
$nombre = trim($_POST["nombre"] ?? "");

/* Validar que no esté vacío */
if ($nombre === "") {
    exit("Nombre inválido.");
}

/* Validar longitud */
if (mb_strlen($nombre) > 100) {
    exit("El nombre es demasiado largo.");
}

/* Preparar consulta */
$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO laboratorios (nombre) VALUES (?)"
);

if (!$stmt) {
    exit("Error al preparar la consulta.");
}

/* Vincular parámetro */
mysqli_stmt_bind_param($stmt, "s", $nombre);

/* Ejecutar */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: laboratorios.php");
    exit();
}

/* Error */
mysqli_stmt_close($stmt);
exit("Error al guardar el laboratorio.");
?>