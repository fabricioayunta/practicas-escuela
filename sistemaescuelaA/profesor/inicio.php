<?php
session_start();

/* Verificar que haya una sesión iniciada */
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.php");
    exit();
}

/* Verificar que el usuario sea profesor */
if (!isset($_SESSION["id_rol"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../index.php");
    exit();
}

/* Función para escapar texto */
function e($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Profesor</title>

    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>

<?php include("../includes/menu_profesor.php"); ?>

<h1>Panel Profesor</h1>

<p>
    Bienvenido, <?php echo e($_SESSION["nombre"] ?? "Profesor"); ?>
</p>

<hr>

<ul>
    <li>
        <a href="crear_ticket.php">Crear Ticket</a>
    </li>

    <li>
        <a href="mis_tickets.php">Mis Tickets</a>
    </li>

    <li>
        <a href="../logout.php">Cerrar Sesión</a>
    </li>
</ul>

</body>
</html>