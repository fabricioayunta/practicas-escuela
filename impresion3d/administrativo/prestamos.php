<?php
session_start();

/* Puente de compatibilidad de sesión para evitar redirecciones al inicio */
if (!isset($_SESSION["pi_id_usuario"]) && isset($_SESSION["id_usuario"])) {
    $_SESSION["pi_id_usuario"] = $_SESSION["id_usuario"];
}
if (!isset($_SESSION["pi_id_rol"]) && isset($_SESSION["rol"])) {
    if ($_SESSION["rol"] == 'administrativo' || $_SESSION["rol"] == 1) {
        $_SESSION["pi_id_rol"] = 1;
    }
}

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$filtro     = " WHERE 1=1 ";
$parametros = [];
$tipos      = "";

$estadoElegido = $_GET["estado"] ?? "";
$buscar        = trim($_GET["buscar"] ?? "");

if (in_array($estadoElegido, ["Pendiente", "Devuelto"], true)) {
    $filtro      .= " AND p.estado = ?";
    $parametros[] = $estadoElegido;
    $tipos       .= "s";
}

if ($buscar !== "") {
    $filtro      .= " AND (p.persona LIKE ? OR p.insumo LIKE ?)";
    $like         = "%" . $buscar . "%";
    $parametros[] = $like;
    $parametros[] = $like;
    $tipos       .= "ss";
}

/* Primero los pendientes, después los devueltos */
$sql = "SELECT p.*, u.nombre, u.apellido
        FROM prestamos_insumos p
        INNER JOIN usuarios u
            ON p.id_usuario = u.id_usuario
        $filtro
        ORDER BY FIELD(p.estado, 'Pendiente', 'Devuelto'), p.fecha_entrega DESC";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudieron cargar los préstamos.", "inicio.php");
}

if ($tipos !== "") {
    mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$ok = $_GET["ok"] ?? "";

$titulo = "Préstamos de insumos";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Préstamos de insumos</h1>
    <p>Insumos entregados. Los pendientes de devolución aparecen primero.</p>
</div>

<?php if ($ok === "creado") { ?>
    <div class="alerta alerta-exito">La entrega se registró correctamente.</div>
<?php } elseif ($ok === "devuelto") { ?>
    <div class="alerta alerta-exito">La devolución se registró correctamente.</div>
<?php } elseif ($ok === "eliminado") { ?>
    <div class="alerta alerta-exito">El préstamo se eliminó correctamente.</div>
<?php } ?>

<div class="barra-acciones">
    <a class="btn btn-exito" href="crear_prestamo.php">+ Nueva entrega</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tarjeta">

    <form method="GET" class="filtros">

        <div class="campo">
            <label for="estado">Estado</label>
            <select id="estado" name="estado">
                <option value="">Todos</option>
                <option value="Pendiente" <?php echo $estadoElegido === "Pendiente" ? "selected" : ""; ?>>Pendientes</option>
                <option value="Devuelto"  <?php echo $estadoElegido === "Devuelto" ? "selected" : ""; ?>>Devueltos</option>
            </select>
        </div>

        <div class="campo">
            <label for="buscar">Persona o insumo</label>
            <input type="text" id="buscar" name="buscar" placeholder="Nombre de la persona o del insumo"
                   value="<?php echo e($buscar); ?>">
        </div>

        <button type="submit">Buscar</button>

        <?php if ($estadoElegido !== "" || $buscar !== "") { ?>
            <a class="btn btn-secundario" href="prestamos.php">Quitar filtros</a>
        <?php } ?>

    </form>

</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>N.º</th>
                <th>Insumo</th>
                <th>Cantidad</th>
                <th>Entregado a</th>
                <th>Fecha entrega</th>
                <th>Fecha devolución</th>
                <th>Estado</th>
                <th>Registró</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($resultado) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="9">No se encontraron préstamos.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo e($fila["id_prestamo"]); ?></td>
                <td>
                    <strong><?php echo e($fila["insumo"]); ?></strong>
                    <?php if (!empty($fila["observaciones"])) { ?>
                        <br><small><?php echo e($fila["observaciones"]); ?></small>
                    <?php } ?>
                </td>
                <td><?php echo e($fila["cantidad"]); ?></td>
                <td><?php echo e($fila["persona"]); ?></td>
               <td><?php echo date("d/m/Y H:i", strtotime($fila["fecha_entrega"])); ?></td>
                
                <!-- Muestra siempre la fecha de devolución guardada (estimada o real) -->
                <td>
                    <?php 
                    if (!empty($fila["fecha_devolucion"])) {
                        echo date("d/m/Y H:i", strtotime($fila["fecha_devolucion"]));
                    } else {
                        echo "—";
                    }
                    ?>
                </td>

                <td>
                    <span class="estado <?php echo clase_prestamo($fila["estado"]); ?>">
                        <?php echo e($fila["estado"]); ?>
                    </span>
                </td>
                <td><?php echo e($fila["nombre"] . " " . $fila["apellido"]); ?></td>
                <td>
                    <div class="acciones">
                        <?php if ($fila["estado"] === "Pendiente") { ?>
                            <a class="btn btn-exito btn-chico"
                               href="devolver_prestamo.php?id=<?php echo e($fila["id_prestamo"]); ?>"
                               onclick="return confirm('¿Registrar la devolución de este insumo?');">Devolver</a>
                        <?php } ?>

                        <a class="btn btn-peligro btn-chico"
                           href="eliminar_prestamo.php?id=<?php echo e($fila["id_prestamo"]); ?>"
                           onclick="return confirm('¿Seguro que desea eliminar este préstamo?');">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>