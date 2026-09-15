<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

/* TOTAL COMPUTADORAS */
$sql1 = "SELECT COUNT(*) as total FROM computadoras";
$res1 = mysqli_query($conexion,$sql1);
$totalPC = mysqli_fetch_assoc($res1)["total"];

/* ACTIVAS */
$sql2 = "SELECT COUNT(*) as total FROM computadoras WHERE estado='Alta'";
$res2 = mysqli_query($conexion,$sql2);
$activas = mysqli_fetch_assoc($res2)["total"];

/* BAJAS */
$sql3 = "SELECT COUNT(*) as total FROM computadoras WHERE estado='Baja'";
$res3 = mysqli_query($conexion,$sql3);
$bajas = mysqli_fetch_assoc($res3)["total"];

/* TICKETS ABIERTOS */
$sql4 = "SELECT COUNT(*) as total FROM tickets WHERE estado!='Cerrado'";
$res4 = mysqli_query($conexion,$sql4);
$tickets = mysqli_fetch_assoc($res4)["total"];
/* TOTAL USUARIOS */
$sql5 = "SELECT COUNT(*) as total FROM usuarios";
$res5 = mysqli_query($conexion,$sql5);
$totalUsuarios = mysqli_fetch_assoc($res5)["total"];

/* PROFESORES (rol = 2) */
$sql6 = "SELECT COUNT(*) as total FROM usuarios WHERE id_rol = 2";
$res6 = mysqli_query($conexion,$sql6);
$profesores = mysqli_fetch_assoc($res6)["total"];

/* EMATP (rol = 3) */
$sql7 = "SELECT COUNT(*) as total FROM usuarios WHERE id_rol = 3";
$res7 = mysqli_query($conexion,$sql7);
$ematp = mysqli_fetch_assoc($res7)["total"];

/* LABORATORIOS */
$sql8 = "SELECT COUNT(*) as total FROM laboratorios";
$res8 = mysqli_query($conexion,$sql8);
$laboratorios = mysqli_fetch_assoc($res8)["total"];

/* TICKETS CERRADOS */
$sql9 = "SELECT COUNT(*) as total FROM tickets WHERE estado='Cerrado'";
$res9 = mysqli_query($conexion,$sql9);
$ticketsCerrados = mysqli_fetch_assoc($res9)["total"];

/* TICKETS PENDIENTES */
$sql10 = "SELECT COUNT(*) as total FROM tickets WHERE estado='Pendiente'";
$res10 = mysqli_query($conexion,$sql10);
$ticketsPendientes = mysqli_fetch_assoc($res10)["total"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../css/estilos.css">
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>

<div class="contenedor">

<?php include("../includes/menu_admin.php"); ?>

<h1>Dashboard Administrativo</h1>

<p>Bienvenido <?php echo e($_SESSION["nombre"]); ?></p>

<hr>

<div class="cards">

<div class="card azul">

<h3>💻 Total Computadoras</h3>

<h1><?php echo $totalPC; ?></h1>

</div>

<div class="card verde">

<h3>🟢 Activas</h3>

<h1><?php echo $activas; ?></h1>

</div>

<div class="card rojo">

<h3>🔴 Fuera de Servicio</h3>

<h1><?php echo $bajas; ?></h1>

</div>

<div class="card naranja">

<h3>🎫 Tickets Abiertos</h3>

<h1><?php echo $tickets; ?></h1>

</div>

<div class="card gris">

<h3>👥 Usuarios</h3>

<h1><?php echo $totalUsuarios; ?></h1>

</div>

<div class="card azul">

<h3>👨‍🏫 Profesores</h3>

<h1><?php echo $profesores; ?></h1>

</div>

<div class="card verde">

<h3>🛠 EMATP</h3>

<h1><?php echo $ematp; ?></h1>

</div>

<div class="card naranja">

<h3>🧪 Laboratorios</h3>

<h1><?php echo $laboratorios; ?></h1>

</div>

<div class="card rojo">

<h3>✅ Tickets Cerrados</h3>

<h1><?php echo $ticketsCerrados; ?></h1>

</div>

<div class="card gris">

<h3>⏳ Tickets Pendientes</h3>

<h1><?php echo $ticketsPendientes; ?></h1>

</div>

</div>
<hr>

<a href="inicio.php">Volver al panel</a>
</div>      
</body>
</html>