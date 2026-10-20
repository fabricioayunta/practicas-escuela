<?php
/*
|--------------------------------------------------------------------------
| FUNCIONES PROPIAS DEL PAÑOL INFORMÁTICO
|--------------------------------------------------------------------------
| Las funciones generales están en sistemalaboratorios/includes/funciones.php
*/

/* Registra una entrega de insumo. Devuelve "" si salió bien o el mensaje de error */
function registrar_prestamo_insumo($conexion, $insumo, $cantidad, $persona, $observaciones, $id_usuario)
{
    if ($insumo === "" || mb_strlen($insumo) > 100) {
        return "Indique el insumo (hasta 100 caracteres).";
    }

    if ($cantidad === false || $cantidad < 1 || $cantidad > 9999) {
        return "La cantidad debe ser un número entre 1 y 9999.";
    }

    if ($persona === "" || mb_strlen($persona) > 100) {
        return "Indique a quién se le entrega (hasta 100 caracteres).";
    }

    if (mb_strlen($observaciones) > 255) {
        return "Las observaciones son demasiado largas.";
    }

    $stmt = mysqli_prepare(
        $conexion,
        "INSERT INTO prestamos_insumos
            (insumo, cantidad, persona, estado, observaciones, id_usuario)
         VALUES
            (?, ?, ?, 'Pendiente', ?, ?)"
    );

    if (!$stmt) {
        error_log(mysqli_error($conexion));
        return "No se pudo guardar la entrega.";
    }

    mysqli_stmt_bind_param($stmt, "sissi", $insumo, $cantidad, $persona, $observaciones, $id_usuario);

    $ok = mysqli_stmt_execute($stmt);

    if (!$ok) {
        error_log(mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

    return $ok ? "" : "No se pudo guardar la entrega.";
}

/* Registra la devolución de un insumo pendiente */
function devolver_prestamo_insumo($conexion, $id_prestamo)
{
    $stmt = mysqli_prepare(
        $conexion,
        "UPDATE prestamos_insumos
         SET estado = 'Devuelto', fecha_devolucion = NOW()
         WHERE id_prestamo = ? AND estado = 'Pendiente'"
    );

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $id_prestamo);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok;
}
