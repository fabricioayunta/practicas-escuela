<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$id = (int)($_GET["id"] ?? 0);

/* Datos de la computadora */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT
        computadoras.*,
        laboratorios.nombre AS laboratorio
     FROM computadoras
     INNER JOIN laboratorios
        ON computadoras.id_laboratorio = laboratorios.id_laboratorio
     WHERE computadoras.id_computadora = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$pc = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$pc) {
    mostrar_error("No se encontró la computadora.", "computadoras.php");
}

/* Componentes */

$stmt = mysqli_prepare($conexion, "SELECT * FROM componentes WHERE id_computadora = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$componentes = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: [];
mysqli_stmt_close($stmt);

/* Historial de modificaciones */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT
        historial_computadoras.*,
        usuarios.nombre,
        usuarios.apellido
     FROM historial_computadoras
     INNER JOIN usuarios
        ON historial_computadoras.id_usuario = usuarios.id_usuario
     WHERE historial_computadoras.id_computadora = ?
     ORDER BY historial_computadoras.fecha DESC, historial_computadoras.id_historial DESC"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$historial = mysqli_stmt_get_result($stmt);

$filasComponentes = [
    "Mother"        => "mother",
    "Procesador"    => "procesador",
    "Memoria RAM"   => "memoria_ram",
    "Disco"         => "disco",
    "Monitor"       => "monitor",
    "Teclado"       => "teclado",
    "Mouse"         => "mouse",
    "Observaciones" => "observaciones",
];

$operativa = ($pc["estado"] === "Alta");

$titulo = "Computadora";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>PC <?php echo e($pc["numero_pc"]); ?> · <?php echo e($pc["laboratorio"]); ?></h1>
    <p>
        <?php if ($operativa) { ?>
            <span class="estado operativa">Operativa</span>
        <?php } else { ?>
            <span class="estado fuera-servicio">Fuera de servicio</span>
        <?php } ?>
    </p>
</div>

<div class="barra-acciones">
    <a class="btn btn-secundario" href="computadoras.php?laboratorio=<?php echo e($pc["id_laboratorio"]); ?>">← Volver a las computadoras</a>
    <a class="btn" href="editar_componentes.php?id=<?php echo (int)$id; ?>">Editar componentes</a>
</div>

<div class="tarjeta">

    <h2 style="margin-top:0;">Componentes</h2>

    <div class="tabla-responsive" style="box-shadow:none;">
        <table class="ficha">
            <?php foreach ($filasComponentes as $nombre => $campo) { ?>
                <tr>
                    <td><?php echo e($nombre); ?></td>
                    <td>
                        <?php
                        $valor = trim((string)($componentes[$campo] ?? ""));
                        echo $valor === "" ? "<span class='ayuda'>Sin datos</span>" : nl2br(e($valor));
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

</div>

<div class="tarjeta">

    <h2 style="margin-top:0;">Estado de la computadora</h2>

    <?php if ($operativa) { ?>

        <p>Si la computadora ya no puede usarse, indique el motivo y désela de baja.</p>

        <form class="formulario" action="cambiar_estado.php" method="POST"
              onsubmit="return confirm('¿Seguro que desea dar de baja esta computadora?');">

            <input type="hidden" name="id_computadora" value="<?php echo (int)$id; ?>">
            <input type="hidden" name="nuevo_estado" value="Baja">

            <div class="campo">
                <label for="motivo">Motivo de la baja</label>
                <textarea id="motivo" name="motivo" required placeholder="Explique por qué se da de baja..."></textarea>
            </div>

            <button type="submit" class="btn-peligro">Dar de baja</button>

        </form>

    <?php } else { ?>

        <p>Esta computadora está fuera de servicio. Cuando vuelva a funcionar, puede darla de alta.</p>

        <form class="formulario" action="cambiar_estado.php" method="POST">

            <input type="hidden" name="id_computadora" value="<?php echo (int)$id; ?>">
            <input type="hidden" name="nuevo_estado" value="Alta">

            <button type="submit" class="btn-exito">Dar de alta</button>

        </form>

    <?php } ?>

</div>

<h2>Historial de la computadora</h2>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>EMATP</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($historial) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="3">Todavía no hay movimientos.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($historial)) { ?>
            <tr>
                <td><?php echo date("d/m/Y H:i", strtotime($fila["fecha"])); ?></td>
                <td><?php echo e($fila["nombre"] . " " . $fila["apellido"]); ?></td>
                <td><?php echo nl2br(e($fila["accion"])); ?></td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
