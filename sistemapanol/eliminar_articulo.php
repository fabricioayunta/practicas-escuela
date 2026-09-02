<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"])) {
    header("Location: index.php");
    exit();
}

include("conexion/conexion.php");

$id = $_GET["id"];

// Verificar si tiene préstamos registrados
$sql = "SELECT COUNT(*) AS total
        FROM prestamos_articulos
        WHERE id_articulo='$id'";

$resultado = mysqli_query($conexion,$sql);
$fila = mysqli_fetch_assoc($resultado);

if($fila["total"] > 0){

    echo "<script>
            alert('No se puede eliminar este artículo porque tiene préstamos registrados.');
            window.location='articulos.php';
          </script>";
    exit();

}

// Eliminar
$sqlEliminar = "DELETE FROM articulos
                WHERE id_articulo='$id'";

mysqli_query($conexion,$sqlEliminar);

header("Location: articulos.php");
exit();
?>
