<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}
?>

<h1>Panel Administrativo</h1>

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<hr>

<h3>Opciones</h3>

<ul>
    <li><a href="usuarios.php">Gestionar Usuarios</a></li>
    <li>Gestionar Computadoras</li>
    <li>Gestionar Laboratorios</li>
    <li>Ver Tickets</li>
</ul>