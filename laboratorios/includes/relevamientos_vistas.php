<?php
/*
|--------------------------------------------------------------------------
| PANTALLAS COMPARTIDAS DE RELEVAMIENTOS (EMATP, Administrativo, Profesor)
|--------------------------------------------------------------------------
| Cada rol tiene sus propios archivos .php (con su menú y sus permisos) que
| llaman a estas funciones, así el aspecto y los datos son los mismos.
|
|   $rol = 1 Administrativo | 2 Profesor | 3 EMATP
*/

require_once __DIR__ . "/relevamientos.php";


/* ======================================================================
   LISTADO CON FILTROS
   ====================================================================== */

function rel_vista_filtros($conexion, $filtros, $rol)
{
    $laboratorios = mysqli_query($conexion, "SELECT id_laboratorio, nombre FROM laboratorios ORDER BY nombre");

    $profesores = null;

    if ((int)$rol !== 2) {
        $profesores = mysqli_query(
            $conexion,
            "SELECT DISTINCT u.id_usuario, u.nombre, u.apellido
             FROM relevamientos r
             INNER JOIN usuarios u ON u.id_usuario = r.id_usuario
             ORDER BY u.apellido, u.nombre"
        );
    }
    ?>

    <div class="tarjeta">

        <form method="GET" class="filtros">

            <div class="campo">
                <label for="laboratorio">Laboratorio</label>
                <select id="laboratorio" name="laboratorio">
                    <option value="">Todos</option>
                    <?php while ($lab = mysqli_fetch_assoc($laboratorios)) { ?>
                        <option value="<?php echo (int)$lab["id_laboratorio"]; ?>"
                            <?php echo $filtros["laboratorio"] === (int)$lab["id_laboratorio"] ? "selected" : ""; ?>>
                            <?php echo e($lab["nombre"]); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="campo">
                <label for="turno">Turno</label>
                <select id="turno" name="turno">
                    <option value="">Todos</option>
                    <?php foreach (turnos_relevamiento() as $turno) { ?>
                        <option value="<?php echo e($turno); ?>" <?php echo $filtros["turno"] === $turno ? "selected" : ""; ?>>
                            <?php echo e($turno); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="campo">
                <label for="resultado">Resultado</label>
                <select id="resultado" name="resultado">
                    <option value="">Todos</option>
                    <option value="<?php echo e(REL_RESULTADO_OK); ?>" <?php echo $filtros["resultado"] === REL_RESULTADO_OK ? "selected" : ""; ?>>Habilitado — Todo OK</option>
                    <option value="<?php echo e(REL_RESULTADO_PROBLEMA); ?>" <?php echo $filtros["resultado"] === REL_RESULTADO_PROBLEMA ? "selected" : ""; ?>>Con inconvenientes</option>
                </select>
            </div>

            <div class="campo">
                <label for="desde">Desde</label>
                <input type="date" id="desde" name="desde" value="<?php echo e($filtros["desde"]); ?>">
            </div>

            <div class="campo">
                <label for="hasta">Hasta</label>
                <input type="date" id="hasta" name="hasta" value="<?php echo e($filtros["hasta"]); ?>">
            </div>

            <?php if ($profesores) { ?>
                <div class="campo">
                    <label for="profesor">Profesor</label>
                    <select id="profesor" name="profesor">
                        <option value="">Todos</option>
                        <?php while ($p = mysqli_fetch_assoc($profesores)) { ?>
                            <option value="<?php echo (int)$p["id_usuario"]; ?>"
                                <?php echo $filtros["profesor"] === (int)$p["id_usuario"] ? "selected" : ""; ?>>
                                <?php echo e($p["nombre"] . " " . $p["apellido"]); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            <?php } ?>

            <button type="submit">Filtrar</button>
            <a class="btn btn-secundario" href="<?php echo e(rel_pagina_listado($rol)); ?>">Quitar filtros</a>

        </form>

    </div>

    <?php
}

function rel_vista_tabla($resultado, $rol)
{
    $verProfesor = ((int)$rol !== 2);
    $columnas    = $verProfesor ? 9 : 8;
    ?>

    <div class="tabla-responsive">
        <table>
            <thead>
                <tr>
                    <th>N.º</th>
                    <th>Laboratorio</th>
                    <th>Turno</th>
                    <th>Fecha y hora</th>
                    <?php if ($verProfesor) { ?><th>Profesor</th><?php } ?>
                    <th>Resultado</th>
                    <th>Revisadas</th>
                    <th>Con inconvenientes</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>

            <?php
            $hayFilas = false;

            while ($fila = mysqli_fetch_assoc($resultado)) {
                $hayFilas = true;
                ?>
                <tr>
                    <td><?php echo (int)$fila["id_relevamiento"]; ?></td>
                    <td><strong><?php echo e($fila["laboratorio"]); ?></strong></td>
                    <td><?php echo e($fila["turno"]); ?></td>
                    <td><?php echo e(fecha_hora($fila["fecha"])); ?></td>
                    <?php if ($verProfesor) { ?>
                        <td><?php echo e($fila["nombre"] . " " . $fila["apellido"]); ?></td>
                    <?php } ?>
                    <td><?php echo etiqueta_resultado($fila["resultado"]); ?></td>
                    <td><?php echo (int)$fila["revisadas"]; ?> de <?php echo (int)$fila["total_computadoras"]; ?></td>
                    <td><?php echo (int)$fila["con_inconvenientes"]; ?></td>
                    <td>
                        <a class="btn btn-chico" href="ver_relevamiento.php?id=<?php echo (int)$fila["id_relevamiento"]; ?>">Ver detalle</a>
                    </td>
                </tr>
            <?php } ?>

            <?php if (!$hayFilas) { ?>
                <tr>
                    <td class="sin-datos" colspan="<?php echo (int)$columnas; ?>">No se encontraron relevamientos con esos datos.</td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>

    <?php
}

function rel_vista_paginacion($filtros, $pagina, $paginas, $total)
{
    $base = [];

    foreach (["laboratorio", "profesor", "turno", "resultado", "desde", "hasta"] as $clave) {
        if ($filtros[$clave] !== "" && $filtros[$clave] !== 0) {
            $base[$clave] = $filtros[$clave];
        }
    }
    ?>

    <p class="ayuda">
        <?php echo (int)$total; ?> relevamiento<?php echo $total === 1 ? "" : "s"; ?> encontrado<?php echo $total === 1 ? "" : "s"; ?>.
        Página <?php echo (int)$pagina; ?> de <?php echo (int)$paginas; ?>.
    </p>

    <?php if ($paginas > 1) { ?>
        <div class="barra-acciones">
            <?php if ($pagina > 1) { ?>
                <a class="btn btn-secundario btn-chico" href="?<?php echo e(http_build_query($base + ["pagina" => $pagina - 1])); ?>">← Anteriores</a>
            <?php } ?>
            <?php if ($pagina < $paginas) { ?>
                <a class="btn btn-secundario btn-chico" href="?<?php echo e(http_build_query($base + ["pagina" => $pagina + 1])); ?>">Siguientes →</a>
            <?php } ?>
        </div>
    <?php } ?>

    <?php
}

/* Pantalla completa del listado (la usan EMATP, Administrativo y Profesor) */
function rel_vista_listado($conexion, $rol, $idUsuarioFijo = 0)
{
    $filtros = rel_leer_filtros();

    list($resultado, $total, $pagina, $paginas) = rel_consultar_listado($conexion, $filtros, $idUsuarioFijo);

    rel_vista_filtros($conexion, $filtros, $rol);
    rel_vista_tabla($resultado, $rol);
    rel_vista_paginacion($filtros, $pagina, $paginas, $total);
}


/* ======================================================================
   DETALLE DE UN RELEVAMIENTO
   ====================================================================== */

function rel_vista_detalle($conexion, $idRelevamiento, $rol, $idUsuarioFijo = 0)
{
    $rol = (int)$rol;

    /* Datos generales (el profesor solo puede abrir los suyos) */

    $sql = "SELECT
                r.*,
                COALESCE(l.nombre, r.laboratorio_nombre) AS laboratorio,
                u.nombre,
                u.apellido
            FROM relevamientos r
            INNER JOIN usuarios u
                ON u.id_usuario = r.id_usuario
            LEFT JOIN laboratorios l
                ON l.id_laboratorio = r.id_laboratorio
            WHERE r.id_relevamiento = ?";

    if ($idUsuarioFijo > 0) {
        $sql .= " AND r.id_usuario = ?";
    }

    $stmt = mysqli_prepare($conexion, $sql);

    if (!$stmt) {
        error_log("rel_vista_detalle: " . mysqli_error($conexion));
        mostrar_error("No se pudo consultar el relevamiento.", rel_pagina_listado($rol));
    }

    if ($idUsuarioFijo > 0) {
        mysqli_stmt_bind_param($stmt, "ii", $idRelevamiento, $idUsuarioFijo);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $idRelevamiento);
    }

    mysqli_stmt_execute($stmt);
    $rel = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if (!$rel) {
        mostrar_error("No se encontró el relevamiento.", rel_pagina_listado($rol));
    }

    /* Detalle por computadora */

    $stmt = mysqli_prepare(
        $conexion,
        "SELECT
            d.*,
            t.estado AS ticket_estado
         FROM relevamiento_detalle d
         LEFT JOIN tickets t
            ON t.id_ticket = d.id_ticket
         WHERE d.id_relevamiento = ?
         ORDER BY d.numero_pc"
    );

    mysqli_stmt_bind_param($stmt, "i", $idRelevamiento);
    mysqli_stmt_execute($stmt);
    $resultadoDetalle = mysqli_stmt_get_result($stmt);

    $filas     = [];
    $afectadas = [];

    while ($fila = mysqli_fetch_assoc($resultadoDetalle)) {
        $filas[] = $fila;

        if ($fila["estado"] === REL_ESTADO_PROBLEMA) {
            $afectadas[] = "PC " . $fila["numero_pc"];
        }
    }

    mysqli_stmt_close($stmt);

    /* Control anterior de cada computadora con inconvenientes */

    $stmtAnterior = mysqli_prepare(
        $conexion,
        "SELECT d2.estado, r2.fecha, r2.turno, u2.nombre, u2.apellido
         FROM relevamiento_detalle d2
         INNER JOIN relevamientos r2
            ON r2.id_relevamiento = d2.id_relevamiento
         INNER JOIN usuarios u2
            ON u2.id_usuario = r2.id_usuario
         WHERE d2.id_computadora = ?
           AND d2.estado <> 'Fuera de servicio'
           AND r2.id_relevamiento < ?
         ORDER BY r2.id_relevamiento DESC
         LIMIT 1"
    );

    $idPc  = 0;
    $idRel = (int)$idRelevamiento;

    if ($stmtAnterior) {
        mysqli_stmt_bind_param($stmtAnterior, "ii", $idPc, $idRel);
    }

    $totalPc = (int)$rel["total_computadoras"];
    ?>

    <div class="encabezado-pagina">
        <h1><?php echo e($rel["laboratorio"]); ?> — Turno <?php echo e(mb_strtolower($rel["turno"], "UTF-8")); ?></h1>
        <p>Relevamiento n.º <?php echo (int)$rel["id_relevamiento"]; ?> · <?php echo e(fecha_hora($rel["fecha"])); ?></p>
    </div>

    <div class="barra-acciones">
        <a class="btn btn-secundario" href="<?php echo e(rel_pagina_listado($rol)); ?>">← Volver a los relevamientos</a>
    </div>

    <div class="tarjeta">

        <h2 class="sin-margen-arriba">Resumen</h2>

        <div class="tabla-responsive sin-sombra">
            <table class="ficha">
                <tr><td>Laboratorio</td><td><?php echo e($rel["laboratorio"]); ?></td></tr>
                <tr><td>Turno</td><td><?php echo e($rel["turno"]); ?></td></tr>
                <tr><td>Fecha y hora</td><td><?php echo e(fecha_hora($rel["fecha"])); ?></td></tr>
                <tr><td>Responsable</td><td><?php echo e($rel["nombre"] . " " . $rel["apellido"]); ?></td></tr>
                <tr><td>Resultado</td><td><?php echo etiqueta_resultado($rel["resultado"]); ?></td></tr>
                <tr>
                    <td>Estado de revisión</td>
                    <td>
                        <?php echo (int)$rel["revisadas"]; ?> de <?php echo $totalPc; ?> computadoras revisadas
                        <?php if ($totalPc > (int)$rel["revisadas"]) { ?>
                            <span class="ayuda">(<?php echo $totalPc - (int)$rel["revisadas"]; ?> fuera de servicio, no se revisan)</span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>Computadoras con inconvenientes</td>
                    <td>
                        <?php echo (int)$rel["con_inconvenientes"]; ?>
                        <?php if ($afectadas) { ?>
                            — <strong><?php echo e(implode(", ", $afectadas)); ?></strong>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>Observaciones generales</td>
                    <td><?php echo trim((string)$rel["observaciones"]) === "" ? '<span class="ayuda">Sin observaciones</span>' : nl2br(e($rel["observaciones"])); ?></td>
                </tr>
            </table>
        </div>

        <p class="ayuda">Este registro es parte del historial y no se puede modificar.</p>

    </div>

    <h2>Detalle por computadora</h2>

    <div class="tabla-responsive">
        <table>
            <thead>
                <tr>
                    <th>Computadora</th>
                    <th>Estado</th>
                    <th>Observación</th>
                    <th>Ticket relacionado</th>
                    <th>Control anterior</th>
                    <?php if ($rol !== 2) { ?><th>Historial</th><?php } ?>
                </tr>
            </thead>
            <tbody>

            <?php if (!$filas) { ?>
                <tr>
                    <td class="sin-datos" colspan="<?php echo $rol !== 2 ? 6 : 5; ?>">Este relevamiento no tiene computadoras registradas.</td>
                </tr>
            <?php } ?>

            <?php foreach ($filas as $fila) { ?>
                <tr class="<?php echo $fila["estado"] === REL_ESTADO_PROBLEMA ? "fila-problema" : ""; ?>">
                    <td><strong>PC <?php echo (int)$fila["numero_pc"]; ?></strong></td>
                    <td><?php echo etiqueta_estado_pc($fila["estado"]); ?></td>
                    <td><?php echo trim((string)$fila["observacion"]) === "" ? "—" : nl2br(e($fila["observacion"])); ?></td>
                    <td>
                        <?php
                        if (!empty($fila["id_ticket"])) {
                            echo rel_enlace_ticket($rol, $fila["id_ticket"], $fila["ticket_estado"] ?? "");
                            echo '<br><span class="ayuda">' . ((int)$fila["ticket_nuevo"] === 1 ? "Creado en este relevamiento" : "Ticket que ya existía") . '</span>';
                        } else {
                            echo "—";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($fila["estado"] === REL_ESTADO_PROBLEMA && $stmtAnterior && !empty($fila["id_computadora"])) {

                            $idPc = (int)$fila["id_computadora"];

                            mysqli_stmt_execute($stmtAnterior);
                            $ant = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtAnterior));

                            if ($ant) {
                                echo etiqueta_estado_pc($ant["estado"]);
                                echo '<br><span class="ayuda">' . e($ant["turno"]) . ", " . e(fecha_hora($ant["fecha"]));

                                if ($rol !== 2) {
                                    echo " · " . e($ant["nombre"] . " " . $ant["apellido"]);
                                }

                                echo '</span>';
                            } else {
                                echo '<span class="ayuda">Sin control anterior</span>';
                            }
                        } else {
                            echo "—";
                        }
                        ?>
                    </td>
                    <?php if ($rol !== 2) { ?>
                        <td>
                            <?php if (!empty($fila["id_computadora"])) { ?>
                                <a class="btn btn-chico btn-secundario"
                                   href="historial_relevamientos_pc.php?id=<?php echo (int)$fila["id_computadora"]; ?>">Ver historial</a>
                            <?php } else { ?>
                                <span class="ayuda">Computadora eliminada</span>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>

    <?php
    if ($stmtAnterior) {
        mysqli_stmt_close($stmtAnterior);
    }
}


/* ======================================================================
   HISTORIAL DE UNA COMPUTADORA (comparación entre controles sucesivos)
   Solo EMATP y Administrativo.
   ====================================================================== */

function rel_vista_historial_pc($conexion, $idComputadora, $rol)
{
    $rol = (int)$rol;

    $stmt = mysqli_prepare(
        $conexion,
        "SELECT c.id_computadora, c.numero_pc, c.estado, l.nombre AS laboratorio
         FROM computadoras c
         INNER JOIN laboratorios l
            ON l.id_laboratorio = c.id_laboratorio
         WHERE c.id_computadora = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $idComputadora);
    mysqli_stmt_execute($stmt);
    $pc = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if (!$pc) {
        mostrar_error("No se encontró la computadora.", rel_pagina_listado($rol));
    }

    /* Todos los controles de esa computadora, del más viejo al más nuevo */

    $stmt = mysqli_prepare(
        $conexion,
        "SELECT
            d.estado,
            d.observacion,
            d.id_ticket,
            d.ticket_nuevo,
            t.estado AS ticket_estado,
            r.id_relevamiento,
            r.fecha,
            r.turno,
            u.nombre,
            u.apellido
         FROM relevamiento_detalle d
         INNER JOIN relevamientos r
            ON r.id_relevamiento = d.id_relevamiento
         INNER JOIN usuarios u
            ON u.id_usuario = r.id_usuario
         LEFT JOIN tickets t
            ON t.id_ticket = d.id_ticket
         WHERE d.id_computadora = ?
         ORDER BY r.id_relevamiento ASC"
    );

    mysqli_stmt_bind_param($stmt, "i", $idComputadora);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    $controles = [];

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $controles[] = $fila;
    }

    mysqli_stmt_close($stmt);

    /*
       Comparación: se recorren solo los controles realmente revisados
       (se ignoran los de "Fuera de servicio") y se buscan los cambios de estado.
    */

    $cambios     = [];
    $anterior    = null;
    $cambioPorId = [];

    foreach ($controles as $control) {

        if ($control["estado"] === REL_ESTADO_BAJA) {
            continue;
        }

        if ($anterior !== null && $anterior["estado"] !== $control["estado"]) {

            $tipo = ($control["estado"] === REL_ESTADO_PROBLEMA) ? "detectado" : "normalizado";

            $cambios[] = ["tipo" => $tipo, "antes" => $anterior, "despues" => $control];
            $cambioPorId[(int)$control["id_relevamiento"]] = $tipo;
        }

        $anterior = $control;
    }

    $hayProblemas = false;

    foreach ($controles as $control) {
        if ($control["estado"] === REL_ESTADO_PROBLEMA) {
            $hayProblemas = true;
            break;
        }
    }

    $primerRevisado = null;

    foreach ($controles as $control) {
        if ($control["estado"] !== REL_ESTADO_BAJA) {
            $primerRevisado = $control;
            break;
        }
    }

    $descripcionControl = function ($c) {
        return e($c["turno"]) . ", " . e(fecha_hora($c["fecha"])) . " · " . e($c["nombre"] . " " . $c["apellido"]);
    };
    ?>

    <div class="encabezado-pagina">
        <h1>PC <?php echo (int)$pc["numero_pc"]; ?> — <?php echo e($pc["laboratorio"]); ?></h1>
        <p>
            Historial de relevamientos ·
            Estado actual:
            <?php echo $pc["estado"] === "Alta"
                ? '<span class="estado operativa">Operativa</span>'
                : '<span class="estado fuera-servicio">Fuera de servicio</span>'; ?>
        </p>
    </div>

    <div class="barra-acciones">
        <a class="btn btn-secundario" href="<?php echo e(rel_pagina_listado($rol)); ?>">← Volver a los relevamientos</a>
    </div>

    <div class="tarjeta">

        <h2 class="sin-margen-arriba">Comparación entre controles</h2>

        <?php if (!$controles) { ?>

            <p>Esta computadora todavía no figura en ningún relevamiento.</p>

        <?php } else { ?>

            <?php foreach (array_reverse($cambios) as $cambio) { ?>

                <?php if ($cambio["tipo"] === "detectado") { ?>
                    <div class="rel-comparacion rel-comparacion-problema">
                        <strong><span aria-hidden="true">⚠</span> Inconveniente detectado entre dos controles</strong>
                        <ul>
                            <li>
                                <strong>Último control en condiciones:</strong>
                                <?php echo $descripcionControl($cambio["antes"]); ?>
                            </li>
                            <li>
                                <strong>Primer control con inconvenientes:</strong>
                                <?php echo $descripcionControl($cambio["despues"]); ?>
                            </li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <div class="rel-comparacion">
                        <strong><span aria-hidden="true">✔</span> Volvió a figurar en condiciones</strong>
                        <ul>
                            <li>
                                <strong>Control con inconvenientes:</strong>
                                <?php echo $descripcionControl($cambio["antes"]); ?>
                            </li>
                            <li>
                                <strong>Primer control en condiciones posterior:</strong>
                                <?php echo $descripcionControl($cambio["despues"]); ?>
                            </li>
                        </ul>
                    </div>
                <?php } ?>

            <?php } ?>

            <?php if (!$cambios) { ?>
                <?php if ($hayProblemas) { ?>
                    <div class="rel-comparacion rel-comparacion-problema">
                        <strong><span aria-hidden="true">⚠</span> Siempre figuró con inconvenientes</strong>
                        <p>Todos los controles revisados informaron inconvenientes<?php echo $primerRevisado ? " (el primero fue " . $descripcionControl($primerRevisado) . ")" : ""; ?>. No hay un control previo en condiciones para comparar.</p>
                    </div>
                <?php } else { ?>
                    <div class="rel-comparacion">
                        <strong><span aria-hidden="true">✔</span> Sin cambios entre controles</strong>
                        <p>No se registraron inconvenientes en los controles de esta computadora.</p>
                    </div>
                <?php } ?>
            <?php } ?>

            <p class="ayuda">
                Esta comparación solo indica entre qué controles cambió lo informado.
                No demuestra cuándo se originó la falla ni quién la causó.
            </p>

        <?php } ?>

    </div>

    <h2>Controles registrados</h2>

    <div class="tabla-responsive">
        <table>
            <thead>
                <tr>
                    <th>Fecha y hora</th>
                    <th>Turno</th>
                    <th>Profesor</th>
                    <th>Estado registrado</th>
                    <th>Observaciones</th>
                    <th>Ticket</th>
                    <th>Relevamiento</th>
                </tr>
            </thead>
            <tbody>

            <?php if (!$controles) { ?>
                <tr>
                    <td class="sin-datos" colspan="7">Sin controles registrados.</td>
                </tr>
            <?php } ?>

            <?php foreach (array_reverse($controles) as $control) { ?>
                <tr class="<?php echo ($cambioPorId[(int)$control["id_relevamiento"]] ?? "") === "detectado" ? "fila-problema" : ""; ?>">
                    <td><?php echo e(fecha_hora($control["fecha"])); ?></td>
                    <td><?php echo e($control["turno"]); ?></td>
                    <td><?php echo e($control["nombre"] . " " . $control["apellido"]); ?></td>
                    <td>
                        <?php echo etiqueta_estado_pc($control["estado"]); ?>
                        <?php
                        $cambio = $cambioPorId[(int)$control["id_relevamiento"]] ?? "";

                        if ($cambio === "detectado") {
                            echo '<br><span class="ayuda">Primer control con inconvenientes tras uno en condiciones</span>';
                        } elseif ($cambio === "normalizado") {
                            echo '<br><span class="ayuda">Volvió a estar en condiciones</span>';
                        }
                        ?>
                    </td>
                    <td><?php echo trim((string)$control["observacion"]) === "" ? "—" : nl2br(e($control["observacion"])); ?></td>
                    <td><?php echo !empty($control["id_ticket"]) ? rel_enlace_ticket($rol, $control["id_ticket"], $control["ticket_estado"] ?? "") : "—"; ?></td>
                    <td>
                        <a class="btn btn-chico btn-secundario" href="ver_relevamiento.php?id=<?php echo (int)$control["id_relevamiento"]; ?>">
                            N.º <?php echo (int)$control["id_relevamiento"]; ?>
                        </a>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>

    <?php
}
