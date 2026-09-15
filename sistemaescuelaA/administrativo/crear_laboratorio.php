<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../css/estilos.css">
    <meta charset="UTF-8">
    <title>Nuevo Laboratorio</title>
</head>
<body>
<?php include("../includes/menu_admin.php"); ?>
<h1>Nuevo Laboratorio</h1>

<a href="laboratorios.php">← Volver</a>

<hr>

<form action="guardar_laboratorio.php" method="POST">

    <label>Nombre del laboratorio</label>

    <br><br>

    <input type="text"
           name="nombre"
           required
           maxlength="50">

    <br><br>

    <button type="submit">
        Guardar Laboratorio
    </button>

</form>

</body>
</html>