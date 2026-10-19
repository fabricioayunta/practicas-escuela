<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$filtro     = " WHERE 1=1 ";
$parametros = [];
$tipos      = "";

$estadoElegido = $_GET["estado"] ?? "";
$buscar        = trim($_GET["buscar"] ?? "");

if (in_array($estadoElegido, ["Abierto", "Pendiente", "Cerrado"], true)) {
    $filtro      .= " AND tickets.estado = ?";
    $parametros[] = $estadoElegido;
    $tipos       .= "s";
}

if ($buscar !== "") {
    $filtro      .= " AND (usuarios.nombre LIKE ? OR usuarios.apellido LIKE ?)";
    $like         = "%" . $buscar . "%";
    $parametros[] = $like;
    $parametros[] = $like;
    $tipos       .= "ss";
}

$sql = "SELECT
            tickets.id_ticket,
            tickets.titulo,
            tickets.estado,
            tickets.fecha_creacion,
            usuarios.nombre,
            usuarios.apellido,
            laboratorios.nombre AS laboratorio,
            computadoras.numero_pc
        FROM tickets
        INNER JOIN usuarios
            ON tickets.id_usuario = usuarios.id_usuario
        LEFT JOIN computadoras
            ON tickets.id_computadora = computadoras.id_computadora
        LEFT JOIN laboratorios
            ON computadoras.id_laboratorio = laboratorios.id_laboratorio
        $filtro
        ORDER BY tickets.fecha_creacion DESC";

$stmt = mysqli_prepare($conexion, $sql);

if ($tipos !== "") {
    mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);
}

mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$titulo = "Tickets";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Tickets</h1>
    <p>Avisos enviados por los profesores.</p>
</div>

<div class="barra-acciones">
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tarjeta">

    <form method="GET" class="filtros">

        <div class="campo">
            <label for="estado">Estado</label>
            <select id="estado" name="estado">
                <option value="">Todos</option>
                <option value="Abierto"   <?php echo $estadoElegido === "Abierto" ? "selected" : ""; ?>>Abiertos</option>
                <option value="Pendiente" <?php echo $estadoElegido === "Pendiente" ? "selected" : ""; ?>>Pendientes</option>
                <option value="Cerrado"   <?php echo $estadoElegido === "Cerrado" ? "selected" : ""; ?>>Cerrados</option>
            </select>
        </div>

        <div class="campo">
            <label for="buscar">Profesor</label>
            <input type="text" id="buscar" name="buscar" placeholder="Nombre o apellido"
                   value="<?php echo e($buscar); ?>">
        </div>

        <button type="submit">Buscar</button>

        <?php if ($estadoElegido !== "" || $buscar !== "") { ?>
            <a class="btn btn-secundario" href="tickets.php">Quitar filtros</a>
        <?php } ?>

    </form>

</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>N.º</th>
                <th>Profesor</th>
                <th>Laboratorio</th>
                <th>PC</th>
                <th>Problema</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if (mysqli_num_rows($resultado) === 0) { ?>
            <tr>
                <td class="sin-datos" colspan="8">No se encontraron tickets.</td>
            </tr>
        <?php } ?>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo e($fila["id_ticket"]); ?></td>
                <td><?php echo e($fila["nombre"] . " " . $fila["apellido"]); ?></td>
                <td><?php echo e($fila["laboratorio"] ?? "—"); ?></td>
                <td><?php echo $fila["numero_pc"] !== null ? "PC " . e($fila["numero_pc"]) : "—"; ?></td>
                <td><?php echo e($fila["titulo"]); ?></td>
                <td>
                    <span class="estado <?php echo clase_estado($fila["estado"]); ?>">
                        <?php echo e($fila["estado"]); ?>
                    </span>
                </td>
                <td><?php echo date("d/m/Y H:i", strtotime($fila["fecha_creacion"])); ?></td>
                <td>
                    <a class="btn btn-chico" href="ver_ticket.php?id=<?php echo e($fila["id_ticket"]); ?>">Ver</a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
