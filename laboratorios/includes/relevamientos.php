<?php
/*
|--------------------------------------------------------------------------
| RELEVAMIENTO Y HABILITACIÓN DE LABORATORIOS POR TURNO
|--------------------------------------------------------------------------
| Funciones compartidas por los tres roles (Profesor, EMATP, Administrativo).
| Se carga con:  require_once "../includes/relevamientos.php";
| (después de ../conexion/conexion.php, que ya carga funciones.php)
|
| Tablas: relevamientos y relevamiento_detalle
| (ver actualizar_relevamientos.sql)
*/

/* Estados posibles de una computadora dentro de un relevamiento */
const REL_ESTADO_OK       = "En condiciones";
const REL_ESTADO_PROBLEMA = "Con inconvenientes";
const REL_ESTADO_BAJA     = "Fuera de servicio";

/* Resultado general de un relevamiento */
const REL_RESULTADO_OK       = "Todo OK";
const REL_RESULTADO_PROBLEMA = "Con inconvenientes";

/* Cantidad de relevamientos por página en los listados */
const REL_POR_PAGINA = 25;


/* ======================================================================
   TURNOS
   Si la escuela maneja otros turnos, se cambian SOLO en esta lista.
   ====================================================================== */

function turnos_relevamiento()
{
    return ["Mañana", "Tarde", "Noche"];
}

/* Turno que probablemente corresponde a la hora actual (el profesor lo confirma) */
function turno_sugerido()
{
    try {
        $ahora = new DateTime("now", new DateTimeZone("America/Argentina/Buenos_Aires"));
    } catch (Exception $e) {
        return "Mañana";
    }

    $hora = (int)$ahora->format("G");

    if ($hora < 13) {
        return "Mañana";
    }

    if ($hora < 19) {
        return "Tarde";
    }

    return "Noche";
}


/* ======================================================================
   PEQUEÑAS AYUDAS
   ====================================================================== */

/* Convierte un valor recibido por GET/POST en entero positivo (0 si no es válido) */
function rel_entero($valor)
{
    if (is_string($valor) && ctype_digit($valor) && strlen($valor) <= 9) {
        return (int)$valor;
    }

    return 0;
}

/* Fecha y hora legibles: 07/10/2026 14:05 */
function fecha_hora($valor)
{
    $marca = strtotime((string)$valor);

    return $marca ? date("d/m/Y H:i", $marca) : "—";
}

/* Etiqueta (con texto, no solo color) para el estado de una computadora */
function etiqueta_estado_pc($estado)
{
    if ($estado === REL_ESTADO_OK) {
        return '<span class="estado rel-ok"><span aria-hidden="true">✔</span> En condiciones</span>';
    }

    if ($estado === REL_ESTADO_PROBLEMA) {
        return '<span class="estado rel-problema"><span aria-hidden="true">⚠</span> Con inconvenientes</span>';
    }

    if ($estado === REL_ESTADO_BAJA) {
        return '<span class="estado rel-baja"><span aria-hidden="true">⛔</span> Fuera de servicio</span>';
    }

    return '<span class="estado rel-pendiente"><span aria-hidden="true">…</span> No revisada</span>';
}

/* Etiqueta (con texto, no solo color) para el resultado general */
function etiqueta_resultado($resultado)
{
    if ($resultado === REL_RESULTADO_OK) {
        return '<span class="estado rel-ok"><span aria-hidden="true">✔</span> Habilitado — Todo OK</span>';
    }

    return '<span class="estado rel-problema"><span aria-hidden="true">⚠</span> Con inconvenientes</span>';
}

/* Archivo del listado según el rol (el profesor ve solo "mis relevamientos") */
function rel_pagina_listado($rol)
{
    return ((int)$rol === 2) ? "mis_relevamientos.php" : "relevamientos.php";
}

/* Enlace al ticket relacionado, según lo que cada rol puede abrir */
function rel_enlace_ticket($rol, $idTicket, $estadoTicket = "")
{
    $idTicket = (int)$idTicket;

    if ($idTicket <= 0) {
        return "—";
    }

    $texto = "Ticket n.º " . $idTicket;

    if ((int)$rol === 1) {
        $html = '<a href="ver_ticket.php?id=' . $idTicket . '">' . $texto . '</a>';
    } elseif ((int)$rol === 3) {
        $html = '<a href="editar_ticket.php?id=' . $idTicket . '">' . $texto . '</a>';
    } else {
        $html = e($texto);
    }

    if ($estadoTicket !== "" && $estadoTicket !== null) {
        $html .= ' <span class="estado ' . e(clase_estado($estadoTicket)) . '">' . e($estadoTicket) . '</span>';
    }

    return $html;
}

/* Busca un relevamiento ya guardado con ese código de envío (evita duplicados) */
function rel_buscar_por_token($conexion, $token, $idUsuario)
{
    $stmt = mysqli_prepare(
        $conexion,
        "SELECT id_relevamiento FROM relevamientos WHERE token_envio = ? AND id_usuario = ? LIMIT 1"
    );

    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param($stmt, "si", $token, $idUsuario);
    mysqli_stmt_execute($stmt);
    $fila = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    return $fila ? (int)$fila["id_relevamiento"] : 0;
}


/* ======================================================================
   FILTROS Y LISTADO
   ====================================================================== */

/* Fecha válida con formato AAAA-MM-DD, o "" */
function rel_fecha_valida($valor)
{
    if (!is_string($valor) || $valor === "") {
        return "";
    }

    $fecha = DateTime::createFromFormat("!Y-m-d", $valor);

    return ($fecha && $fecha->format("Y-m-d") === $valor) ? $valor : "";
}

/* Lee y valida los filtros recibidos por GET */
function rel_leer_filtros()
{
    $turno     = $_GET["turno"] ?? "";
    $resultado = $_GET["resultado"] ?? "";

    return [
        "laboratorio" => rel_entero($_GET["laboratorio"] ?? ""),
        "profesor"    => rel_entero($_GET["profesor"] ?? ""),
        "turno"       => (is_string($turno) && in_array($turno, turnos_relevamiento(), true)) ? $turno : "",
        "resultado"   => (is_string($resultado) && in_array($resultado, [REL_RESULTADO_OK, REL_RESULTADO_PROBLEMA], true)) ? $resultado : "",
        "desde"       => rel_fecha_valida($_GET["desde"] ?? ""),
        "hasta"       => rel_fecha_valida($_GET["hasta"] ?? ""),
        "pagina"      => max(1, min(100000, rel_entero($_GET["pagina"] ?? "1"))),
    ];
}

/*
   Devuelve [resultado, total, pagina, paginas].
   $idUsuarioFijo > 0 limita el listado a los relevamientos de ESE usuario
   (así se usa para el profesor: solo ve los suyos).
*/
function rel_consultar_listado($conexion, $filtros, $idUsuarioFijo = 0)
{
    $where  = " WHERE 1 = 1";
    $tipos  = "";
    $params = [];

    if ($idUsuarioFijo > 0) {
        $where   .= " AND r.id_usuario = ?";
        $tipos   .= "i";
        $params[] = $idUsuarioFijo;
    } elseif ($filtros["profesor"] > 0) {
        $where   .= " AND r.id_usuario = ?";
        $tipos   .= "i";
        $params[] = $filtros["profesor"];
    }

    if ($filtros["laboratorio"] > 0) {
        $where   .= " AND r.id_laboratorio = ?";
        $tipos   .= "i";
        $params[] = $filtros["laboratorio"];
    }

    if ($filtros["turno"] !== "") {
        $where   .= " AND r.turno = ?";
        $tipos   .= "s";
        $params[] = $filtros["turno"];
    }

    if ($filtros["resultado"] !== "") {
        $where   .= " AND r.resultado = ?";
        $tipos   .= "s";
        $params[] = $filtros["resultado"];
    }

    if ($filtros["desde"] !== "") {
        $where   .= " AND r.fecha >= ?";
        $tipos   .= "s";
        $params[] = $filtros["desde"] . " 00:00:00";
    }

    if ($filtros["hasta"] !== "") {
        $limite   = (new DateTime($filtros["hasta"]))->modify("+1 day")->format("Y-m-d");
        $where   .= " AND r.fecha < ?";
        $tipos   .= "s";
        $params[] = $limite . " 00:00:00";
    }

    /* Total de resultados */

    $stmt = mysqli_prepare($conexion, "SELECT COUNT(*) AS total FROM relevamientos r" . $where);

    if (!$stmt) {
        error_log("rel_consultar_listado (total): " . mysqli_error($conexion));
        mostrar_error("No se pudo consultar el historial de relevamientos. ¿Se ejecutó el script actualizar_relevamientos.sql?");
    }

    if ($tipos !== "") {
        mysqli_stmt_bind_param($stmt, $tipos, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $fila  = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    $total   = (int)($fila["total"] ?? 0);
    $paginas = max(1, (int)ceil($total / REL_POR_PAGINA));
    $pagina  = min($filtros["pagina"], $paginas);
    $inicio  = ($pagina - 1) * REL_POR_PAGINA;

    /* Página pedida */

    $sql = "SELECT
                r.id_relevamiento,
                r.turno,
                r.fecha,
                r.resultado,
                r.total_computadoras,
                r.revisadas,
                r.con_inconvenientes,
                COALESCE(l.nombre, r.laboratorio_nombre) AS laboratorio,
                u.nombre,
                u.apellido
            FROM relevamientos r
            INNER JOIN usuarios u
                ON u.id_usuario = r.id_usuario
            LEFT JOIN laboratorios l
                ON l.id_laboratorio = r.id_laboratorio"
         . $where .
         " ORDER BY r.fecha DESC, r.id_relevamiento DESC
            LIMIT ? OFFSET ?";

    $stmt = mysqli_prepare($conexion, $sql);

    if (!$stmt) {
        error_log("rel_consultar_listado: " . mysqli_error($conexion));
        mostrar_error("No se pudo consultar el historial de relevamientos.");
    }

    $tipos   .= "ii";
    $params[] = REL_POR_PAGINA;
    $params[] = $inicio;

    mysqli_stmt_bind_param($stmt, $tipos, ...$params);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    return [$resultado, $total, $pagina, $paginas];
}
