<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id_ticket = (int)($_GET["id"] ?? 0);

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
        WHERE tickets.id_ticket = ?";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_ticket);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$ticket = mysqli_fetch_assoc($resultado);

// Obtener historial del ticket
$sqlHistorial = "SELECT
                    historialticket.*,
                    usuarios.nombre,
                    usuarios.apellido
                FROM historialticket
                INNER JOIN usuarios
                    ON historialticket.id_usuario = usuarios.id_usuario
                WHERE historialticket.id_ticket = ?
                ORDER BY historialticket.fecha DESC";

$stmt = mysqli_prepare($conexion, $sqlHistorial);
mysqli_stmt_bind_param($stmt, "i", $id_ticket);
mysqli_stmt_execute($stmt);
$historial = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../css/estilos.css">
<meta charset="UTF-8">
<title>Gestionar Ticket</title>
</head>

<body>
<?php include("../includes/menu_ematp.php"); ?>
<h1>Gestionar Ticket #<?php echo e($ticket["id_ticket"]); ?></h1>

<a href="tickets.php">← Volver a Tickets</a>

<hr>

<h3>Información del Ticket</h3>

<p>
    <strong>Profesor:</strong>
    <?php echo e($ticket["nombre"]) . " " . $ticket["apellido"]; ?>
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
    <strong>Descripción:</strong><br>
    <?php echo nl2br(e($ticket["descripcion"])); ?>
</p>

<?php if (!empty($ticket["foto"])) { ?>
    <p>
        <strong>Foto del problema:</strong>
    </p>
    <a
        href="../uploads/tickets/<?php echo htmlspecialchars($ticket["foto"]); ?>"
        target="_blank"
    >
        <img
            src="../uploads/tickets/<?php echo htmlspecialchars($ticket["foto"]); ?>"
            alt="Foto del problema"
            style="max-width:200px; max-height:300px;"
        >
    </a>
<?php } ?>

<br><br>

<p><strong>Estado actual:</strong>
<?php echo e($ticket["estado"]); ?>
</p>

<hr>
<h3>Cambiar Estado</h3>

<form action="actualizar_ticket.php" method="POST">

<input
type="hidden"
name="id_ticket"
value="<?php echo e($ticket["id_ticket"]); ?>">

<label>Estado</label>

<br>

<select name="estado">

<option value="Abierto"
<?php if($ticket["estado"]=="Abierto") echo "selected"; ?>>Abierto
</option>

<option value="Pendiente"
<?php if($ticket["estado"]=="Pendiente") echo "selected"; ?>>Pendiente
</option>

<option value="Cerrado"
<?php if($ticket["estado"]=="Cerrado") echo "selected"; ?>>Cerrado
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
<?php echo e($fila["estado"]); ?>
</td>

<td>
<?php echo e($fila["observacion"]); ?>
</td>

<td>
<?php
echo e($fila["nombre"])." ".e($fila["apellido"]);
?>
</td>

</tr>
<?php } ?>
</table>

</body>
</html>