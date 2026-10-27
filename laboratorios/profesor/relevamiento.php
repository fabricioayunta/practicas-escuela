<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";
require_once "../includes/relevamientos.php";

/* ---------- Laboratorios ---------- */

$laboratorios = [];
$consulta     = mysqli_query($conexion, "SELECT id_laboratorio, nombre FROM laboratorios ORDER BY nombre");

while ($lab = mysqli_fetch_assoc($consulta)) {
    $laboratorios[(int)$lab["id_laboratorio"]] = $lab;
}

/* ---------- Laboratorio y turno elegidos ---------- */

$idLab = rel_entero($_GET["laboratorio"] ?? "");
$turno = $_GET["turno"] ?? "";
$turno = (is_string($turno) && in_array($turno, turnos_relevamiento(), true)) ? $turno : "";

$laboratorio  = $laboratorios[$idLab] ?? null;
$mostrarLista = ($laboratorio !== null && $turno !== "");

$computadoras     = [];
$ticketsAbiertos  = [];
$relevamientosHoy = [];
$revisables       = 0;
$tokenEnvio       = "";

if ($mostrarLista) {

    /* Todas las computadoras registradas en el laboratorio */

    $stmt = mysqli_prepare(
        $conexion,
        "SELECT id_computadora, numero_pc, estado
         FROM computadoras
         WHERE id_laboratorio = ?
         ORDER BY numero_pc"
    );

    mysqli_stmt_bind_param($stmt, "i", $idLab);
    mysqli_stmt_execute($stmt);
    $resultadoPC = mysqli_stmt_get_result($stmt);

    while ($pc = mysqli_fetch_assoc($resultadoPC)) {
        $computadoras[] = $pc;

        if ($pc["estado"] !== "Baja") {
            $revisables++;
        }
    }

    mysqli_stmt_close($stmt);

    /* Tickets todavía abiertos de esas computadoras (para vincular y no duplicar) */

    $stmt = mysqli_prepare(
        $conexion,
        "SELECT t.id_ticket, t.id_computadora, t.titulo, t.estado, t.fecha_creacion
         FROM tickets t
         INNER JOIN computadoras c
            ON c.id_computadora = t.id_computadora
         WHERE c.id_laboratorio = ?
           AND t.estado <> 'Cerrado'
         ORDER BY t.id_ticket DESC"
    );

    mysqli_stmt_bind_param($stmt, "i", $idLab);
    mysqli_stmt_execute($stmt);
    $resultadoTickets = mysqli_stmt_get_result($stmt);

    while ($t = mysqli_fetch_assoc($resultadoTickets)) {
        $ticketsAbiertos[(int)$t["id_computadora"]][] = $t;
    }

    mysqli_stmt_close($stmt);

    /* Aviso (no bloquea): ¿ya hubo un relevamiento hoy de este laboratorio y turno? */

    $stmt = mysqli_prepare(
        $conexion,
        "SELECT r.fecha, r.resultado, u.nombre, u.apellido
         FROM relevamientos r
         INNER JOIN usuarios u
            ON u.id_usuario = r.id_usuario
         WHERE r.id_laboratorio = ?
           AND r.turno = ?
           AND r.fecha >= CURDATE()
         ORDER BY r.id_relevamiento DESC
         LIMIT 3"
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "is", $idLab, $turno);
        mysqli_stmt_execute($stmt);
        $resultadoHoy = mysqli_stmt_get_result($stmt);

        while ($h = mysqli_fetch_assoc($resultadoHoy)) {
            $relevamientosHoy[] = $h;
        }

        mysqli_stmt_close($stmt);
    }

    /* Código único de este formulario: evita registrar dos veces el mismo envío */

    $tokenEnvio = bin2hex(random_bytes(16));

    if (!isset($_SESSION["relevamiento_tokens"]) || !is_array($_SESSION["relevamiento_tokens"])) {
        $_SESSION["relevamiento_tokens"] = [];
    }

    $_SESSION["relevamiento_tokens"][] = $tokenEnvio;
    $_SESSION["relevamiento_tokens"]   = array_slice($_SESSION["relevamiento_tokens"], -20);
}

$titulo = "Relevamiento de laboratorio";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Relevamiento de laboratorio</h1>
    <p>Antes de comenzar la clase, revise las computadoras y registre cómo las encontró.</p>
</div>

<?php if (!$mostrarLista) { ?>

    <?php if ($idLab > 0 || ($_GET["turno"] ?? "") !== "") { ?>
        <div class="alerta alerta-error" role="alert">
            Elija un laboratorio y un turno válidos para comenzar.
        </div>
    <?php } ?>

    <div class="tarjeta">

        <h2 class="sin-margen-arriba">1. ¿Qué laboratorio va a usar?</h2>

        <?php if (!$laboratorios) { ?>

            <div class="alerta alerta-info">Todavía no hay laboratorios cargados. Avise al administrador.</div>

        <?php } else { ?>

            <form method="GET" class="formulario">

                <div class="campo">
                    <label for="laboratorio">Laboratorio</label>
                    <select id="laboratorio" name="laboratorio" required>
                        <option value="">Seleccione un laboratorio</option>
                        <?php foreach ($laboratorios as $lab) { ?>
                            <option value="<?php echo (int)$lab["id_laboratorio"]; ?>" <?php echo $idLab === (int)$lab["id_laboratorio"] ? "selected" : ""; ?>>
                                <?php echo e($lab["nombre"]); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="turno">Turno</label>
                    <select id="turno" name="turno" required>
                        <?php $sugerido = ($turno !== "") ? $turno : turno_sugerido(); ?>
                        <?php foreach (turnos_relevamiento() as $t) { ?>
                            <option value="<?php echo e($t); ?>" <?php echo $sugerido === $t ? "selected" : ""; ?>>
                                <?php echo e($t); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <small>Ya se sugirió el turno según la hora. Cámbielo si no es el correcto.</small>
                </div>

                <div class="acciones-form">
                    <button type="submit" class="btn-exito">Comenzar relevamiento</button>
                    <a class="btn btn-secundario" href="mis_relevamientos.php">Ver mis relevamientos</a>
                </div>

            </form>

        <?php } ?>

    </div>

<?php } else { ?>

    <div class="tarjeta">

        <h2 class="sin-margen-arriba"><?php echo e($laboratorio["nombre"]); ?> — Turno <?php echo e(mb_strtolower($turno, "UTF-8")); ?></h2>

        <p>
            Responsable: <strong><?php echo e($_SESSION["nombre"] . " " . ($_SESSION["apellido"] ?? "")); ?></strong>
            · <a href="relevamiento.php">Cambiar laboratorio o turno</a>
        </p>

        <?php foreach ($relevamientosHoy as $h) { ?>
            <div class="alerta alerta-info">
                Hoy ya se registró un relevamiento de este laboratorio en este turno
                (<?php echo e(fecha_hora($h["fecha"])); ?>, <?php echo e($h["nombre"] . " " . $h["apellido"]); ?>:
                <?php echo e($h["resultado"] === REL_RESULTADO_OK ? "Todo OK" : "Con inconvenientes"); ?>).
                Si registra otro, ambos se conservan en el historial.
            </div>
            <?php break; ?>
        <?php } ?>

        <ul class="rel-ayuda">
            <li><strong>En condiciones:</strong> la revisó y no encontró inconvenientes.</li>
            <li><strong>Con inconvenientes:</strong> encontró algún problema (deberá describirlo).</li>
            <li><strong>No revisada:</strong> todavía no la revisó. No se puede confirmar con computadoras sin revisar.</li>
        </ul>

    </div>

    <?php if ($revisables === 0) { ?>

        <div class="alerta alerta-error" role="alert">
            Este laboratorio no tiene computadoras en servicio para revisar.
            Si es un error, avise al administrador.
        </div>

        <div class="barra-acciones">
            <a class="btn btn-secundario" href="relevamiento.php">← Elegir otro laboratorio</a>
        </div>

    <?php } else { ?>

        <form action="guardar_relevamiento.php" method="POST" class="rel-formulario"
              data-laboratorio="<?php echo e($laboratorio["nombre"]); ?>"
              data-turno="<?php echo e($turno); ?>">

            <?php echo campo_csrf(); ?>
            <input type="hidden" name="id_laboratorio" value="<?php echo (int)$idLab; ?>">
            <input type="hidden" name="turno" value="<?php echo e($turno); ?>">
            <input type="hidden" name="token_envio" value="<?php echo e($tokenEnvio); ?>">

            <div class="rel-progreso" role="status" aria-live="polite">
                <div class="rel-progreso-texto">
                    <strong id="rel-contador">0 de <?php echo (int)$revisables; ?> computadoras revisadas</strong>
                    <span id="rel-pendientes" class="ayuda"></span>
                </div>
                <progress id="rel-barra" max="<?php echo (int)$revisables; ?>" value="0"></progress>
            </div>

            <?php foreach ($computadoras as $pc) { ?>

                <?php
                $idPc = (int)$pc["id_computadora"];
                $num  = (int)$pc["numero_pc"];
                ?>

                <?php if ($pc["estado"] === "Baja") { ?>

                    <div class="rel-pc rel-pc-baja">
                        <div class="rel-pc-titulo">
                            <strong>PC <?php echo $num; ?></strong>
                            <?php echo etiqueta_estado_pc(REL_ESTADO_BAJA); ?>
                        </div>
                        <p class="ayuda">Esta computadora está dada de baja, no hace falta revisarla.</p>
                    </div>

                <?php } else { ?>

                    <fieldset class="rel-pc rel-pc-pendiente" data-numero="<?php echo $num; ?>">

                        <legend>
                            PC <?php echo $num; ?>
                            <span class="estado rel-pendiente rel-etiqueta" data-etiqueta>
                                <span aria-hidden="true">…</span> No revisada
                            </span>
                        </legend>

                        <div class="rel-opciones">
                            <label class="opcion">
                                <input type="radio" name="estado[<?php echo $idPc; ?>]" value="ok">
                                En condiciones
                            </label>
                            <label class="opcion">
                                <input type="radio" name="estado[<?php echo $idPc; ?>]" value="problema">
                                Con inconvenientes
                            </label>
                            <label class="opcion">
                                <input type="radio" name="estado[<?php echo $idPc; ?>]" value="pendiente" checked>
                                No revisada
                            </label>
                        </div>

                        <div class="rel-detalle" data-detalle hidden>

                            <div class="campo">
                                <label for="obs-<?php echo $idPc; ?>">Describa el inconveniente de la PC <?php echo $num; ?></label>
                                <textarea id="obs-<?php echo $idPc; ?>" name="obs[<?php echo $idPc; ?>]" maxlength="500"
                                          placeholder="Por ejemplo: el monitor no enciende."></textarea>
                            </div>

                            <?php $abiertos = $ticketsAbiertos[$idPc] ?? []; ?>

                            <div class="campo">
                                <span class="etiqueta">Ticket de este inconveniente</span>

                                <?php if ($abiertos) { ?>
                                    <p class="ayuda">Esta computadora ya tiene tickets abiertos. Si es el mismo problema, vincúlelo para no duplicarlo.</p>
                                <?php } ?>

                                <div class="rel-opciones rel-opciones-columna">

                                    <?php foreach ($abiertos as $indice => $t) { ?>
                                        <label class="opcion">
                                            <input type="radio" name="ticket[<?php echo $idPc; ?>]"
                                                   value="<?php echo (int)$t["id_ticket"]; ?>"
                                                   <?php echo $indice === 0 ? "checked" : ""; ?>>
                                            <span>
                                                Vincular al ticket n.º <?php echo (int)$t["id_ticket"]; ?>
                                                (<?php echo e($t["estado"]); ?>) — <?php echo e($t["titulo"]); ?>
                                            </span>
                                        </label>
                                    <?php } ?>

                                    <label class="opcion">
                                        <input type="radio" name="ticket[<?php echo $idPc; ?>]" value="nuevo"
                                               <?php echo $abiertos ? "" : "checked"; ?>>
                                        <span>Crear un ticket nuevo</span>
                                    </label>

                                </div>
                            </div>

                        </div>

                    </fieldset>

                <?php } ?>

            <?php } ?>

            <div class="tarjeta">

                <div class="campo">
                    <label for="observacion_general">Observaciones generales (opcional)</label>
                    <textarea id="observacion_general" name="observacion_general" maxlength="255"
                              placeholder="Cualquier comentario sobre el laboratorio."></textarea>
                </div>

                <p id="rel-resultado-previsto" class="rel-resultado-previsto" role="status" aria-live="polite"></p>

                <div class="acciones-form">
                    <button type="submit" class="btn-exito">Confirmar relevamiento</button>
                    <a class="btn btn-secundario" href="relevamiento.php">Cancelar</a>
                </div>

            </div>

        </form>

        <script src="../js/relevamiento.js?v=1" defer></script>

    <?php } ?>

<?php } ?>

<?php include "../includes/pie.php"; ?>
