<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Pañol</title>

<link rel="stylesheet" href="css/estilos.css">

</head>

<body>
<?php include("includes/menu_panol.php"); ?>
<h1>Panel Pañol</h1>

<p>Bienvenido <?php echo $_SESSION["panol_nombre"]; ?></p>

<hr>

<ul>

<li><a href="articulos.php">Gestionar Artículos</a></li>

<li><a href="prestamos.php">Préstamos y Devoluciones</a></li>

<li><a href="../index.php">Cambiar de Módulo</a></li>

<li><a href="logout.php">Cerrar Sesión</a></li>

</ul>

</body>
</html>
