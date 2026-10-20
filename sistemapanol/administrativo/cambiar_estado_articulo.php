<?php

session_start();

/* Verificar sesión y rol de administrador */
if (
    !isset($_SESSION["panol_id_usuario"]) ||
    !isset($_SESSION["panol_id_rol"]) ||
    (int)$_SESSION["panol_id_rol"] !== 1
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");

/* Obtener y validar datos */
$id           = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$nuevo_estado = $_GET["estado"] ?? "";

if ($id === false || $id === null || $id <= 0 || !in_array($nuevo_estado, ["Alta", "Baja"], true)) {
    mostrar_error("Los datos enviados no son válidos.", "articulos.php");
}

/* No se puede dar de baja un artículo que está prestado */
if ($nuevo_estado === "Baja") {

    $stmt = mysqli_prepare(
        $conexion,
        "SELECT COUNT(*) AS total
         FROM prestamos_articulos
         WHERE id_articulo = ? AND estado = 'Pendiente'"
    );

    if (!$stmt) {
        mostrar_error("Error al preparar la consulta.", "articulos.php");
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $fila = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ((int)($fila["total"] ?? 0) > 0) {
        header("Location: articulos.php?error=prestado");
        exit();
    }
}

/* Dar de alta o de baja el artículo */
$stmt = mysqli_prepare($conexion, "UPDATE articulos SET estado = ? WHERE id_articulo = ?");

if (!$stmt) {
    mostrar_error("Error al preparar la consulta.", "articulos.php");
}

mysqli_stmt_bind_param($stmt, "si", $nuevo_estado, $id);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudo cambiar el estado del artículo.", "articulos.php");
}

mysqli_stmt_close($stmt);

header("Location: articulos.php?ok=" . ($nuevo_estado === "Alta" ? "alta" : "baja"));
exit();
?>
