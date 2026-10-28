<?php
session_start();

/* Puente de compatibilidad de sesión */
if (!isset($_SESSION["pi_id_usuario"]) && isset($_SESSION["id_usuario"])) {
    $_SESSION["pi_id_usuario"] = $_SESSION["id_usuario"];
}
if (!isset($_SESSION["pi_id_rol"]) && isset($_SESSION["rol"])) {
    if ($_SESSION["rol"] == 'administrativo' || $_SESSION["rol"] == 1) {
        $_SESSION["pi_id_rol"] = 1;
    }
}

/* Verificar sesión y rol (Administrativo = 1) */
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

/* Obtener y limpiar datos */
$insumo        = trim((string)($_POST["insumo"] ?? ""));
$cantidad      = filter_var($_POST["cantidad"] ?? "", FILTER_VALIDATE_INT);
$persona       = trim((string)($_POST["persona"] ?? ""));
$fecha_raw     = trim((string)($_POST["fecha_limite"] ?? ""));
$observaciones = trim((string)($_POST["observaciones"] ?? ""));
$id_usuario    = (int)$_SESSION["pi_id_usuario"];

// Formatear la fecha de datetime-local a formato de MySQL (YYYY-MM-DD HH:MM:SS)
$fecha_devolucion = null;
if (!empty($fecha_raw)) {
    $fecha_devolucion = date("Y-m-d H:i:s", strtotime($fecha_raw));
}

if (empty($insumo) || !$cantidad || empty($persona) || empty($fecha_devolucion)) {
    echo "<script>alert('Complete todos los campos obligatorios, incluyendo la fecha límite.'); window.history.back();</script>";
    exit();
}

/* Guardar directamente en prestamos_insumos con los tipos y variables exactos */
$stmt = $conexion->prepare("INSERT INTO prestamos_insumos (insumo, cantidad, persona, fecha_devolucion, observaciones, id_usuario, estado, fecha_entrega) VALUES (?, ?, ?, ?, ?, ?, 'Pendiente', NOW())");

if ($stmt) {
    // Tipos exactos:
    // s = insumo (string)
    // i = cantidad (int)
    // s = persona (string)
    // s = fecha_devolucion (string)
    // s = observaciones (string)
    // i = id_usuario (int)
    $stmt->bind_param("sisssi", $insumo, $cantidad, $persona, $fecha_devolucion, $observaciones, $id_usuario);
    
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