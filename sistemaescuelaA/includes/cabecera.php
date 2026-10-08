<?php
/*
   Encabezado común de todas las pantallas.

   Variables que puede definir la página antes de incluirlo:
     $titulo -> título de la pantalla
     $menu   -> "admin", "profesor", "ematp" o "" (sin menú)
     $base   -> ruta hacia la carpeta principal ("../" por defecto)
*/
$base   = $base ?? "../";
$titulo = $titulo ?? "Mesa de Ayuda Informática";
$menu   = $menu ?? "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($titulo); ?> · Mesa de Ayuda Informática</title>
    <link rel="stylesheet" href="<?php echo e($base); ?>css/estilos.css">
</head>
<body class="<?php echo $menu === "" ? "sin-menu" : ""; ?>">

<?php
if ($menu !== "") {
    include __DIR__ . "/menu_" . $menu . ".php";
}
?>

<main class="contenedor">
