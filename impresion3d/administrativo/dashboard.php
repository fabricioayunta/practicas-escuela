<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

/* Repuestos */
$totalRepuestos = contar($conexion, "SELECT COUNT(*) AS total FROM repuestos");
$unidades       = contar($conexion, "SELECT COALESCE(SUM(cantidad), 0) AS total FROM repuestos");
$sinStock       = contar($conexion, "SELECT COUNT(*) AS total FROM repuestos WHERE cantidad = 0");

/* Préstamos de insumos */
$prestamosPendientes = contar($conexion, "SELECT COUNT(*) AS total FROM prestamos_insumos WHERE estado = 'Pendiente'");
$prestamosDevueltos  = contar($conexion, "SELECT COUNT(*) AS total FROM prestamos_insumos WHERE estado = 'Devuelto'");

/* Usuarios con acceso a este módulo (2 = Máquinas de Impresión 3D) */
$sqlUsuarios = "SELECT COUNT(*) AS total
                FROM usuarios
                INNER JOIN usuarios_modulos
                    ON usuarios_modulos.id_usuario = usuarios.id_usuario
                    AND usuarios_modulos.id_modulo = 2";

$totalUsuarios = contar($conexion, $sqlUsuarios);
$profesores    = contar($conexion, $sqlUsuarios . " WHERE usuarios.id_rol = 2");
$ematp         = contar($conexion, $sqlUsuarios . " WHERE usuarios.id_rol = 3");

$titulo = "Resumen";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Resumen general</h1>
    <p>Un vistazo rápido al estado de las máquinas de impresión 3D.</p>
</div>

<h2 style="margin-top:0;">Repuestos</h2>

<div class="cards">
    <div class="card azul">
        <h3>Tipos de repuesto</h3>
        <div class="numero"><?php echo $totalRepuestos; ?></div>
    </div>
    <div class="card verde">
        <h3>Unidades en stock</h3>
        <div class="numero"><?php echo $unidades; ?></div>
    </div>
    <div class="card rojo">
        <h3>Sin stock</h3>
        <div class="numero"><?php echo $sinStock; ?></div>
    </div>
</div>

<h2>Préstamos de insumos</h2>

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
