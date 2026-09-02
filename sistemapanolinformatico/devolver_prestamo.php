<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$id = $_GET["id"];

// Marcar el insumo como devuelto
$sql = "UPDATE prestamos_insumos
        SET estado='Devuelto',
            fecha_devolucion=NOW()
        WHERE id_prestamo='$id'";

if(mysqli_query($conexion,$sql)){

    header("Location: prestamos.php");
    exit();

}else{

    echo "Error al registrar la devolución.";

}
?>
