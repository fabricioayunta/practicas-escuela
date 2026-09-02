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

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<a href="dashboard.php">Volver al panel</a>

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
        <th>Módulos</th>
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

<?php

// Módulos a los que accede el usuario
$sqlModulos = "SELECT m.nombre
               FROM usuarios_modulos um
               INNER JOIN modulos m
               ON um.id_modulo = m.id_modulo
               WHERE um.id_usuario = ".$fila["id_usuario"]."
               ORDER BY m.id_modulo";

$resModulos = mysqli_query($conexion, $sqlModulos);

if(mysqli_num_rows($resModulos) == 0){

    echo "-";

}else{

    while($m = mysqli_fetch_assoc($resModulos)){

        echo $m["nombre"]."<br>";

    }

}

?>

            </td>

           <td>

<div class="acciones">

<a
class="btn btn-azul"
href="editar_usuario.php?id=<?php echo $fila["id_usuario"]; ?>">
✏️ Editar
</a>

<a
class="btn btn-rojo"
href="eliminar_usuario.php?id=<?php echo $fila["id_usuario"]; ?>"
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
