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
$numero = filter_input(INPUT_POST, "numero_pc", FILTER_VALIDATE_INT);

if (!$id_lab || $id_lab <= 0) {
    mostrar_error("Debe elegir un laboratorio.");
}

if (!$numero || $numero <= 0) {
    mostrar_error("El número de computadora no es válido.");
}

mysqli_begin_transaction($conexion);

/* Crear la computadora */

$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO computadoras (id_laboratorio, numero_pc, estado) VALUES (?, ?, 'Alta')"
);

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
