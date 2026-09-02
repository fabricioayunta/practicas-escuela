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
    <title>Nueva Entrega de Insumo</title>
</head>
<body>
<?php include("includes/menu_panol_informatico.php"); ?>
<h1>Nueva Entrega de Insumo</h1>

<a href="prestamos.php">← Volver</a>

<hr>

<form action="guardar_prestamo.php" method="POST">

    <label>Insumo</label>

    <br>

    <input type="text"
           name="insumo"
           required
           maxlength="100">

    <br>

    <label>Cantidad</label>

    <br>

    <input type="number"
           name="cantidad"
           min="1"
           value="1"
           required>

    <br>

    <label>Entregado a</label>

    <br>

    <input type="text"
           name="persona"
           required
           maxlength="100">

    <br>

    <label>Observaciones</label>

    <br>

    <textarea name="observaciones"
              rows="3"
              maxlength="255"></textarea>

    <br>

    <button type="submit">
        Guardar Entrega
    </button>

</form>

</body>
</html>
