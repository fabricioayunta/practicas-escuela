<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../css/estilos.css">
    <meta charset="UTF-8">
    <title>Panel Administrativo</title>
</head>
<body>

<h1>Panel Administrativo</h1>

<p>Bienvenido <?php echo e($_SESSION["nombre"]); ?></p>

<hr>

<h3>Opciones</h3>

<ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="usuarios.php">Gestionar Usuarios</a></li>
    <li><a href="laboratorios.php">Gestionar Laboratorios</a></li>
    <li><a href="computadoras.php">Gestionar Computadoras</a></li>
    <li><a href="tickets.php">Ver Tickets</a></li>
    <li><a href="../logout.php">Cerrar Sesión</a></li>
</ul>

</body>
</html>