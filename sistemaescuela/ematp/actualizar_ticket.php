<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id_ticket = $_POST["id_ticket"];
$estado = $_POST["estado"];
$observacion = $_POST["observacion"];
$id_usuario = $_SESSION["id_usuario"];

// Actualizar el ticket
$sql = "UPDATE tickets
        SET estado = '$estado',
            id_ematp_asignado = '$id_usuario'
        WHERE id_ticket = '$id_ticket'";

if(mysqli_query($conexion, $sql)){

    // Guardar el cambio en el historial
    $sqlHistorial = "INSERT INTO historialticket
                    (id_ticket, estado, fecha, observacion, id_usuario)
                    VALUES
                    ('$id_ticket', '$estado', NOW(), '$observacion', '$id_usuario')";

    mysqli_query($conexion, $sqlHistorial);

    header("Location: tickets.php");
    exit();

}else{

    echo "Error al actualizar el ticket.";

}
?>