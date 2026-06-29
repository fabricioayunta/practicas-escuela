<?php

$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "inventariocomputadoras";

$conexion = mysqli_connect(
    $servidor,
    $usuario,
    $contrasena,
    $base_datos
);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

echo "Conexión exitosa";
?>