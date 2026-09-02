<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$id = $_GET["id"];

$sql = "SELECT * FROM articulos WHERE id_articulo = '$id'";
$resultado = mysqli_query($conexion, $sql);
$articulo = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="css/estilos.css">
    <meta charset="UTF-8">
    <title>Editar Artículo</title>
</head>
<body>
<?php include("includes/menu_panol.php"); ?>
<h1>Editar Artículo</h1>

<a href="articulos.php">← Volver</a>

<hr>

<form action="actualizar_articulo.php" method="POST">

    <input type="hidden" name="id_articulo" value="<?php echo $articulo["id_articulo"]; ?>">

    <label>Nombre</label>

    <br>

    <input
        type="text"
        name="nombre"
        value="<?php echo $articulo["nombre"]; ?>"
        required>

    <br>

    <label>Descripción</label>

    <br>

    <input
        type="text"
        name="descripcion"
        value="<?php echo $articulo["descripcion"]; ?>">

    <br>

    <label>Cantidad</label>

    <br>

    <input
        type="number"
        name="cantidad"
        min="1"
        value="<?php echo $articulo["cantidad"]; ?>"
        required>

    <br>

    <button type="submit">
        Guardar Cambios
    </button>

</form>

</body>
</html>
