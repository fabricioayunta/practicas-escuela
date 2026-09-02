<?php
session_start();

/* Verificar que haya una sesión iniciada en este módulo */
if (!isset($_SESSION["id_usuario"])) {
    header("Location: index.php");
    exit();
}

/* Verificar que el rol sea válido */
if (!isset($_SESSION["id_rol"]) || !in_array($_SESSION["id_rol"], [1, 2, 3])) {

    unset($_SESSION["id_usuario"]);
    unset($_SESSION["id_rol"]);

    header("Location: index.php");
    exit();
}

$rol = $_SESSION["id_rol"];
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Panel Principal</title>

    <link rel="stylesheet" href="css/estilos.css">

</head>

<body>

<div class="contenedor">

    <h1>💻 Laboratorios</h1>

    <p>
        Hola,
        <strong>
            <?php echo htmlspecialchars($_SESSION["nombre"]); ?>
        </strong>
    </p>

    <hr>


    <?php if ($rol == 1) { ?>

        <h2>Panel Administrativo</h2>

        <ul>

            <li>
                <a href="administrativo/dashboard.php">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="administrativo/usuarios.php">
                    Gestionar Usuarios
                </a>
            </li>

            <li>
                <a href="administrativo/laboratorios.php">
                    Gestionar Laboratorios
                </a>
            </li>

            <li>
                <a href="administrativo/computadoras.php">
                    Gestionar Computadoras
                </a>
            </li>

            <li>
                <a href="administrativo/tickets.php">
                    Ver Tickets
                </a>
            </li>

        </ul>


    <?php } elseif ($rol == 2) { ?>

        <h2>Panel Profesor</h2>

        <ul>

            <li>
                <a href="profesor/crear_ticket.php">
                    Crear Ticket
                </a>
            </li>

            <li>
                <a href="profesor/mis_tickets.php">
                    Mis Tickets
                </a>
            </li>

        </ul>


    <?php } elseif ($rol == 3) { ?>

        <h2>Panel EMATP</h2>

        <ul>

            <li>
                <a href="ematp/tickets.php">
                    Gestionar Tickets
                </a>
            </li>

            <li>
                <a href="ematp/computadoras.php">
                    Computadoras
                </a>
            </li>

        </ul>

    <?php } ?>


    <hr>

    <a href="../index.php">
        🔁 Cambiar de Módulo
    </a>

    |

    <a href="logout.php">
        🚪 Cerrar Sesión
    </a>

</div>

</body>

</html>
