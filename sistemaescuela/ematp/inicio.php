<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}
?>

<h1>Panel EMATP</h1>

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<hr>

<a href="tickets.php">Gestionar Tickets</a>

<br><br>

<a href="computadoras.php">Gestionar Computadoras</a>

<br><br>

<a href="../logout.php">Cerrar Sesión</a>