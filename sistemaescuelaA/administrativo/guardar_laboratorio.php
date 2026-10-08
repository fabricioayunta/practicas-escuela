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
    mostrar_error("Nombre inválido.");
}

/* Validar longitud */
if (mb_strlen($nombre) > 50) {
    mostrar_error("El nombre es demasiado largo (máximo 50 caracteres).");
}

/* Preparar consulta */
$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO laboratorios (nombre) VALUES (?)"
);

if (!$stmt) {
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
mysqli_stmt_close($stmt);

if ($codigo == 1062) {
    mostrar_error("Ya existe un laboratorio con ese nombre.");
}

mostrar_error("Error al guardar el laboratorio.");
?>