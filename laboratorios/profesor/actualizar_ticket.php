<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: mis_tickets.php");
    exit();
}

$id_ticket   = (int)($_POST["id_ticket"] ?? 0);
$id_usuario  = (int)$_SESSION["id_usuario"];
$observacion = trim($_POST["observacion"] ?? "");
$componentes = filtrar_componentes($_POST["componentes"] ?? []);

if ($id_ticket <= 0) {
    mostrar_error("Ticket inválido.", "mis_tickets.php");
}

if ($observacion === "") {
    mostrar_error("Debe contar qué le pasa a la computadora.");
}

/* Los componentes son opcionales: sin marcar se guarda NULL ("—") */
$componentesTexto = empty($componentes) ? null : implode(", ", $componentes);
$titulo           = titulo_ticket($observacion);


/* Comprobar que el ticket sea del profesor y siga abierto */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_ticket
     FROM tickets
     WHERE id_ticket = ? AND id_usuario = ? AND estado = 'Abierto'
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "ii", $id_ticket, $id_usuario);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) !== 1) {
    mysqli_stmt_close($stmt);
    mostrar_error("No puede editar este ticket. Solo se pueden editar sus propios tickets mientras están abiertos.", "mis_tickets.php");
}

mysqli_stmt_close($stmt);


/* Guardar los cambios */

$stmt = mysqli_prepare(
    $conexion,
    "UPDATE tickets
     SET titulo = ?, descripcion = ?, componentes_afectados = ?
     WHERE id_ticket = ? AND id_usuario = ? AND estado = 'Abierto'"
);

mysqli_stmt_bind_param($stmt, "sssii", $titulo, $observacion, $componentesTexto, $id_ticket, $id_usuario);

if (!mysqli_stmt_execute($stmt)) {
    error_log("Error al actualizar ticket: " . mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudieron guardar los cambios del ticket.");
}

mysqli_stmt_close($stmt);

header("Location: mis_tickets.php?ok=editado");
exit();
