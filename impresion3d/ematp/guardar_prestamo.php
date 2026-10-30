<?php

session_start();

/* Verificar sesión y rol */
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
require_once("../includes/funciones_pi.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: crear_prestamo.php");
    exit();
}

if (!token_valido("pi_csrf_token")) {
    mostrar_error("El formulario venció o no es válido. Vuelva a abrir la nueva entrega.");
}

/* Obtener y limpiar datos */
$insumo          = trim((string)($_POST["insumo"] ?? ""));
$cantidad        = filter_var($_POST["cantidad"] ?? "", FILTER_VALIDATE_INT);
$persona         = trim((string)($_POST["persona"] ?? ""));
$fecha_prest_val = trim((string)($_POST["fecha_prestamo"] ?? ""));
$hora_prest_val  = trim((string)($_POST["hora_prestamo"] ?? ""));
$fecha_dev_val   = trim((string)($_POST["fecha_devolucion"] ?? ""));
$hora_dev_val    = trim((string)($_POST["hora_devolucion"] ?? ""));
$observaciones   = trim((string)($_POST["observaciones"] ?? ""));
$id_usuario      = (int)$_SESSION["pi_id_usuario"];

/* Combinar fecha y hora de entrega */
$fecha_entrega = date("Y-m-d H:i:s");
if (!empty($fecha_prest_val)) {
    $h_prest = !empty($hora_prest_val) ? $hora_prest_val : "00:00";
    $fecha_entrega = $fecha_prest_val . " " . $h_prest . ":00";
}

/* Combinar fecha y hora de devolución */
$fecha_devolucion = null;
if (!empty($fecha_dev_val)) {
    $h_dev = !empty($hora_dev_val) ? $hora_dev_val : "00:00";
    $fecha_devolucion = $fecha_dev_val . " " . $h_dev . ":00";
}

if (empty($insumo) || !$cantidad || empty($persona) || empty($fecha_devolucion)) {
    echo "<script>alert('Complete todos los campos obligatorios, incluyendo la fecha de devolución.'); window.history.back();</script>";
    exit();
}

/* Guardar en prestamos_insumos */
$stmt = $conexion->prepare("INSERT INTO prestamos_insumos (insumo, cantidad, persona, fecha_devolucion, observaciones, id_usuario, estado, fecha_entrega) VALUES (?, ?, ?, ?, ?, ?, 'Pendiente', ?)");

if ($stmt) {
    $stmt->bind_param("sisssis", $insumo, $cantidad, $persona, $fecha_devolucion, $observaciones, $id_usuario, $fecha_entrega);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: prestamos.php?ok=creado");
        exit();
    } else {
        error_log("Error al insertar: " . $stmt->error);
        echo "<script>alert('Error al guardar en la base de datos.'); window.history.back();</script>";
        exit();
    }
} else {
    error_log("Error al preparar la consulta: " . $conexion->error);
    echo "<script>alert('Error en la estructura de la consulta SQL.'); window.history.back();</script>";
    exit();
}
?>