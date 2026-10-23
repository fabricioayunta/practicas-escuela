<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

/* Artículos */
$totalArticulos = contar($conexion, "SELECT COUNT(*) AS total FROM articulos");
$articulosAlta  = contar($conexion, "SELECT COUNT(*) AS total FROM articulos WHERE estado = 'Alta'");
$articulosBaja  = contar($conexion, "SELECT COUNT(*) AS total FROM articulos WHERE estado = 'Baja'");

/* Préstamos */
$prestamosPendientes = contar($conexion, "SELECT COUNT(*) AS total FROM prestamos_articulos WHERE estado = 'Pendiente'");
$prestamosDevueltos  = contar($conexion, "SELECT COUNT(*) AS total FROM prestamos_articulos WHERE estado = 'Devuelto'");

/* Usuarios con acceso a este módulo (3 = Herramientas) */
$sqlUsuarios = "SELECT COUNT(*) AS total
                FROM usuarios
                INNER JOIN usuarios_modulos
                    ON usuarios_modulos.id_usuario = usuarios.id_usuario
                    AND usuarios_modulos.id_modulo = 3";

$totalUsuarios = contar($conexion, $sqlUsuarios);
$profesores    = contar($conexion, $sqlUsuarios . " WHERE usuarios.id_rol = 2");
$ematp         = contar($conexion, $sqlUsuarios . " WHERE usuarios.id_rol = 3");

$titulo = "Resumen";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Resumen general</h1>
    <p>Un vistazo rápido al estado de las herramientas.</p>
</div>

<h2 style="margin-top:0;">Artículos</h2>

<div class="cards">
    <div class="card azul">
        <h3>Total de artículos</h3>
        <div class="numero"><?php echo $totalArticulos; ?></div>
    </div>
    <div class="card verde">
        <h3>Dados de alta</h3>
        <div class="numero"><?php echo $articulosAlta; ?></div>
    </div>
    <div class="card rojo">
        <h3>Dados de baja</h3>
        <div class="numero"><?php echo $articulosBaja; ?></div>
    </div>
</div>

<h2>Préstamos</h2>

<div class="cards">
    <div class="card naranja">
        <h3>Pendientes de devolución</h3>
        <div class="numero"><?php echo $prestamosPendientes; ?></div>
    </div>
    <div class="card verde">
        <h3>Devueltos</h3>
        <div class="numero"><?php echo $prestamosDevueltos; ?></div>
    </div>
</div>

<h2>Usuarios con acceso a este módulo</h2>

<div class="cards">
    <div class="card azul">
        <h3>Usuarios</h3>
        <div class="numero"><?php echo $totalUsuarios; ?></div>
    </div>
    <div class="card azul">
        <h3>Profesores</h3>
        <div class="numero"><?php echo $profesores; ?></div>
    </div>
    <div class="card azul">
        <h3>EMATP</h3>
        <div class="numero"><?php echo $ematp; ?></div>
    </div>
</div>

<div class="barra-acciones" style="margin-top:24px;">
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<?php include "../includes/pie.php"; ?>
