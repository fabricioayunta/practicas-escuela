<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

/* Laboratorios */
$laboratorios = mysqli_query($conexion, "SELECT id_laboratorio, nombre FROM laboratorios ORDER BY nombre");

/* Computadoras del laboratorio elegido */
$idLaboratorio = (int)($_GET["laboratorio"] ?? 0);
$computadoras  = null;

if ($idLaboratorio > 0) {

    $stmt = mysqli_prepare(
        $conexion,
        "SELECT id_computadora, numero_pc, estado
         FROM computadoras
         WHERE id_laboratorio = ?
         ORDER BY numero_pc"
    );

    mysqli_stmt_bind_param($stmt, "i", $idLaboratorio);
    mysqli_stmt_execute($stmt);
    $computadoras = mysqli_stmt_get_result($stmt);
}

$titulo = "Computadoras";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Computadoras</h1>
    <p>Elija un laboratorio para ver sus computadoras.</p>
</div>

<div class="barra-acciones">
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tarjeta">

    <form method="GET" class="filtros">

        <div class="campo">
            <label for="laboratorio">Laboratorio</label>
            <select id="laboratorio" name="laboratorio" required>
                <option value="">Seleccione un laboratorio</option>
                <?php while ($lab = mysqli_fetch_assoc($laboratorios)) { ?>
                    <option value="<?php echo e($lab["id_laboratorio"]); ?>"
                        <?php echo $idLaboratorio === (int)$lab["id_laboratorio"] ? "selected" : ""; ?>>
                        <?php echo e($lab["nombre"]); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit">Ver computadoras</button>

    </form>

</div>

<?php if ($computadoras) { ?>

    <div class="tabla-responsive">
        <table>
            <thead>
                <tr>
                    <th>Computadora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

            <?php if (mysqli_num_rows($computadoras) === 0) { ?>
                <tr>
                    <td class="sin-datos" colspan="3">Este laboratorio no tiene computadoras cargadas.</td>
                </tr>
            <?php } ?>

            <?php while ($pc = mysqli_fetch_assoc($computadoras)) { ?>
                <tr>
                    <td><strong>PC <?php echo e($pc["numero_pc"]); ?></strong></td>
                    <td>
                        <?php if ($pc["estado"] === "Alta") { ?>
                            <span class="estado operativa">Operativa</span>
                        <?php } else { ?>
                            <span class="estado fuera-servicio">Fuera de servicio</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a class="btn btn-chico" href="ver_computadora.php?id=<?php echo e($pc["id_computadora"]); ?>">Ver componentes</a>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
    </div>

<?php } ?>

<?php include "../includes/pie.php"; ?>
