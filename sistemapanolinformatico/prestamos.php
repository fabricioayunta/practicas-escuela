<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

// Traer préstamos con el usuario que los registró
$sql = "SELECT p.*, u.nombre, u.apellido
        FROM prestamos_insumos p
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
    <title>Préstamos de Insumos</title>
</head>
<body>
<?php include("includes/menu_panol_informatico.php"); ?>
<h1>Gestión de Préstamos de Insumos</h1>

<p>Bienvenido <?php echo $_SESSION["pi_nombre"]; ?></p>

<a href="inicio.php">← Volver</a>
|
<a href="crear_prestamo.php">+ Nueva Entrega</a>

<hr>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Insumo</th>
    <th>Cantidad</th>
    <th>Entregado a</th>
    <th>Fecha Entrega</th>
    <th>Fecha Devolución</th>
    <th>Estado</th>
    <th>Registró</th>
    <th>Acciones</th>
</tr>

<?php while($fila = mysqli_fetch_assoc($resultado)){ ?>

<tr>
    <td><?php echo $fila["id_prestamo"]; ?></td>
    <td><?php echo $fila["insumo"]; ?></td>
    <td><?php echo $fila["cantidad"]; ?></td>
    <td><?php echo $fila["persona"]; ?></td>
    <td>
        <?php echo date("d/m/Y H:i", strtotime($fila["fecha_entrega"])); ?>
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
           onclick="return confirm('¿Registrar la devolución de este insumo?');">
           Devolver
        </a>
        |
        <?php } ?>

        <a href="eliminar_prestamo.php?id=<?php echo $fila["id_prestamo"]; ?>"
           onclick="return confirm('¿Eliminar préstamo?');">
           Eliminar
        </a>
    </td>
</tr>

<?php } ?>

</table>

</body>
</html>
