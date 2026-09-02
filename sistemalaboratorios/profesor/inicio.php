<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Profesor</title>

<link rel="stylesheet" href="../css/estilos.css">

</head>

<body>
<?php include("../includes/menu_profesor.php"); ?>
<h1>Panel Profesor</h1>

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<hr>

<ul>

<li><a href="crear_ticket.php">Crear Ticket</a></li>

<li><a href="mis_tickets.php">Mis Tickets</a></li>

<li><a href="../logout.php">Cerrar Sesión</a></li>

</ul>

</body>
</html>