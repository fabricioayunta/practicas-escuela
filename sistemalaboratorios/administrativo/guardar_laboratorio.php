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
$conexion = $GLOBALS["conexion"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: crear_laboratorio.php");
    exit();
}

if (
    !isset($_SESSION["csrf_token"]) ||
    !is_string($_SESSION["csrf_token"]) ||
    !isset($_POST["csrf_token"]) ||
    !is_string($_POST["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
) {
    mostrar_error("El formulario venció o no es válido. Vuelva a abrir la creación del laboratorio.");
}

/* Obtener y limpiar el nombre */
$nombre = $_POST["nombre"] ?? null;
if (!is_string($nombre)) {
    mostrar_error("El nombre del laboratorio no es válido.");
}
$nombre = trim($nombre);

/* Validar que no esté vacío */
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
    "INSERT INTO laboratorios (nombre) VALUES (?)"
);

if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("Error al preparar la consulta.");
}

/* Vincular parámetro */
mysqli_stmt_bind_param($stmt, "s", $nombre);

/* Ejecutar */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: laboratorios.php?ok=creado");
    exit();
}

/* Error */
$codigo = mysqli_stmt_errno($stmt);
error_log(mysqli_stmt_error($stmt));
mysqli_stmt_close($stmt);

if ($codigo == 1062) {
    mostrar_error("Ya existe un laboratorio con ese nombre.");
}

mostrar_error("No se pudo guardar el laboratorio por un error de base de datos.");
?>