<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

// Traer computadoras con laboratorio
$sql = "SELECT c.*, l.nombre AS laboratorio
        FROM computadoras c
        INNER JOIN laboratorios l
        ON c.id_laboratorio = l.id_laboratorio
        ORDER BY c.id_computadora DESC";

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/estilos.css">
    <title>Computadoras</title>
</head>
<body>
<?php include("../includes/menu_admin.php"); ?>
<h1>Gestión de Computadoras</h1>

<a href="inicio.php">← Volver</a>
|
<a href="crear_computadora.php">+ Nueva Computadora</a>

<hr>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Laboratorio</th>
    <th>N° PC</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>

<?php while($fila = mysqli_fetch_assoc($resultado)){ ?>

<tr>
    <td><?php echo $fila["id_computadora"]; ?></td>
    <td><?php echo $fila["laboratorio"]; ?></td>
    <td>PC <?php echo $fila["numero_pc"]; ?></td>
    <td>
        <?php echo ($fila["estado"] == "Alta") ? "🟢 Alta" : "🔴 Baja"; ?>
    </td>
    <td>
        <a href="editar_computadora.php?id=<?php echo $fila["id_computadora"]; ?>">Editar</a>
        |
        <a href="eliminar_computadora.php?id=<?php echo $fila["id_computadora"]; ?>"
           onclick="return confirm('¿Eliminar computadora?');">
           Eliminar
        </a>
    </td>
</tr>

<?php } ?>

</table>

</body>
</html>