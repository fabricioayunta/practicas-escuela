<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

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
        ORDER BY tickets.fecha_creacion DESC";

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<title>Tickets</title>

<link rel="stylesheet" href="../css/estilos.css">

</head>

<body>

<h1>Panel EMATP</h1>

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<a href="inicio.php">← Volver al Panel</a>

<br><br>

<table>

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

<td>

<?php echo $fila["laboratorio"]; ?>

</td>

<td>

PC <?php echo $fila["numero_pc"]; ?>

</td>

<td>

<?php echo $fila["titulo"]; ?>

</td>

<td>

<?php

if($fila["estado"]=="Abierto"){

echo "<span class='abierto'>Abierto</span>";

}elseif($fila["estado"]=="Pendiente"){

echo "<span class='pendiente'>Pendiente</span>";

}else{

echo "<span class='cerrado'>Cerrado</span>";

}

?>

</td>

<td>

<?php

echo date("d/m/Y H:i",strtotime($fila["fecha_creacion"]));

?>

</td>

<td>

<a href="editar_ticket.php?id=<?php echo $fila["id_ticket"]; ?>">

Gestionar

</a>

</td>

</tr>

<?php } ?>

</table>

</body>

</html>