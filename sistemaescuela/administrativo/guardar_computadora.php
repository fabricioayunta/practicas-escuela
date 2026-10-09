<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: computadoras.php");
    exit();
}

$id_lab = filter_input(INPUT_POST, "id_laboratorio", FILTER_VALIDATE_INT);
$numeroIngresado = $_POST["numero_pc"] ?? null;

if ($id_lab === false || $id_lab === null || $id_lab <= 0) {
    mostrar_error("Debe elegir un laboratorio.");
}

if (
    !is_string($numeroIngresado) ||
    !preg_match('/\A[0-9]{1,2}\z/', $numeroIngresado)
) {
    mostrar_error("El número de computadora debe estar entre 1 y 99, sin comillas ni otros caracteres.");
}

$numero = (int)$numeroIngresado;
if ($numero < 1 || $numero > 99) {
    mostrar_error("El número de computadora debe estar entre 1 y 99, sin comillas ni otros caracteres.");
}

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_laboratorio FROM laboratorios WHERE id_laboratorio = ?"
);
if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudo validar el laboratorio.");
}

mysqli_stmt_bind_param($stmt, "i", $id_lab);
if (!mysqli_stmt_execute($stmt)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudo validar el laboratorio.");
}

$laboratorioExiste = mysqli_stmt_get_result($stmt);
$existeLaboratorio = $laboratorioExiste && mysqli_num_rows($laboratorioExiste) === 1;
mysqli_stmt_close($stmt);

if (!$existeLaboratorio) {
    mostrar_error("El laboratorio seleccionado no existe.");
}

mysqli_begin_transaction($conexion);

/* Crear la computadora */

$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO computadoras (id_laboratorio, numero_pc, estado) VALUES (?, ?, 'Alta')"
);
if (!$stmt) {
    error_log(mysqli_error($conexion));
    mysqli_rollback($conexion);
    mostrar_error("No se pudo guardar la computadora.");
}

mysqli_stmt_bind_param($stmt, "ii", $id_lab, $numero);

if (!mysqli_stmt_execute($stmt)) {

    $codigo = mysqli_stmt_errno($stmt);
    mysqli_stmt_close($stmt);
    mysqli_rollback($conexion);

    if ($codigo == 1062) {
        mostrar_error("Ya existe la PC " . $numero . " en ese laboratorio.");
    }

    mostrar_error("No se pudo guardar la computadora.");
}

$id_computadora = mysqli_insert_id($conexion);
mysqli_stmt_close($stmt);

/* Crear la ficha de componentes vacía (así después se puede completar y editar) */

$stmt = mysqli_prepare($conexion, "INSERT INTO componentes (id_computadora) VALUES (?)");
if (!$stmt) {
    error_log(mysqli_error($conexion));
    mysqli_rollback($conexion);
    mostrar_error("No se pudo guardar la computadora.");
}
mysqli_stmt_bind_param($stmt, "i", $id_computadora);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_rollback($conexion);
    mostrar_error("No se pudo guardar la computadora.");
}

mysqli_stmt_close($stmt);
mysqli_commit($conexion);

header("Location: computadoras.php?ok=creada");
exit();
