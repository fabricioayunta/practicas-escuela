<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../index.php");
    exit();
}
?>

<h1>Panel Profesor</h1>

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<hr>

<h3>Opciones</h3>

<ul>
    <li><a href="crear_ticket.php">Crear Ticket</a></li>
    <li><a href="mis_tickets.php">Mis Tickets</a></li>
</ul>