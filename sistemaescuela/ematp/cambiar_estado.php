<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id_pc = $_POST["id_computadora"];
$nuevo_estado = $_POST["nuevo_estado"];
$id_user = $_SESSION["id_usuario"];
$motivo = $_POST["motivo"] ?? "";

// Actualizar estado
$sql = "UPDATE computadoras
SET estado = '$nuevo_estado'
WHERE id_computadora = '$id_pc'";

if(!mysqli_query($conexion,$sql)){
    die(mysqli_error($conexion));
}

// Historial
if ($nuevo_estado == "Baja") {
    $accion="La computadora fue dada de baja.\nMotivo: ".$motivo;
}else{
    $accion="La computadora fue dada de alta";
}

$sqlHistorial="INSERT INTO historial_computadoras
(id_computadora,id_usuario,accion)
VALUES
('$id_pc','$id_user','$accion')";

mysqli_query($conexion,$sqlHistorial);

header("Location: ver_computadora.php?id=".$id_pc);
exit();