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

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: laboratorios.php");
    exit();
}

if (
    !isset($_SESSION["csrf_token"]) ||
    !is_string($_SESSION["csrf_token"]) ||
    !isset($_POST["csrf_token"]) ||
    !is_string($_POST["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
) {
    mostrar_error("El formulario venció o no es válido. Vuelva a abrir la edición del laboratorio.");
}

/* Obtener y validar ID */
$id = filter_input(
    INPUT_POST,
    "id_laboratorio",
    FILTER_VALIDATE_INT
);

/* Obtener y limpiar nombre */
$nombre = $_POST["nombre"] ?? null;
if (!is_string($nombre)) {
    mostrar_error("El nombre del laboratorio no es válido.");
}
$nombre = trim($nombre);

/* Validar ID */
if ($id === false || $id === null || $id <= 0) {
    mostrar_error("ID de laboratorio inválido.");
}

/* Validar nombre */
if ($nombre === "") {
    mostrar_error("El nombre del laboratorio es obligatorio.");
}

$soloLetrasYNumeros = preg_match('/\A[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9]{1,18}\z/u', $nombre);
$cantidadNumeros = preg_match_all('/[0-9]/', $nombre);
$cantidadLetras = preg_match_all('/\p{L}/u', $nombre);
if (
    !$soloLetrasYNumeros ||
    $cantidadNumeros === false ||
    $cantidadNumeros > 2 ||
    $cantidadLetras === false ||
    $cantidadLetras > 16
) {
    mostrar_error("El nombre debe tener hasta 16 letras y 2 números.");
}

/* Preparar consulta */
$stmt = mysqli_prepare(
    $conexion,
    "UPDATE laboratorios
     SET nombre = ?
     WHERE id_laboratorio = ?"
);

if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("Error al preparar la consulta.");
}

/* Vincular parámetros */
if (!mysqli_stmt_bind_param($stmt, "si", $nombre, $id)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudieron validar los datos del laboratorio.");
}

/* Ejecutar actualización */
if (!mysqli_stmt_execute($stmt)) {
    $codigo = mysqli_stmt_errno($stmt);
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);

    if ($codigo == 1062) {
        mostrar_error("Ya existe un laboratorio con ese nombre.");
    }

    mostrar_error("No se pudo actualizar el laboratorio por un error de base de datos.");
}

mysqli_stmt_close($stmt);

/* Volver al listado */
header("Location: laboratorios.php?ok=editado");
exit();

?>
