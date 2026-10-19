<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

/* Computadoras */
$totalPC = contar($conexion, "SELECT COUNT(*) AS total FROM computadoras");
$activas = contar($conexion, "SELECT COUNT(*) AS total FROM computadoras WHERE estado = 'Alta'");
$bajas   = contar($conexion, "SELECT COUNT(*) AS total FROM computadoras WHERE estado = 'Baja'");

/* Tickets */
$ticketsAbiertos   = contar($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE estado = 'Abierto'");
$ticketsPendientes = contar($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE estado = 'Pendiente'");
$ticketsCerrados   = contar($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE estado = 'Cerrado'");

/* Usuarios y laboratorios */
$totalUsuarios = contar($conexion, "SELECT COUNT(*) AS total FROM usuarios");
$profesores    = contar($conexion, "SELECT COUNT(*) AS total FROM usuarios WHERE id_rol = 2");
$ematp         = contar($conexion, "SELECT COUNT(*) AS total FROM usuarios WHERE id_rol = 3");
$laboratorios  = contar($conexion, "SELECT COUNT(*) AS total FROM laboratorios");

$titulo = "Resumen";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Resumen general</h1>
    <p>Un vistazo rápido al estado del sistema.</p>
</div>

<h2 style="margin-top:0;">Computadoras</h2>

<div class="cards">
    <div class="card azul">
        <h3>Total de computadoras</h3>
        <div class="numero"><?php echo $totalPC; ?></div>
    </div>
    <div class="card verde">
        <h3>Operativas</h3>
        <div class="numero"><?php echo $activas; ?></div>
    </div>
    <div class="card rojo">
        <h3>Fuera de servicio</h3>
        <div class="numero"><?php echo $bajas; ?></div>
    </div>
</div>

<h2>Tickets</h2>

<div class="cards">
    <div class="card verde">
        <h3>Abiertos</h3>
        <div class="numero"><?php echo $ticketsAbiertos; ?></div>
    </div>
    <div class="card naranja">
        <h3>Pendientes</h3>
        <div class="numero"><?php echo $ticketsPendientes; ?></div>
    </div>
    <div class="card gris">
        <h3>Cerrados</h3>
        <div class="numero"><?php echo $ticketsCerrados; ?></div>
    </div>
</div>

<h2>Usuarios y laboratorios</h2>

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
    <div class="card azul">
        <h3>Laboratorios</h3>
        <div class="numero"><?php echo $laboratorios; ?></div>
    </div>
</div>

<div class="barra-acciones" style="margin-top:24px;">
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<?php include "../includes/pie.php"; ?>
