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

<h1>Gestión de Usuarios</h1>

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<a href="inicio.php">Volver al panel</a>

<br><br>

<a href="crear_usuario.php">+ Crear nuevo usuario</a>

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
            <td><?php echo $fila["id_usuario"]; ?></td>
            <td><?php echo $fila["nombre"]; ?></td>
            <td><?php echo $fila["apellido"]; ?></td>
            <td><?php echo $fila["email"]; ?></td>
            <td><?php echo $fila["id_rol"]; ?></td>

            <td>
                <a href="editar_usuario.php?id=<?php echo $fila["id_usuario"]; ?>">Editar</a>
                |
                <a href="eliminar_usuario.php?id=<?php echo $fila["id_usuario"]; ?>"
                   onclick="return confirm('¿Seguro que querés eliminar este usuario?');">
                   Eliminar
                </a>
            </td>
        </tr>
    <?php } ?>

</table>