<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

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

INNER JOIN computadoras
ON tickets.id_computadora = computadoras.id_computadora

INNER JOIN laboratorios
ON computadoras.id_laboratorio = laboratorios.id_laboratorio

ORDER BY tickets.fecha_creacion DESC";
$filtro = " WHERE 1=1 ";

if(isset($_GET["estado"]) && $_GET["estado"]!=""){

    $estado = $_GET["estado"];

    $filtro .= " AND tickets.estado='$estado'";

}

if(isset($_GET["buscar"]) && $_GET["buscar"]!=""){

    $buscar = $_GET["buscar"];

    $filtro .= " AND (
        usuarios.nombre LIKE '%$buscar%'
        OR usuarios.apellido LIKE '%$buscar%'
    )";

}

if(isset($_GET["estado"]) && $_GET["estado"]!=""){

    $estado = $_GET["estado"];

    $filtro = " WHERE tickets.estado='$estado'";

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

INNER JOIN computadoras
ON tickets.id_computadora = computadoras.id_computadora

INNER JOIN laboratorios
ON computadoras.id_laboratorio = laboratorios.id_laboratorio

$filtro

ORDER BY tickets.fecha_creacion DESC";

$resultado = mysqli_query($conexion,$sql);
$resultado = mysqli_query($conexion,$sql);
?>

<h1>Todos los Tickets</h1>

<a href="inicio.php">← Volver</a>

<br><br>
<hr>

<form method="GET">

<label>Filtrar por estado:</label>

<select name="estado">

<option value="">Todos</option>

<option value="Abierto">Abiertos</option>

<option value="Pendiente">Pendientes</option>

<option value="Cerrado">Cerrados</option>

</select>
<br><br>

<label>Profesor</label>

<input
type="text"
name="buscar"
placeholder="Nombre o apellido">
<button type="submit">
Buscar
</button>

</form>

<br>
<table border="1" cellpadding="10">

<tr>

<th>ID</th>

<th>Profesor</th>

<th>Laboratorio</th>

<th>PC</th>

<th>Título</th>

<th>Estado</th>

<th>Fecha</th>

<th>Acciones</th>

</tr>

<?php while($fila=mysqli_fetch_assoc($resultado)){ ?>

<tr>

<td><?php echo $fila["id_ticket"]; ?></td>

<td>
<?php
echo $fila["nombre"]." ".$fila["apellido"];
?>
</td>

<td><?php echo $fila["laboratorio"]; ?></td>

<td>PC <?php echo $fila["numero_pc"]; ?></td>

<td><?php echo $fila["titulo"]; ?></td>

<td><?php echo $fila["estado"]; ?></td>

<td>
<?php echo date("d/m/Y H:i",strtotime($fila["fecha_creacion"])); ?>
</td>

<td>

<a href="ver_ticket.php?id=<?php echo $fila["id_ticket"]; ?>">
Ver
</a>

</td>

</tr>

<?php } ?>

</table>