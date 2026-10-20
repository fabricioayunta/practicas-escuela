<?php
/*
|--------------------------------------------------------------------------
| FUNCIONES PROPIAS DEL PAÑOL
|--------------------------------------------------------------------------
| Las funciones generales están en sistemalaboratorios/includes/funciones.php
*/

/*
   Disponibles = cantidad del artículo - lo que está prestado sin devolver.
   Solo cuentan los artículos dados de alta.
*/
function sql_disponibles()
{
    return "SELECT
                       articulos.id_articulo,
                       articulos.nombre,
                       articulos.cantidad - COALESCE(SUM(prestamos_articulos.cantidad), 0) AS disponibles
                   FROM articulos
                   LEFT JOIN prestamos_articulos
                       ON prestamos_articulos.id_articulo = articulos.id_articulo
                       AND prestamos_articulos.estado = 'Pendiente'
                   WHERE articulos.estado = 'Alta'";
}

/* Lista de artículos que se pueden prestar (con al menos uno disponible) */
function articulos_disponibles($conexion)
{
    $articulos = [];

    $resultado = mysqli_query(
        $conexion,
        sql_disponibles() . " GROUP BY articulos.id_articulo, articulos.nombre, articulos.cantidad
                            HAVING disponibles > 0
                            ORDER BY articulos.nombre"
    );

    if (!$resultado) {
        error_log(mysqli_error($conexion));
        return $articulos;
    }

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $articulos[] = $fila;
    }

    return $articulos;
}

/* Cuántos hay disponibles de un artículo (null si no existe o está de baja) */
function disponibles_de_articulo($conexion, $id_articulo)
{
    $stmt = mysqli_prepare(
        $conexion,
        sql_disponibles() . " AND articulos.id_articulo = ?
                            GROUP BY articulos.id_articulo, articulos.nombre, articulos.cantidad"
    );

    if (!$stmt) {
        error_log(mysqli_error($conexion));
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $id_articulo);
    mysqli_stmt_execute($stmt);
    $fila = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    return $fila ? (int)$fila["disponibles"] : null;
}

/* Registra un préstamo. Devuelve "" si salió bien o el mensaje de error */
function registrar_prestamo_articulo($conexion, $id_articulo, $cantidad, $persona, $observaciones, $id_usuario)
{
    if ($id_articulo <= 0) {
        return "Seleccione un artículo.";
    }

    if ($cantidad === false || $cantidad < 1) {
        return "La cantidad debe ser un número mayor a cero.";
    }

    if ($persona === "" || mb_strlen($persona) > 100) {
        return "Indique a quién se le presta (hasta 100 caracteres).";
    }

    if (mb_strlen($observaciones) > 255) {
        return "Las observaciones son demasiado largas.";
    }

    $disponibles = disponibles_de_articulo($conexion, $id_articulo);

    if ($disponibles === null) {
        return "El artículo no existe o está dado de baja.";
    }

    if ($cantidad > $disponibles) {
        return "Solo hay " . $disponibles . " unidad(es) disponible(s) de ese artículo.";
    }

    $stmt = mysqli_prepare(
        $conexion,
        "INSERT INTO prestamos_articulos
            (id_articulo, cantidad, persona, estado, observaciones, id_usuario)
         VALUES
            (?, ?, ?, 'Pendiente', ?, ?)"
    );

    if (!$stmt) {
        error_log(mysqli_error($conexion));
        return "No se pudo guardar el préstamo.";
    }

    mysqli_stmt_bind_param($stmt, "iissi", $id_articulo, $cantidad, $persona, $observaciones, $id_usuario);

    $ok = mysqli_stmt_execute($stmt);

    if (!$ok) {
        error_log(mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

    return $ok ? "" : "No se pudo guardar el préstamo.";
}

/* Registra la devolución de un préstamo pendiente */
function devolver_prestamo_articulo($conexion, $id_prestamo)
{
    $stmt = mysqli_prepare(
        $conexion,
        "UPDATE prestamos_articulos
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
