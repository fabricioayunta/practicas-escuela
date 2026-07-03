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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>

<h1>Dashboard Administrativo</h1>

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<hr>

<div style="display:flex; gap:20px;">

    <div style="padding:10px; border:1px solid black;">
        <h3>Total PCs</h3>
        <h2><?php echo $totalPC; ?></h2>
    </div>

    <div style="padding:10px; border:1px solid black;">
        <h3>Activas</h3>
        <h2 style="color:green;"><?php echo $activas; ?></h2>
    </div>

    <div style="padding:10px; border:1px solid black;">
        <h3>Bajas</h3>
        <h2 style="color:red;"><?php echo $bajas; ?></h2>
    </div>

    <div style="padding:10px; border:1px solid black;">
        <h3>Tickets abiertos</h3>
        <h2 style="color:orange;"><?php echo $tickets; ?></h2>
    </div>

</div>

<hr>

<a href="inicio.php">Volver al panel</a>

</body>
</html>