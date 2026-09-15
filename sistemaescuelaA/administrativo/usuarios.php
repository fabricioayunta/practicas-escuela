<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$sql = "SELECT * FROM usuarios";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>

    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>

<?php include("../includes/menu_admin.php"); ?>

<p>Bienvenido <?php echo e($_SESSION["nombre"]); ?></p>

<a href="inicio.php">Volver al panel</a>

<br><br>

<a href="crear_usuario.php" class="btn btn-verde">
➕ Crear Usuario
</a>

<hr>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>

    <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
        <tr>
            <td><?php echo e($fila["id_usuario"]); ?></td>
            <td><?php echo e($fila["nombre"]); ?></td>
            <td><?php echo e($fila["apellido"]); ?></td>
            <td><?php echo e($fila["email"]); ?></td>
            <td><?php echo e($fila["id_rol"]); ?></td>

           <td>

<div class="acciones">

<a
class="btn btn-azul"
href="editar_usuario.php?id=<?php echo e($fila["id_usuario"]); ?>">
✏️ Editar
</a>

<a
class="btn btn-rojo"
href="eliminar_usuario.php?id=<?php echo e($fila["id_usuario"]); ?>"
onclick="return confirm('¿Seguro que querés eliminar este usuario?');">
🗑️ Eliminar
</a>

</div>

</td>
        </tr>
    <?php } ?>

</table>

</body>
</html>

