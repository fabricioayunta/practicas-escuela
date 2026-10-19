<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$valorLaboratorio = array_key_exists("laboratorio", $_GET)
    ? $_GET["laboratorio"]
    : "1";
if (!is_string($valorLaboratorio)) {
    mostrar_error("El laboratorio seleccionado no es válido.", "computadoras.php");
}
if ($valorLaboratorio === "") {
    $valorLaboratorio = "1";
}

$idLaboratorio = filter_var($valorLaboratorio, FILTER_VALIDATE_INT);
if ($idLaboratorio === false || $idLaboratorio < 1) {
    mostrar_error("El laboratorio seleccionado no es válido.", "computadoras.php");
}

$consulta_laboratorios = mysqli_query(
    $conexion,
    "SELECT id_laboratorio, nombre
     FROM laboratorios
     ORDER BY nombre"
);
if (!$consulta_laboratorios) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudieron cargar los laboratorios.");
}

$sqlComputadoras = "SELECT c.id_computadora, c.numero_pc, c.estado, l.nombre AS laboratorio
                    FROM computadoras c
                    INNER JOIN laboratorios l
                        ON c.id_laboratorio = l.id_laboratorio";

if ($idLaboratorio > 0) {
    $stmt = mysqli_prepare(
        $conexion,
        $sqlComputadoras . " WHERE c.id_laboratorio = ? ORDER BY l.nombre, c.numero_pc"
    );
    if (!$stmt) {
        error_log(mysqli_error($conexion));
        mostrar_error("No se pudieron cargar las computadoras.");
    }

    mysqli_stmt_bind_param($stmt, "i", $idLaboratorio);
    if (!mysqli_stmt_execute($stmt)) {
        error_log(mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        mostrar_error("No se pudieron cargar las computadoras.");
    }

    $resultado = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
} else {
    $resultado = mysqli_query(
        $conexion,
        $sqlComputadoras . " ORDER BY l.nombre, c.numero_pc"
    );
}

if (!$resultado) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudieron cargar las computadoras.");
}

$ok = $_GET["ok"] ?? "";

$titulo = "Computadoras";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Computadoras</h1>
    <p>Todas las computadoras de la escuela.</p>
</div>

<?php if ($ok === "creada") { ?>
    <div class="alerta alerta-exito">La computadora se agregó correctamente.</div>
<?php } elseif ($ok === "editada") { ?>
    <div class="alerta alerta-exito">La computadora se actualizó correctamente.</div>
<?php } elseif ($ok === "eliminada") { ?>
    <div class="alerta alerta-exito">La computadora se eliminó correctamente.</div>
<?php } ?>

<div class="barra-acciones">
    <a class="btn btn-exito" href="crear_computadora.php">+ Nueva computadora</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tarjeta">
    <form method="GET" class="filtros">
        <div class="campo">
            <label for="laboratorio">Seleccionar laboratorio:</label>
            <select name="laboratorio" id="laboratorio" onchange="this.form.submit()">
                <?php while ($lab = mysqli_fetch_assoc($consulta_laboratorios)) { ?>
                    <option value="<?php echo e($lab["id_laboratorio"]); ?>"
                        <?php echo $idLaboratorio === (int)$lab["id_laboratorio"] ? "selected" : ""; ?>>
                        <?php echo e($lab["nombre"]); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </form>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Laboratorio</th>
                <th>Computadora</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($resultado) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="4">No hay computadoras para el laboratorio seleccionado.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo e($fila["laboratorio"]); ?></td>
                <td><strong>PC <?php echo e($fila["numero_pc"]); ?></strong></td>
                <td>
                    <?php if ($fila["estado"] === "Alta") { ?>
                        <span class="estado operativa">Operativa</span>
                    <?php } else { ?>
                        <span class="estado fuera-servicio">Fuera de servicio</span>
                    <?php } ?>
                </td>
                <td>
                    <div class="acciones">
                        <a class="btn btn-chico" href="editar_computadora.php?id=<?php echo e($fila["id_computadora"]); ?>">Editar</a>
                        <a class="btn btn-peligro btn-chico"
                           href="eliminar_computadora.php?id=<?php echo e($fila["id_computadora"]); ?>"
                           onclick="return confirm('¿Seguro que desea eliminar esta computadora?');">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
