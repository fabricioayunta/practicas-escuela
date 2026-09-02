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
    <link rel="stylesheet" href="css/estilos.css">
    <meta charset="UTF-8">
    <title>Nuevo Repuesto</title>
</head>
<body>
<?php include("includes/menu_panol_informatico.php"); ?>
<h1>Nuevo Repuesto</h1>

<a href="repuestos.php">← Volver</a>

<hr>

<form action="guardar_repuesto.php" method="POST">

    <label>Nombre del repuesto</label>

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

    <label>Cantidad disponible</label>

    <br>

    <input type="number"
           name="cantidad"
           min="0"
           value="0"
           required>

    <br>

    <button type="submit">
        Guardar Repuesto
    </button>

</form>

</body>
</html>
