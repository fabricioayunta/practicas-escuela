<?php
/*
|--------------------------------------------------------------------------
| CONEXIÓN A LA BASE DE DATOS
|--------------------------------------------------------------------------
| Base de datos vigente: inventariocomputadorasA
*/

$servidor   = "localhost";
$usuario    = "root";
$contrasena = "";
$base_datos = "inventariocomputadorasA";

/*
   PHP 8.1 o superior lanza excepciones cuando falla una consulta.
   El sistema está pensado para revisar los errores a mano y mostrar
   mensajes claros, por eso se desactivan esas excepciones.
*/
mysqli_report(MYSQLI_REPORT_OFF);

$conexion = mysqli_connect($servidor, $usuario, $contrasena, $base_datos);

if (!$conexion) {
    die("No se pudo conectar con la base de datos. Verifique que MySQL esté encendido y que la base se llame «" . htmlspecialchars($base_datos) . "».");
}

mysqli_set_charset($conexion, "utf8mb4");

/* Funciones de ayuda compartidas por todas las pantallas */
require_once __DIR__ . "/../includes/funciones.php";
