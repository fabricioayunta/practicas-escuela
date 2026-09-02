<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$id = $_GET["id"];

$sql = "SELECT * FROM repuestos WHERE id_repuesto = '$id'";
$resultado = mysqli_query($conexion, $sql);
$repuesto = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="css/estilos.css">
    <meta charset="UTF-8">
    <title>Editar Repuesto</title>
</head>
<body>
<?php include("includes/menu_panol_informatico.php"); ?>
<h1>Editar Repuesto</h1>

<a href="repuestos.php">← Volver</a>

<hr>

<form action="actualizar_repuesto.php" method="POST">

    <input type="hidden" name="id_repuesto" value="<?php echo $repuesto["id_repuesto"]; ?>">

    <label>Nombre</label>

    <br>

    <input
        type="text"
        name="nombre"
        value="<?php echo $repuesto["nombre"]; ?>"
        required>

    <br>

    <label>Descripción</label>

    <br>

    <input
        type="text"
        name="descripcion"
        value="<?php echo $repuesto["descripcion"]; ?>">

    <br>

    <label>Cantidad disponible</label>

    <br>

    <input
        type="number"
        name="cantidad"
        min="0"
        value="<?php echo $repuesto["cantidad"]; ?>"
        required>

    <br>

    <button type="submit">
        Guardar Cambios
    </button>

</form>

</body>
</html>
