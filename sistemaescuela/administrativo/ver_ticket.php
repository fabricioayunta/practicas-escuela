<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = (int)($_GET["id"] ?? 0);

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

WHERE id_ticket=?";

$stmt=mysqli_prepare($conexion,$sql); mysqli_stmt_bind_param($stmt,"i",$id); mysqli_stmt_execute($stmt); $resultado=mysqli_stmt_get_result($stmt);
$ticket = mysqli_fetch_assoc($resultado);

/* HISTORIAL */

$sqlHistorial = "SELECT

historialticket.*,

usuarios.nombre,
usuarios.apellido

FROM historialticket

INNER JOIN usuarios
ON historialticket.id_usuario = usuarios.id_usuario

WHERE id_ticket=?

ORDER BY fecha DESC";

$stmt=mysqli_prepare($conexion,$sqlHistorial); mysqli_stmt_bind_param($stmt,"i",$id); mysqli_stmt_execute($stmt); $historial=mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="es">

<head>
<link rel="stylesheet" href="../css/estilos.css">
<meta charset="UTF-8">

<title>Ver Ticket</title>

</head>

<body>
<?php include("../includes/menu_admin.php"); ?>
<h1>Ticket #<?php echo e($ticket["id_ticket"]); ?></h1>

<a href="tickets.php">← Volver</a>

<hr>

<h3>Información General</h3>

<p>

<strong>Profesor:</strong>

<?php echo e($ticket["nombre"])." ".e($ticket["apellido"]); ?>

</p>

<p>

<strong>Laboratorio:</strong>

<?php echo e($ticket["laboratorio"]); ?>

</p>

<p>

<strong>Computadora:</strong>

PC <?php echo e($ticket["numero_pc"]); ?>

</p>

<p>

<strong>Título:</strong>

<?php echo e($ticket["titulo"]); ?>

</p>

<p>

<strong>Descripción:</strong>

<br>

<?php echo nl2br(e($ticket["descripcion"])); ?>

</p>

<p>

<strong>Estado:</strong>

<?php echo e($ticket["estado"]); ?>

</p>

<p>

<strong>Fecha:</strong>

<?php echo date("d/m/Y H:i",strtotime($ticket["fecha_creacion"])); ?>

</p>

<p>

<strong>EMATP asignado:</strong>

<?php

if($ticket["nombre_ematp"]!=""){

echo e($ticket["nombre_ematp"])." ".e($ticket["apellido_ematp"]);

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

<?php echo e($fila["nombre"])." ".e($fila["apellido"]); ?>

</td>

<td>

<?php echo e($fila["estado"]); ?>

</td>

<td>

<?php echo e($fila["observacion"]); ?>

</td>

</tr>

<?php } ?>

</table>

</body>

</html>