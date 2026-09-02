<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$sql = "SELECT * FROM repuestos ORDER BY nombre";
$resultado = mysqli_query($conexion,$sql);

?>

<!DOCTYPE html>

<html lang="es">

<head>
<link rel="stylesheet" href="css/estilos.css">
<meta charset="UTF-8">

<title>Repuestos</title>

</head>

<body>
<?php include("includes/menu_panol_informatico.php"); ?>
<h1>Stock de Repuestos</h1>

<p>

Bienvenido

<?php echo $_SESSION["pi_nombre"]; ?>

</p>

<a href="inicio.php">

← Volver

</a>

|

<a href="crear_repuesto.php">

+ Nuevo Repuesto

</a>

<hr>

<table border="1" cellpadding="10">

<tr>

<th>ID</th>

<th>Nombre</th>

<th>Descripción</th>

<th>Cantidad Disponible</th>

<th>Acciones</th>

</tr>

<?php

while($fila=mysqli_fetch_assoc($resultado)){

?>

<tr>

<td>

<?php echo $fila["id_repuesto"]; ?>

</td>

<td>

<?php echo $fila["nombre"]; ?>

</td>

<td>

<?php echo $fila["descripcion"]; ?>

</td>

<td>

<?php echo $fila["cantidad"]; ?>

</td>

<td>

<a href="editar_repuesto.php?id=<?php echo $fila["id_repuesto"]; ?>">

Editar

</a>

|

<a
href="eliminar_repuesto.php?id=<?php echo $fila["id_repuesto"]; ?>"
onclick="return confirm('¿Eliminar repuesto?');">

Eliminar

</a>

</td>

</tr>

<?php

}

?>

</table>

</body>

</html>
