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

$id     = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$id_lab = filter_input(INPUT_POST, "id_laboratorio", FILTER_VALIDATE_INT);
$numeroIngresado = $_POST["numero_pc"] ?? null;
$estado = $_POST["estado"] ?? "";

if ($id === false || $id === null || $id <= 0) {
    mostrar_error("Computadora inválida.", "computadoras.php");
}

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

if (!is_string($estado) || !in_array($estado, ["Alta", "Baja"], true)) {
    mostrar_error("El estado elegido no es válido.");
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

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_computadora FROM computadoras WHERE id_computadora = ?"
);
if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudo validar la computadora.");
}

mysqli_stmt_bind_param($stmt, "i", $id);
if (!mysqli_stmt_execute($stmt)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudo validar la computadora.");
}

$computadoraExiste = mysqli_stmt_get_result($stmt);
$existeComputadora = $computadoraExiste && mysqli_num_rows($computadoraExiste) === 1;
mysqli_stmt_close($stmt);

if (!$existeComputadora) {
    mostrar_error("La computadora seleccionada no existe.", "computadoras.php");
}

$stmt = mysqli_prepare(
    $conexion,
    "UPDATE computadoras
     SET id_laboratorio = ?, numero_pc = ?, estado = ?
     WHERE id_computadora = ?"
);
if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudo actualizar la computadora.");
}

mysqli_stmt_bind_param($stmt, "iisi", $id_lab, $numero, $estado, $id);

if (!mysqli_stmt_execute($stmt)) {

    $codigo = mysqli_stmt_errno($stmt);
    mysqli_stmt_close($stmt);

    if ($codigo == 1062) {
        mostrar_error("Ya existe la PC " . $numero . " en ese laboratorio.");
    }

    mostrar_error("No se pudo actualizar la computadora.");
}

mysqli_stmt_close($stmt);

header("Location: computadoras.php?ok=editada");
exit();
