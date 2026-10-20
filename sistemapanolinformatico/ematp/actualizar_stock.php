<?php

session_start();

/* Verificar sesión y rol de EMATP */
if (
    !isset($_SESSION["pi_id_usuario"]) ||
    !isset($_SESSION["pi_id_rol"]) ||
    (int)$_SESSION["pi_id_rol"] !== 3
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: repuestos.php");
    exit();
}

if (!token_valido("pi_csrf_token")) {
    mostrar_error("El formulario venció o no es válido. Vuelva a abrir la actualización del stock.");
}

/* El EMATP solo puede cambiar la cantidad */
$id       = (int)($_POST["id_repuesto"] ?? 0);
$cantidad = filter_var($_POST["cantidad"] ?? "", FILTER_VALIDATE_INT);

if ($id <= 0) {
    mostrar_error("Repuesto inválido.", "repuestos.php");
}

if ($cantidad === false || $cantidad < 0 || $cantidad > 9999) {
    mostrar_error("La cantidad debe ser un número entre 0 y 9999.");
}

$stmt = mysqli_prepare($conexion, "UPDATE repuestos SET cantidad = ? WHERE id_repuesto = ?");

if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("Error al preparar la consulta.");
}

mysqli_stmt_bind_param($stmt, "ii", $cantidad, $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: repuestos.php?ok=stock");
    exit();
}

error_log(mysqli_stmt_error($stmt));
mysqli_stmt_close($stmt);

mostrar_error("No se pudo actualizar el stock.");
?>
