<?php
/*
   Encabezado común de todas las pantallas del Pañol.
   Igual al del módulo de Laboratorios.

   Variables que puede definir la página antes de incluirlo:
     $titulo -> título de la pantalla
     $menu   -> "admin", "profesor", "ematp" o "" (sin menú)
     $base   -> ruta hacia la carpeta principal ("../" por defecto)
*/
require_once __DIR__ . "/../../sistemalaboratorios/includes/funciones.php";

$base   = $base ?? "../";
$titulo = $titulo ?? "Pañol";
$menu   = $menu ?? "";
$archivoEstilos = __DIR__ . "/../../sistemalaboratorios/css/estilos.css";
$versionEstilos = is_file($archivoEstilos) ? filemtime($archivoEstilos) : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($titulo); ?> · Pañol</title>
    <link rel="stylesheet" href="<?php echo e($base); ?>css/estilos.css?v=<?php echo e($versionEstilos); ?>">
</head>
<body class="<?php echo $menu === "" ? "sin-menu" : ""; ?>">

<?php
if ($menu !== "") {
    include __DIR__ . "/menu_" . $menu . ".php";
}
?>

<main class="contenedor">
