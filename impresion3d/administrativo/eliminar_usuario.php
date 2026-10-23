<?php

session_start();

/* Verificar sesión y rol de administrador */
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

/* Obtener y validar ID */
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    mostrar_error("ID inválido.", "usuarios.php");
}

/* Evitar que el administrador se elimine a sí mismo */
if ($id === (int)$_SESSION["pi_id_usuario"]) {
    mostrar_error("No puede eliminar su propio usuario.", "usuarios.php");
}

/*
   Primero se quitan sus módulos y después el usuario.
   Si el usuario no se puede borrar, se deshace todo.
*/
mysqli_begin_transaction($conexion);

if (!guardar_modulos_usuario($conexion, $id, [])) {
    mysqli_rollback($conexion);
    mostrar_error("Error al eliminar usuario.", "usuarios.php");
}

/* Preparar consulta */
$stmt = mysqli_prepare(
    $conexion,
    "DELETE FROM usuarios WHERE id_usuario = ?"
);

if (!$stmt) {
    mysqli_rollback($conexion);
    mostrar_error("Error al preparar la consulta.", "usuarios.php");
}

/* Vincular ID */
mysqli_stmt_bind_param($stmt, "i", $id);

/* Ejecutar eliminación */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_commit($conexion);
    header("Location: usuarios.php?ok=eliminado");
    exit();
}

/* Error (1451 = el usuario tiene tickets, préstamos o historial asociados) */
$codigo = mysqli_stmt_errno($stmt);
mysqli_stmt_close($stmt);
mysqli_rollback($conexion);

if ($codigo == 1451) {
    mostrar_error("No se puede eliminar este usuario porque tiene tickets, préstamos o movimientos registrados. Para que no pueda ingresar más, quítele los módulos o cámbiele la contraseña.", "usuarios.php");
}

mostrar_error("Error al eliminar usuario.", "usuarios.php");
?>
