<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: computadoras.php");
    exit();
}

$id_pc        = (int)($_POST["id_computadora"] ?? 0);
$nuevo_estado = $_POST["nuevo_estado"] ?? "";
$motivo       = trim($_POST["motivo"] ?? "");
$id_usuario   = (int)$_SESSION["id_usuario"];

if ($id_pc <= 0 || !in_array($nuevo_estado, ["Alta", "Baja"], true)) {
    mostrar_error("Los datos enviados no son válidos.", "computadoras.php");
}

if ($nuevo_estado === "Baja" && $motivo === "") {
    mostrar_error("Debe escribir el motivo de la baja.");
}

mysqli_begin_transaction($conexion);

$stmt = mysqli_prepare($conexion, "UPDATE computadoras SET estado = ? WHERE id_computadora = ?");
mysqli_stmt_bind_param($stmt, "si", $nuevo_estado, $id_pc);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_rollback($conexion);
    mostrar_error("No se pudo cambiar el estado de la computadora.");
}

mysqli_stmt_close($stmt);

if ($nuevo_estado === "Baja") {
    $accion = "La computadora fue dada de baja.\nMotivo: " . $motivo;
} else {
    $accion = "La computadora fue dada de alta.";
}

$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO historial_computadoras (id_computadora, id_usuario, accion) VALUES (?, ?, ?)"
);

mysqli_stmt_bind_param($stmt, "iis", $id_pc, $id_usuario, $accion);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_commit($conexion);

header("Location: ver_computadora.php?id=" . $id_pc);
exit();
