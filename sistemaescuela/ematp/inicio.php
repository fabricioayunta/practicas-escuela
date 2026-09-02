<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}
?>
<?php include("../includes/menu_ematp.php"); ?>
<h1>Panel EMATP</h1>

<p>Bienvenido <?php echo htmlspecialchars($_SESSION["nombre"]); ?></p>

<hr>

 <ul>

<li><a href="tickets.php">Gestionar Tickets</a></li>

<li><a href="computadoras.php">Computadoras</a></li>

<li><a href="../logout.php">Cerrar Sesión</a></li>

</ul>