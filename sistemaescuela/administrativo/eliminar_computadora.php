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
    exit("ID de computadora inválido.");
}

/* Iniciar transacción */
mysqli_begin_transaction($conexion);

try {

    /* Eliminar componentes asociados */
    $stmt = mysqli_prepare(
        $conexion,
        "DELETE FROM componentes
         WHERE id_computadora = ?"
    );

    if (!$stmt) {
        throw new Exception("Error al preparar componentes.");
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Error al eliminar componentes.");
    }

    mysqli_stmt_close($stmt);


    /* Eliminar historial asociado */
    $stmt = mysqli_prepare(
        $conexion,
        "DELETE FROM historial_computadoras
         WHERE id_computadora = ?"
    );

    if (!$stmt) {
        throw new Exception("Error al preparar historial.");
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Error al eliminar historial.");
    }

    mysqli_stmt_close($stmt);


    /* Eliminar computadora */
    $stmt = mysqli_prepare(
        $conexion,
        "DELETE FROM computadoras
         WHERE id_computadora = ?"
    );

    if (!$stmt) {
        throw new Exception("Error al preparar computadora.");
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Error al eliminar computadora.");
    }

    /* Verificar que realmente se haya eliminado */
    if (mysqli_stmt_affected_rows($stmt) === 0) {
        mysqli_stmt_close($stmt);
        throw new Exception("La computadora no existe.");
    }

    mysqli_stmt_close($stmt);


    /* Confirmar todas las eliminaciones */
    mysqli_commit($conexion);

    header("Location: computadoras.php");
    exit();

} catch (Throwable $e) {

    /* Revertir todos los cambios */
    mysqli_rollback($conexion);

    exit("Error al eliminar la computadora.");
}
?>