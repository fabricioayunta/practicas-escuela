<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id_ticket = $_GET["id"];

// Obtener información del ticket
$sql = "SELECT
            tickets.*,
            usuarios.nombre,
            usuarios.apellido,
            computadoras.numero_pc,
            laboratorios.nombre AS laboratorio
        FROM tickets
        INNER JOIN usuarios
            ON tickets.id_usuario = usuarios.id_usuario
        INNER JOIN computadoras
            ON tickets.id_computadora = computadoras.id_computadora
        INNER JOIN laboratorios
            ON computadoras.id_laboratorio = laboratorios.id_laboratorio
        WHERE tickets.id_ticket = '$id_ticket'";

$resultado = mysqli_query($conexion, $sql);
$ticket = mysqli_fetch_assoc($resultado);

// Obtener historial del ticket
$sqlHistorial = "SELECT
                    historialticket.*,
                    usuarios.nombre,
                    usuarios.apellido
                FROM historialticket
                INNER JOIN usuarios
                    ON historialticket.id_usuario = usuarios.id_usuario
                WHERE historialticket.id_ticket = '$id_ticket'
                ORDER BY historialticket.fecha DESC";

$historial = mysqli_query($conexion, $sqlHistorial);

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestionar Ticket</title>
</head>

<body>

<h1>Gestionar Ticket #<?php echo $ticket["id_ticket"]; ?></h1>

<a href="tickets.php">← Volver a Tickets</a>

<hr>

<h3>Información del Ticket</h3>

<p><strong>Profesor:</strong>
<?php echo $ticket["nombre"] . " " . $ticket["apellido"]; ?>
</p>

<p><strong>Laboratorio:</strong>
<?php echo $ticket["laboratorio"]; ?>
</p>

<p><strong>Computadora:</strong>
PC <?php echo $ticket["numero_pc"]; ?>
</p>

<p><strong>Título:</strong>
<?php echo $ticket["titulo"]; ?>
</p>

<p><strong>Descripción:</strong><br>
<?php echo nl2br($ticket["descripcion"]); ?>
</p>

<p><strong>Estado actual:</strong>
<?php echo $ticket["estado"]; ?>
</p>

<hr>

<h3>Cambiar Estado</h3>

<form action="actualizar_ticket.php" method="POST">

<input
type="hidden"
name="id_ticket"
value="<?php echo $ticket["id_ticket"]; ?>">

<label>Estado</label>

<br>

<select name="estado">

<option value="Abierto"
<?php if($ticket["estado"]=="Abierto") echo "selected"; ?>>
Abierto
</option>

<option value="Pendiente"
<?php if($ticket["estado"]=="Pendiente") echo "selected"; ?>>
Pendiente
</option>

<option value="Cerrado"
<?php if($ticket["estado"]=="Cerrado") echo "selected"; ?>>
Cerrado
</option>

</select>

<br><br>

<label>Observación</label>

<br>

<textarea
name="observacion"
rows="5"
cols="70"
required></textarea>

<br><br>

<button type="submit">

Guardar Cambios

</button>

</form>

<hr>

<h2>Historial del Ticket</h2>

<table border="1" cellpadding="10">

<tr>

<th>Fecha</th>

<th>Estado</th>

<th>Observación</th>

<th>Usuario</th>

</tr>

<?php while($fila = mysqli_fetch_assoc($historial)){ ?>

<tr>

<td>

<?php
echo date("d/m/Y H:i", strtotime($fila["fecha"]));
?>

</td>

<td>

<?php echo $fila["estado"]; ?>

</td>

<td>

<?php echo $fila["observacion"]; ?>

</td>

<td>

<?php
echo $fila["nombre"]." ".$fila["apellido"];
?>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>