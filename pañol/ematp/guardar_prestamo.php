<?php

session_start();

/* Verificar sesión y rol */
if (
    !isset($_SESSION["panol_id_usuario"]) ||
    !isset($_SESSION["panol_id_rol"]) ||
    (int)$_SESSION["panol_id_rol"] !== 3
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");
require_once("../includes/funciones_panol.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: crear_prestamo.php");
    exit();
}

if (!token_valido("panol_csrf_token")) {
    mostrar_error("El formulario venció o no es válido. Vuelva a abrir el nuevo préstamo.");
}

/* Obtener y limpiar datos */
$id_articulo   = (int)($_POST["id_articulo"] ?? 0);
$cantidad      = filter_var($_POST["cantidad"] ?? "", FILTER_VALIDATE_INT);
$persona       = trim((string)($_POST["persona"] ?? ""));
$observaciones = trim((string)($_POST["observaciones"] ?? ""));
$id_usuario    = (int)$_SESSION["panol_id_usuario"];

/* Validar y guardar */
$error = registrar_prestamo_articulo($conexion, $id_articulo, $cantidad, $persona, $observaciones, $id_usuario);

if ($error !== "") {
    mostrar_error($error);
}

header("Location: prestamos.php?ok=creado");
exit();
?>
