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

/* Obtener y validar ID */
$id = filter_input(
    INPUT_POST,
    "id_laboratorio",
    FILTER_VALIDATE_INT
);

/* Obtener y limpiar nombre */
$nombre = trim($_POST["nombre"] ?? "");

/* Validar ID */
if ($id === false || $id === null || $id <= 0) {
    exit("ID de laboratorio inválido.");
}

/* Validar nombre */
if ($nombre === "") {
    exit("El nombre del laboratorio es obligatorio.");
}

/* Validar longitud */
if (mb_strlen($nombre) > 100) {
    exit("El nombre del laboratorio es demasiado largo.");
}

/* Preparar consulta */
$stmt = mysqli_prepare(
    $conexion,
    "UPDATE laboratorios
     SET nombre = ?
     WHERE id_laboratorio = ?"
);

if (!$stmt) {
    exit("Error al preparar la consulta.");
}

/* Vincular parámetros */
mysqli_stmt_bind_param($stmt, "si", $nombre, $id);

/* Ejecutar actualización */
if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    exit("Error al actualizar el laboratorio.");
}

mysqli_stmt_close($stmt);

/* Volver al listado */
header("Location: laboratorios.php");
exit();

?>
