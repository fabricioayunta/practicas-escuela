<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

// Traer préstamos con el artículo y el usuario que los registró
$sql = "SELECT p.*, a.nombre AS articulo, u.nombre, u.apellido
        FROM prestamos_articulos p
        INNER JOIN articulos a
        ON p.id_articulo = a.id_articulo
        INNER JOIN usuarios u
        ON p.id_usuario = u.id_usuario
        ORDER BY p.id_prestamo DESC";

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Préstamos</title>
</head>
<body>
<?php include("includes/menu_panol.php"); ?>
<h1>Préstamos y Devoluciones</h1>

<p>Bienvenido <?php echo $_SESSION["panol_nombre"]; ?></p>

<a href="inicio.php">← Volver</a>
|
<a href="crear_prestamo.php">+ Nuevo Préstamo</a>

<hr>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Artículo</th>
    <th>Cantidad</th>
    <th>Prestado a</th>
    <th>Fecha Préstamo</th>
    <th>Fecha Devolución</th>
    <th>Estado</th>
    <th>Registró</th>
    <th>Acciones</th>
</tr>

<?php while($fila = mysqli_fetch_assoc($resultado)){ ?>

<tr>
    <td><?php echo $fila["id_prestamo"]; ?></td>
    <td><?php echo $fila["articulo"]; ?></td>
    <td><?php echo $fila["cantidad"]; ?></td>
    <td><?php echo $fila["persona"]; ?></td>
    <td>
        <?php echo date("d/m/Y H:i", strtotime($fila["fecha_prestamo"])); ?>
    </td>
    <td>
        <?php

        if($fila["fecha_devolucion"] == NULL){

            echo "-";

        }else{

            echo date("d/m/Y H:i", strtotime($fila["fecha_devolucion"]));

        }

        ?>
    </td>
    <td>
        <?php

        if($fila["estado"] == "Pendiente"){

            echo "<span class='pendiente'>Pendiente</span>";

        }else{

            echo "<span class='abierto'>Devuelto</span>";

        }

        ?>
    </td>
    <td>
        <?php echo $fila["nombre"]." ".$fila["apellido"]; ?>
    </td>
    <td>
        <?php if($fila["estado"] == "Pendiente"){ ?>

        <a href="devolver_prestamo.php?id=<?php echo $fila["id_prestamo"]; ?>"
           onclick="return confirm('¿Registrar la devolución de este artículo?');">
           Devolver
        </a>

        <?php }else{ ?>

        -

        <?php } ?>
    </td>
</tr>

<?php } ?>

</table>

</body>
</html>
