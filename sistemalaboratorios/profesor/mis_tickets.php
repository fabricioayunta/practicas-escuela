<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_SESSION["id_usuario"];

$sql = "SELECT
            tickets.*,
            computadoras.numero_pc,
            laboratorios.nombre AS laboratorio
        FROM tickets
        INNER JOIN computadoras
            ON tickets.id_computadora = computadoras.id_computadora
        INNER JOIN laboratorios
            ON computadoras.id_laboratorio = laboratorios.id_laboratorio
        WHERE tickets.id_usuario = $id
        ORDER BY tickets.fecha_creacion DESC";

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../css/estilos.css">
    <meta charset="UTF-8">
    <title>Mis Tickets</title>
</head>
<body>
<?php include("../includes/menu_profesor.php"); ?>
<h1>Mis Tickets</h1>

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<a href="crear_ticket.php">+ Crear Ticket</a>
<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Laboratorio</th>
        <th>Computadora</th>
        <th>Título</th>
        <th>Descripción</th>
        <th>estado</th>
        <th>Fecha</th>
        <th>accion</th>
    </tr>
    <td>

<?php if (!empty($fila["foto"])) { ?>

    <a href="../uploads/tickets/<?php echo htmlspecialchars($fila["foto"]); ?>" target="_blank">
        Ver foto
    </a>


<?php } ?>

</td>

<?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>

<tr>

    <td>
        <?php echo $fila["id_ticket"]; ?>
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
        <?php echo $fila["descripcion"]; ?>
    </td>

    <td>
        <?php echo $fila["estado"]; ?>
    </td>

    <td>
        <?php echo date("d/m/Y H:i", strtotime($fila["fecha_creacion"])); ?>
    </td>

    <td>

        <?php if ($fila["estado"] == "Abierto") { ?>

            <a href="editar_ticket.php?id=<?php echo $fila["id_ticket"]; ?>">
                Editar
            </a>

        <?php } else { ?>

            -

        <?php } ?>

    </td>

</tr>

<?php } ?>

</table>

<br>

<a href="../inicio.php">← Volver al panel</a>

</body>
</html>