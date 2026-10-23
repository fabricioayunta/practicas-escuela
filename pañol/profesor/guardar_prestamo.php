<?php

session_start();

/* Verificar sesión y rol de profesor */
if (
    !isset($_SESSION["panol_id_usuario"]) ||
    !isset($_SESSION["panol_id_rol"]) ||
    (int)$_SESSION["panol_id_rol"] !== 2
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");
require_once("../includes/funciones_panol.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: articulos.php");
    exit();
}

if (!token_valido("panol_csrf_token")) {
    mostrar_error("El formulario venció o no es válido. Vuelva a abrir el pedido.");
}

/* El profesor solo puede pedir a su propio nombre */
$id_articulo   = (int)($_POST["id_articulo"] ?? 0);
$cantidad      = filter_var($_POST["cantidad"] ?? "", FILTER_VALIDATE_INT);
$persona       = trim($_SESSION["panol_nombre"] . " " . $_SESSION["panol_apellido"]);
$observaciones = trim((string)($_POST["observaciones"] ?? ""));
$id_usuario    = (int)$_SESSION["panol_id_usuario"];

/* Validar y guardar */
$error = registrar_prestamo_articulo($conexion, $id_articulo, $cantidad, $persona, $observaciones, $id_usuario);

if ($error !== "") {
    mostrar_error($error);
}

header("Location: mis_prestamos.php?ok=creado");
exit();
?>
