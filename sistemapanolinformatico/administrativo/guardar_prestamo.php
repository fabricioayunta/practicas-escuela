<?php

session_start();

/* Verificar sesión y rol */
if (
    !isset($_SESSION["pi_id_usuario"]) ||
    !isset($_SESSION["pi_id_rol"]) ||
    (int)$_SESSION["pi_id_rol"] !== 1
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");
require_once("../includes/funciones_pi.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: crear_prestamo.php");
    exit();
}

if (!token_valido("pi_csrf_token")) {
    mostrar_error("El formulario venció o no es válido. Vuelva a abrir la nueva entrega.");
}

/* Obtener y limpiar datos */
$insumo        = trim((string)($_POST["insumo"] ?? ""));
$cantidad      = filter_var($_POST["cantidad"] ?? "", FILTER_VALIDATE_INT);
$persona       = trim((string)($_POST["persona"] ?? ""));
$observaciones = trim((string)($_POST["observaciones"] ?? ""));
$id_usuario    = (int)$_SESSION["pi_id_usuario"];

/* Validar y guardar */
$error = registrar_prestamo_insumo($conexion, $insumo, $cantidad, $persona, $observaciones, $id_usuario);

if ($error !== "") {
    mostrar_error($error);
}

header("Location: prestamos.php?ok=creado");
exit();
?>
