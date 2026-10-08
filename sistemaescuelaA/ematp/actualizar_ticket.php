<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: tickets.php");
    exit();
}

$id_ticket   = (int)($_POST["id_ticket"] ?? 0);
$estado      = $_POST["estado"] ?? "";
$observacion = trim($_POST["observacion"] ?? "");
$id_usuario  = (int)$_SESSION["id_usuario"];

if ($id_ticket <= 0 || !in_array($estado, ["Abierto", "Pendiente", "Cerrado"], true)) {
    mostrar_error("Los datos enviados no son válidos.", "tickets.php");
}

if ($observacion === "") {
    mostrar_error("Debe escribir una observación.");
}

mysqli_begin_transaction($conexion);

/* Cambiar estado y asignar el ticket al EMATP que lo gestiona */

$stmt = mysqli_prepare(
    $conexion,
    "UPDATE tickets SET estado = ?, id_ematp_asignado = ? WHERE id_ticket = ?"
);

mysqli_stmt_bind_param($stmt, "sii", $estado, $id_usuario, $id_ticket);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_rollback($conexion);
    mostrar_error("No se pudo actualizar el ticket.");
}

mysqli_stmt_close($stmt);

/* Guardar el movimiento en el historial */

$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO historialticket (id_ticket, estado, fecha, observacion, id_usuario)
     VALUES (?, ?, NOW(), ?, ?)"
);

mysqli_stmt_bind_param($stmt, "issi", $id_ticket, $estado, $observacion, $id_usuario);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_rollback($conexion);
    mostrar_error("No se pudo guardar el historial del ticket.");
}

mysqli_stmt_close($stmt);
mysqli_commit($conexion);

header("Location: tickets.php");
exit();
