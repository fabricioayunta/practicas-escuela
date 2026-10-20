<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 3) {
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
    $filtro      .= " AND (p.persona LIKE ? OR a.nombre LIKE ?)";
    $like         = "%" . $buscar . "%";
    $parametros[] = $like;
    $parametros[] = $like;
    $tipos       .= "ss";
}

/* Primero los pendientes, después los devueltos */
$sql = "SELECT p.*, a.nombre AS articulo, u.nombre, u.apellido
        FROM prestamos_articulos p
        INNER JOIN articulos a
            ON p.id_articulo = a.id_articulo
        INNER JOIN usuarios u
            ON p.id_usuario = u.id_usuario
        $filtro
        ORDER BY FIELD(p.estado, 'Pendiente', 'Devuelto'), p.fecha_prestamo DESC";

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

$titulo = "Préstamos";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Préstamos y devoluciones</h1>
    <p>Artículos prestados. Los pendientes de devolución aparecen primero.</p>
</div>

<?php if ($ok === "creado") { ?>
    <div class="alerta alerta-exito">El préstamo se registró correctamente.</div>
<?php } elseif ($ok === "devuelto") { ?>
    <div class="alerta alerta-exito">La devolución se registró correctamente.</div>
<?php } ?>

<div class="barra-acciones">
    <a class="btn btn-exito" href="crear_prestamo.php">+ Nuevo préstamo</a>
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
            <label for="buscar">Persona o artículo</label>
            <input type="text" id="buscar" name="buscar" placeholder="Nombre de la persona o del artículo"
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
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Prestado a</th>
                <th>Fecha préstamo</th>
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
                    <strong><?php echo e($fila["articulo"]); ?></strong>
                    <?php if (!empty($fila["observaciones"])) { ?>
                        <br><small><?php echo e($fila["observaciones"]); ?></small>
                    <?php } ?>
                </td>
                <td><?php echo e($fila["cantidad"]); ?></td>
                <td><?php echo e($fila["persona"]); ?></td>
                <td><?php echo date("d/m/Y H:i", strtotime($fila["fecha_prestamo"])); ?></td>
                <td><?php echo $fila["fecha_devolucion"] !== null ? date("d/m/Y H:i", strtotime($fila["fecha_devolucion"])) : "—"; ?></td>
                <td>
                    <span class="estado <?php echo clase_prestamo($fila["estado"]); ?>">
                        <?php echo e($fila["estado"]); ?>
                    </span>
                </td>
                <td><?php echo e($fila["nombre"] . " " . $fila["apellido"]); ?></td>
                <td>
                    <?php if ($fila["estado"] === "Pendiente") { ?>
                        <div class="acciones">
                            <a class="btn btn-exito btn-chico"
                               href="devolver_prestamo.php?id=<?php echo e($fila["id_prestamo"]); ?>"
                               onclick="return confirm('¿Registrar la devolución de este artículo?');">Devolver</a>
                        </div>
                    <?php } else { ?>
                        —
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
