<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_SESSION["id_usuario"];

$sql = "SELECT * FROM tickets WHERE id_usuario = $id";
$resultado = mysqli_query($conexion, $sql);
?>

<h1>Mis Tickets</h1>

<a href="crear_ticket.php">+ Crear Ticket</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Descripción</th>
        <th>Estado</th>
        <th>Fecha</th>
    </tr>

    <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
    <tr>
        <td><?php echo $fila["id_ticket"]; ?></td>
        <td><?php echo $fila["titulo"]; ?></td>
        <td><?php echo $fila["descripcion"]; ?></td>
        <td><?php echo $fila["estado"]; ?></td>
        <td><?php echo $fila["fecha"]; ?></td>
    </tr>
    <?php } ?>
</table>