<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_GET["id"];

$sql = "SELECT * FROM laboratorios WHERE id_laboratorio = '$id'";
$resultado = mysqli_query($conexion, $sql);
$laboratorio = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../css/estilos.css">
    <meta charset="UTF-8">
    <title>Editar Laboratorio</title>
</head>
<body>
<?php include("../includes/menu_admin.php"); ?>
<h1>Editar Laboratorio</h1>

<a href="laboratorios.php">← Volver</a>

<hr>

<form action="actualizar_laboratorio.php" method="POST">

    <input type="hidden" name="id_laboratorio" value="<?php echo $laboratorio["id_laboratorio"]; ?>">

    <label>Nombre</label>

    <br><br>

    <input
        type="text"
        name="nombre"
        value="<?php echo $laboratorio["nombre"]; ?>"
        required>

    <br><br>

    <button type="submit">
        Guardar Cambios
    </button>

</form>

</body>
</html>