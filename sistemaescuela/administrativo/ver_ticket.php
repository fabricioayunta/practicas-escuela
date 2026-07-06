<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_GET["id"];

/* DATOS DEL TICKET */

$sql = "SELECT

tickets.*,

usuarios.nombre,
usuarios.apellido,

laboratorios.nombre AS laboratorio,

computadoras.numero_pc,

ematp.nombre AS nombre_ematp,
ematp.apellido AS apellido_ematp

FROM tickets

INNER JOIN usuarios
ON tickets.id_usuario = usuarios.id_usuario

INNER JOIN computadoras
ON tickets.id_computadora = computadoras.id_computadora

INNER JOIN laboratorios
ON computadoras.id_laboratorio = laboratorios.id_laboratorio

LEFT JOIN usuarios ematp
ON tickets.id_ematp_asignado = ematp.id_usuario

WHERE id_ticket='$id'";

$resultado = mysqli_query($conexion,$sql);

$ticket = mysqli_fetch_assoc($resultado);

/* HISTORIAL */

$sqlHistorial = "SELECT

historialticket.*,

usuarios.nombre,
usuarios.apellido

FROM historialticket

INNER JOIN usuarios
ON historialticket.id_usuario = usuarios.id_usuario

WHERE id_ticket='$id'

ORDER BY fecha DESC";

$historial = mysqli_query($conexion,$sqlHistorial);

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Ver Ticket</title>

</head>

<body>

<h1>Ticket #<?php echo $ticket["id_ticket"]; ?></h1>

<a href="tickets.php">← Volver</a>

<hr>

<h3>Información General</h3>

<p>

<strong>Profesor:</strong>

<?php echo $ticket["nombre"]." ".$ticket["apellido"]; ?>

</p>

<p>

<strong>Laboratorio:</strong>

<?php echo $ticket["laboratorio"]; ?>

</p>

<p>

<strong>Computadora:</strong>

PC <?php echo $ticket["numero_pc"]; ?>

</p>

<p>

<strong>Título:</strong>

<?php echo $ticket["titulo"]; ?>

</p>

<p>

<strong>Descripción:</strong>

<br>

<?php echo nl2br($ticket["descripcion"]); ?>

</p>

<p>

<strong>Estado:</strong>

<?php echo $ticket["estado"]; ?>

</p>

<p>

<strong>Fecha:</strong>

<?php echo date("d/m/Y H:i",strtotime($ticket["fecha_creacion"])); ?>

</p>

<p>

<strong>EMATP asignado:</strong>

<?php

if($ticket["nombre_ematp"]!=""){

echo $ticket["nombre_ematp"]." ".$ticket["apellido_ematp"];

}else{

echo "Sin asignar";

}

?>

</p>

<hr>

<h2>Historial del Ticket</h2>

<table border="1" cellpadding="10">

<tr>

<th>Fecha</th>

<th>Usuario</th>

<th>Estado</th>

<th>Observación</th>

</tr>

<?php while($fila=mysqli_fetch_assoc($historial)){ ?>

<tr>

<td>

<?php echo date("d/m/Y H:i",strtotime($fila["fecha"])); ?>

</td>

<td>

<?php echo $fila["nombre"]." ".$fila["apellido"]; ?>

</td>

<td>

<?php echo $fila["estado"]; ?>

</td>

<td>

<?php echo $fila["observacion"]; ?>

</td>

</tr>

<?php } ?>

</table>

</body>

</html>