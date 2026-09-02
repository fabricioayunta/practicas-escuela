<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$sql = "SELECT * FROM laboratorios ORDER BY nombre";
$resultado = mysqli_query($conexion,$sql);

?>

<!DOCTYPE html>

<html lang="es">

<head>
<link rel="stylesheet" href="../css/estilos.css">
<meta charset="UTF-8">

<title>Laboratorios</title>

</head>

<body>
<?php include("../includes/menu_admin.php"); ?>
<h1>Gestión de Laboratorios</h1>

<p>

Bienvenido

<?php echo $_SESSION["nombre"]; ?>

</p>

<a href="inicio.php">

← Volver

</a>

|

<a href="crear_laboratorio.php">

+ Nuevo Laboratorio

</a>

<hr>

<table border="1" cellpadding="10">

<tr>

<th>ID</th>

<th>Nombre</th>

<th>Acciones</th>

</tr>

<?php

while($fila=mysqli_fetch_assoc($resultado)){

?>

<tr>

<td>

<?php echo $fila["id_laboratorio"]; ?>

</td>

<td>

<?php echo $fila["nombre"]; ?>

</td>

<td>

<a href="editar_laboratorio.php?id=<?php echo $fila["id_laboratorio"]; ?>">

Editar

</a>

|

<a
href="eliminar_laboratorio.php?id=<?php echo $fila["id_laboratorio"]; ?>"
onclick="return confirm('¿Eliminar laboratorio?');">

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