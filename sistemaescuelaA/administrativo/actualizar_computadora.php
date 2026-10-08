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
$numero = filter_input(INPUT_POST, "numero_pc", FILTER_VALIDATE_INT);
$estado = trim($_POST["estado"] ?? "");

if (!$id || $id <= 0) {
    mostrar_error("Computadora inválida.", "computadoras.php");
}

if (!$id_lab || $id_lab <= 0) {
    mostrar_error("Debe elegir un laboratorio.");
}

if (!$numero || $numero <= 0) {
    mostrar_error("El número de computadora no es válido.");
}

if (!in_array($estado, ["Alta", "Baja"], true)) {
    mostrar_error("El estado elegido no es válido.");
}

$stmt = mysqli_prepare(
    $conexion,
    "UPDATE computadoras
     SET id_laboratorio = ?, numero_pc = ?, estado = ?
     WHERE id_computadora = ?"
);

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
