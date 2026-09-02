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
    <link rel="stylesheet" href="css/estilos.css">
    <meta charset="UTF-8">
    <title>Nuevo Artículo</title>
</head>
<body>
<?php include("includes/menu_panol.php"); ?>
<h1>Nuevo Artículo</h1>

<a href="articulos.php">← Volver</a>

<hr>

<form action="guardar_articulo.php" method="POST">

    <label>Nombre del artículo</label>

    <br>

    <input type="text"
           name="nombre"
           required
           maxlength="100">

    <br>

    <label>Descripción</label>

    <br>

    <input type="text"
           name="descripcion"
           maxlength="255">

    <br>

    <label>Cantidad</label>

    <br>

    <input type="number"
           name="cantidad"
           min="1"
           value="1"
           required>

    <br>

    <button type="submit">
        Guardar Artículo
    </button>

</form>

</body>
</html>
