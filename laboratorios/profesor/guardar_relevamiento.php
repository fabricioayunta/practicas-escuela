<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";
require_once "../includes/relevamientos.php";

if (($_SERVER["REQUEST_METHOD"] ?? "") !== "POST") {
    header("Location: relevamiento.php");
    exit();
}

verificar_csrf();

/*
   El responsable del relevamiento es SIEMPRE quien inició sesión.
   Nunca se toma un usuario, una fecha ni un resultado del formulario.
*/
$id_usuario = (int)$_SESSION["id_usuario"];


/* Revierte todo y muestra un mensaje claro */
function rel_fallar($conexion, $mensaje, $detalleTecnico = "")
{
    mysqli_rollback($conexion);

    if ($detalleTecnico !== "") {
        error_log("guardar_relevamiento: " . $detalleTecnico);
    }

    mostrar_error($mensaje);
}


/* =========================
   DATOS RECIBIDOS
========================= */

$id_laboratorio = rel_entero($_POST["id_laboratorio"] ?? "");
$turno          = $_POST["turno"] ?? "";
$token          = $_POST["token_envio"] ?? "";

$observacionGeneral = $_POST["observacion_general"] ?? "";
$observacionGeneral = is_string($observacionGeneral) ? trim($observacionGeneral) : "";

$estados = (isset($_POST["estado"]) && is_array($_POST["estado"])) ? $_POST["estado"] : [];
$obs     = (isset($_POST["obs"])    && is_array($_POST["obs"]))    ? $_POST["obs"]    : [];
$tickets = (isset($_POST["ticket"]) && is_array($_POST["ticket"])) ? $_POST["ticket"] : [];

if ($id_laboratorio <= 0 || !is_string($turno) || !in_array($turno, turnos_relevamiento(), true)) {
    mostrar_error("Elija un laboratorio y un turno válidos.", "relevamiento.php");
}

if (!is_string($token) || !preg_match('/^[a-f0-9]{32}$/', $token)) {
    mostrar_error("El formulario no es válido. Vuelva a abrir el relevamiento.", "relevamiento.php");
}

if (mb_strlen($observacionGeneral) > 255) {
    mostrar_error("Las observaciones generales son demasiado largas (máximo 255 caracteres).");
}


/* =========================
   ENVÍO REPETIDO
   Si este mismo formulario ya se guardó (doble clic, recarga, botón "atrás"),
   no se crea otro registro: se muestra el que ya existe.
========================= */

$yaGuardado = rel_buscar_por_token($conexion, $token, $id_usuario);

if ($yaGuardado > 0) {
    header("Location: ver_relevamiento.php?id=" . $yaGuardado . "&ok=repetido");
    exit();
}

$tokensEmitidos = (isset($_SESSION["relevamiento_tokens"]) && is_array($_SESSION["relevamiento_tokens"]))
    ? $_SESSION["relevamiento_tokens"]
    : [];

if (!in_array($token, $tokensEmitidos, true)) {
    mostrar_error("El formulario venció o ya fue enviado. Vuelva a abrir el relevamiento.", "relevamiento.php");
}


/* =========================
   LABORATORIO Y COMPUTADORAS (se leen de la base, no del formulario)
========================= */

$stmt = mysqli_prepare($conexion, "SELECT id_laboratorio, nombre FROM laboratorios WHERE id_laboratorio = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_laboratorio);
mysqli_stmt_execute($stmt);
$laboratorio = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$laboratorio) {
    mostrar_error("El laboratorio seleccionado no existe.", "relevamiento.php");
}

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_computadora, numero_pc, estado
     FROM computadoras
     WHERE id_laboratorio = ?
     ORDER BY numero_pc"
);
mysqli_stmt_bind_param($stmt, "i", $id_laboratorio);
mysqli_stmt_execute($stmt);
$resultadoPC = mysqli_stmt_get_result($stmt);

$pcs = [];

while ($pc = mysqli_fetch_assoc($resultadoPC)) {
    $pcs[(int)$pc["id_computadora"]] = $pc;
}

mysqli_stmt_close($stmt);

if (!$pcs) {
    mostrar_error("Este laboratorio no tiene computadoras registradas.", "relevamiento.php");
}

/*
   Seguridad: todo lo que llegue del formulario debe pertenecer a ESTE laboratorio.
   Si alguien modifica los datos para incluir computadoras de otro laboratorio, se rechaza.
*/
foreach ([$estados, $obs, $tickets] as $grupo) {
    foreach (array_keys($grupo) as $clave) {
        if (!isset($pcs[$clave])) {
            mostrar_error("Los datos enviados no corresponden a este laboratorio. Vuelva a abrir el relevamiento.", "relevamiento.php");
        }
    }
}


/* =========================
   VALIDAR CADA COMPUTADORA
========================= */

$detalle    = [];   // filas a guardar
$pendientes = [];   // computadoras sin revisar
$errores    = [];
$revisables = 0;
$problemas  = 0;

foreach ($pcs as $idPc => $pc) {

    $num = (int)$pc["numero_pc"];

    /* Computadoras dadas de baja: no se revisan, pero quedan registradas */
    if ($pc["estado"] === "Baja") {
        $detalle[] = [
            "id_pc"  => $idPc,
            "num"    => $num,
            "estado" => REL_ESTADO_BAJA,
            "obs"    => null,
            "ticket" => null,
        ];
        continue;
    }

    $revisables++;

    $valor = $estados[$idPc] ?? "";
    $valor = is_string($valor) ? $valor : "";

    if ($valor === "ok") {

        $detalle[] = [
            "id_pc"  => $idPc,
            "num"    => $num,
            "estado" => REL_ESTADO_OK,
            "obs"    => null,
            "ticket" => null,
        ];

    } elseif ($valor === "problema") {

        $problemas++;

        $texto = $obs[$idPc] ?? "";
        $texto = is_string($texto) ? trim($texto) : "";

        if ($texto === "") {
            $errores[] = "PC " . $num . ": describa el inconveniente que encontró.";
        } elseif (mb_strlen($texto) > 500) {
            $errores[] = "PC " . $num . ": la descripción es demasiado larga (máximo 500 caracteres).";
        }

        $eleccion = $tickets[$idPc] ?? "";
        $eleccion = is_string($eleccion) ? $eleccion : "";

        if ($eleccion !== "nuevo" && rel_entero($eleccion) <= 0) {
            $errores[] = "PC " . $num . ": elija si crea un ticket nuevo o vincula uno existente.";
        }

        $detalle[] = [
            "id_pc"  => $idPc,
            "num"    => $num,
            "estado" => REL_ESTADO_PROBLEMA,
            "obs"    => $texto,
            "ticket" => $eleccion,       // "nuevo" o id de un ticket existente
        ];

    } else {

        $pendientes[] = "PC " . $num;
    }
}

if ($revisables === 0) {
    mostrar_error("Este laboratorio no tiene computadoras en servicio para revisar.", "relevamiento.php");
}

/* REGLA OBLIGATORIA: no se confirma nada si queda alguna computadora sin revisar */
if ($pendientes) {
    mostrar_error(
        "No se puede confirmar el relevamiento: faltan revisar " . count($pendientes) .
        " computadora" . (count($pendientes) === 1 ? "" : "s") . " (" . implode(", ", $pendientes) . "). " .
        "Marque cada una como «En condiciones» o «Con inconvenientes»."
    );
}

if ($errores) {
    mostrar_error(implode(" ", $errores));
}


/* El resultado lo calcula SIEMPRE el servidor */
$resultado = ($problemas === 0) ? REL_RESULTADO_OK : REL_RESULTADO_PROBLEMA;
$total     = count($pcs);


/* =========================
   GUARDAR (todo o nada)
========================= */

mysqli_begin_transaction($conexion);

$nombreLab = (string)$laboratorio["nombre"];
$obsGen    = ($observacionGeneral === "") ? null : $observacionGeneral;

$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO relevamientos
        (id_laboratorio, laboratorio_nombre, turno, id_usuario, resultado,
         total_computadoras, revisadas, con_inconvenientes, observaciones, token_envio)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    rel_fallar($conexion, "No se pudo guardar el relevamiento. ¿Se ejecutó el script actualizar_relevamientos.sql?", mysqli_error($conexion));
}

mysqli_stmt_bind_param(
    $stmt,
    "issisiiiss",
    $id_laboratorio,
    $nombreLab,
    $turno,
    $id_usuario,
    $resultado,
    $total,
    $revisables,
    $problemas,
    $obsGen,
    $token
);

if (!mysqli_stmt_execute($stmt)) {

    $codigo  = mysqli_stmt_errno($stmt);
    $mensaje = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);

    /* Otro envío idéntico se guardó justo antes: se muestra ese */
    if ($codigo == 1062) {
        mysqli_rollback($conexion);
        $existente = rel_buscar_por_token($conexion, $token, $id_usuario);

        if ($existente > 0) {
            header("Location: ver_relevamiento.php?id=" . $existente . "&ok=repetido");
            exit();
        }
    }

    rel_fallar($conexion, "No se pudo guardar el relevamiento. Intente nuevamente.", $mensaje);
}

$id_relevamiento = (int)mysqli_insert_id($conexion);
mysqli_stmt_close($stmt);


/* Sentencias reutilizadas para cada computadora */

$stmtBuscarTicket = mysqli_prepare(
    $conexion,
    "SELECT id_ticket, estado
     FROM tickets
     WHERE id_ticket = ? AND id_computadora = ? AND estado <> 'Cerrado'
     LIMIT 1"
);

$stmtNuevoTicket = mysqli_prepare(
    $conexion,
    "INSERT INTO tickets (id_usuario, id_computadora, titulo, descripcion) VALUES (?, ?, ?, ?)"
);

$stmtHistorial = mysqli_prepare(
    $conexion,
    "INSERT INTO historialticket (id_ticket, estado, fecha, observacion, id_usuario)
     VALUES (?, ?, NOW(), ?, ?)"
);

$stmtDetalle = mysqli_prepare(
    $conexion,
    "INSERT INTO relevamiento_detalle
        (id_relevamiento, id_computadora, numero_pc, estado, observacion, id_ticket, ticket_nuevo)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmtBuscarTicket || !$stmtNuevoTicket || !$stmtHistorial || !$stmtDetalle) {
    rel_fallar($conexion, "No se pudo guardar el relevamiento. Intente nuevamente.", mysqli_error($conexion));
}

foreach ($detalle as $fila) {

    $idPc        = (int)$fila["id_pc"];
    $num         = (int)$fila["num"];
    $estadoPc    = $fila["estado"];
    $observacion = $fila["obs"];
    $idTicket    = null;
    $ticketNuevo = 0;

    if ($estadoPc === REL_ESTADO_PROBLEMA) {

        if ($fila["ticket"] === "nuevo") {

            /* Ticket nuevo, a nombre del profesor que hace el relevamiento */

            $tituloTicket = "Relevamiento: inconveniente en PC " . $num;
            $descripcion  = $observacion .
                "\n\n(Detectado en el relevamiento n.º " . $id_relevamiento . " del laboratorio " .
                $nombreLab . ", turno " . mb_strtolower($turno, "UTF-8") . ".)";

            mysqli_stmt_bind_param($stmtNuevoTicket, "iiss", $id_usuario, $idPc, $tituloTicket, $descripcion);

            if (!mysqli_stmt_execute($stmtNuevoTicket)) {
                rel_fallar($conexion, "No se pudo crear el ticket de la PC " . $num . ".", mysqli_stmt_error($stmtNuevoTicket));
            }

            $idTicket    = (int)mysqli_insert_id($conexion);
            $ticketNuevo = 1;

            $estadoHist = "Abierto";
            $textoHist  = "Ticket creado desde el relevamiento n.º " . $id_relevamiento . ".";

            mysqli_stmt_bind_param($stmtHistorial, "issi", $idTicket, $estadoHist, $textoHist, $id_usuario);

            if (!mysqli_stmt_execute($stmtHistorial)) {
                rel_fallar($conexion, "No se pudo guardar el historial del ticket de la PC " . $num . ".", mysqli_stmt_error($stmtHistorial));
            }

        } else {

            /* Ticket existente: debe ser de ESTA computadora y seguir abierto */

            $idElegido = rel_entero($fila["ticket"]);

            mysqli_stmt_bind_param($stmtBuscarTicket, "ii", $idElegido, $idPc);
            mysqli_stmt_execute($stmtBuscarTicket);
            $ticketExistente = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtBuscarTicket));

            if (!$ticketExistente) {
                rel_fallar(
                    $conexion,
                    "PC " . $num . ": el ticket elegido ya no está disponible (puede haberse cerrado). Vuelva a abrir el relevamiento."
                );
            }

            $idTicket = (int)$ticketExistente["id_ticket"];

            /* Queda constancia en el ticket de que el problema se informó de nuevo */

            $estadoHist = $ticketExistente["estado"];
            $textoHist  = "Vinculado al relevamiento n.º " . $id_relevamiento . " (" . $nombreLab . ", turno " .
                          mb_strtolower($turno, "UTF-8") . "): " . $observacion;

            if (mb_strlen($textoHist) > 255) {
                $textoHist = mb_substr($textoHist, 0, 252) . "...";
            }

            mysqli_stmt_bind_param($stmtHistorial, "issi", $idTicket, $estadoHist, $textoHist, $id_usuario);

            if (!mysqli_stmt_execute($stmtHistorial)) {
                rel_fallar($conexion, "No se pudo guardar el historial del ticket de la PC " . $num . ".", mysqli_stmt_error($stmtHistorial));
            }
        }
    }

    mysqli_stmt_bind_param(
        $stmtDetalle,
        "iiissii",
        $id_relevamiento,
        $idPc,
        $num,
        $estadoPc,
        $observacion,
        $idTicket,
        $ticketNuevo
    );

    if (!mysqli_stmt_execute($stmtDetalle)) {
        rel_fallar($conexion, "No se pudo guardar el detalle de la PC " . $num . ".", mysqli_stmt_error($stmtDetalle));
    }
}

mysqli_stmt_close($stmtBuscarTicket);
mysqli_stmt_close($stmtNuevoTicket);
mysqli_stmt_close($stmtHistorial);
mysqli_stmt_close($stmtDetalle);

mysqli_commit($conexion);

/* El código de envío ya se usó: se descarta */
$_SESSION["relevamiento_tokens"] = array_values(array_diff($tokensEmitidos, [$token]));

header("Location: ver_relevamiento.php?id=" . $id_relevamiento . "&ok=creado");
exit();
