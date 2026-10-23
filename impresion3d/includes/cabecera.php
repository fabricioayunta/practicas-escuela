<?php
/*
   Encabezado común de todas las pantallas de la Mesa de Ayuda de Máquinas de Impresión 3D.
   Igual al del módulo de Laboratorios.

   Variables que puede definir la página antes de incluirlo:
     $titulo -> título de la pantalla
     $menu   -> "admin", "profesor", "ematp" o "" (sin menú)
     $base   -> ruta hacia la carpeta principal ("../" por defecto)
*/
require_once __DIR__ . "/../../laboratorios/includes/funciones.php";

$base   = $base ?? "../";
$titulo = $titulo ?? "Mesa de Ayuda de Máquinas de Impresión 3D";
$menu   = $menu ?? "";
$archivoEstilos = __DIR__ . "/../../laboratorios/css/estilos.css";
$versionEstilos = is_file($archivoEstilos) ? filemtime($archivoEstilos) : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($titulo); ?> · Mesa de Ayuda de Máquinas de Impresión 3D</title>
    <link rel="stylesheet" href="<?php echo e($base); ?>css/estilos.css?v=<?php echo e($versionEstilos); ?>">
</head>
<body class="<?php echo $menu === "" ? "sin-menu" : ""; ?>">

<?php
if ($menu !== "") {
    include __DIR__ . "/menu_" . $menu . ".php";
}
?>

<main class="contenedor">
