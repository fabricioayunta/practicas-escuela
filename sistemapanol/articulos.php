<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$sql = "SELECT * FROM articulos ORDER BY nombre";
$resultado = mysqli_query($conexion,$sql);

?>

<!DOCTYPE html>

<html lang="es">

<head>
<link rel="stylesheet" href="css/estilos.css">
<meta charset="UTF-8">

<title>Artículos</title>

</head>

<body>
<?php include("includes/menu_panol.php"); ?>
<h1>Gestión de Artículos</h1>

<p>

Bienvenido

<?php echo $_SESSION["panol_nombre"]; ?>

</p>

<a href="inicio.php">

← Volver

</a>

|

<a href="crear_articulo.php">

+ Nuevo Artículo

</a>

<hr>

<table border="1" cellpadding="10">

<tr>

<th>ID</th>

<th>Nombre</th>

<th>Descripción</th>

<th>Cantidad</th>

<th>Estado</th>

<th>Acciones</th>

</tr>

<?php

while($fila=mysqli_fetch_assoc($resultado)){

?>

<tr>

<td>

<?php echo $fila["id_articulo"]; ?>

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

<?php echo ($fila["estado"] == "Alta") ? "🟢 Alta" : "🔴 Baja"; ?>

</td>

<td>

<a href="editar_articulo.php?id=<?php echo $fila["id_articulo"]; ?>">

Editar

</a>

|

<?php if($fila["estado"] == "Alta"){ ?>

<a
href="cambiar_estado_articulo.php?id=<?php echo $fila["id_articulo"]; ?>&estado=Baja"
onclick="return confirm('¿Dar de baja este artículo?');">

Dar de Baja

</a>

<?php }else{ ?>

<a
href="cambiar_estado_articulo.php?id=<?php echo $fila["id_articulo"]; ?>&estado=Alta"
onclick="return confirm('¿Dar de alta este artículo?');">

Dar de Alta

</a>

<?php } ?>

|

<a
href="eliminar_articulo.php?id=<?php echo $fila["id_articulo"]; ?>"
onclick="return confirm('¿Eliminar artículo?');">

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
