<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel EMATP</title>
</head>
<body>

<h1>Panel EMATP</h1>

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<hr>

<h3>Opciones</h3>

<ul>
    <li><a href="tickets.php">Ver Tickets</a></li>
</ul>

</body>
</html>