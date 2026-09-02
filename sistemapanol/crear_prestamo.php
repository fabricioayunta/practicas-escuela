<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

// Solo se pueden prestar los artículos que están de alta
$articulos = mysqli_query($conexion,"SELECT * FROM articulos WHERE estado='Alta' ORDER BY nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="css/estilos.css">
    <meta charset="UTF-8">
    <title>Nuevo Préstamo</title>
</head>
<body>
<?php include("includes/menu_panol.php"); ?>
<h1>Nuevo Préstamo</h1>

<a href="prestamos.php">← Volver</a>

<hr>

<form action="guardar_prestamo.php" method="POST">

<label>Artículo</label>
<br>
<select name="id_articulo" required>

<option value="">Seleccionar</option>

<?php while($a = mysqli_fetch_assoc($articulos)){ ?>
<option value="<?php echo $a["id_articulo"]; ?>">
    <?php echo $a["nombre"]; ?>
</option>
<?php } ?>

</select>

<br>

<label>Cantidad</label>
<br>
<input type="number" name="cantidad" min="1" value="1" required>

<br>

<label>Prestado a</label>
<br>
<input type="text" name="persona" required maxlength="100">

<br>

<label>Observaciones</label>
<br>
<textarea name="observaciones" rows="3" maxlength="255"></textarea>

<br>

<button type="submit">Guardar Préstamo</button>

</form>

</body>
</html>
