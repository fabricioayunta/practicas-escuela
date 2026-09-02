<?php

session_start();

/* Verificar sesión y rol de administrador */
if (
    !isset($_SESSION["id_usuario"]) ||
    !isset($_SESSION["id_rol"]) ||
    (int)$_SESSION["id_rol"] !== 1
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");

/* Obtener y validar ID */
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    exit("ID inválido.");
}

/* Evitar que el administrador se elimine a sí mismo */
if ($id === (int)$_SESSION["id_usuario"]) {
    exit("No puedes eliminar tu propio usuario.");
}

/* Preparar consulta */
$stmt = mysqli_prepare(
    $conexion,
    "DELETE FROM usuarios WHERE id_usuario = ?"
);

if (!$stmt) {
    exit("Error al preparar la consulta.");
}

/* Vincular ID */
mysqli_stmt_bind_param($stmt, "i", $id);

/* Ejecutar eliminación */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: usuarios.php");
    exit();
}

/* Error */
mysqli_stmt_close($stmt);
exit("Error al eliminar usuario.");
?>