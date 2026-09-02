<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Pañol Informático</title>

<link rel="stylesheet" href="css/estilos.css">

</head>

<body>
<?php include("includes/menu_panol_informatico.php"); ?>
<h1>Panel Pañol Informático</h1>

<p>Bienvenido <?php echo $_SESSION["pi_nombre"]; ?></p>

<hr>

<ul>

<li><a href="prestamos.php">Préstamos de Insumos</a></li>

<li><a href="repuestos.php">Stock de Repuestos</a></li>

<li><a href="../index.php">Cambiar de Módulo</a></li>

<li><a href="logout.php">Cerrar Sesión</a></li>

</ul>

</body>
</html>
