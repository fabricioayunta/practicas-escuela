<?php

session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id_ticket = $_GET["id"] ?? "";
$id_usuario = $_SESSION["id_usuario"];


/* =========================
   BUSCAR TICKET
========================= */

$sql = "
    SELECT
        tickets.*,
        computadoras.numero_pc,
        laboratorios.nombre AS laboratorio

    FROM tickets

    INNER JOIN computadoras
        ON tickets.id_computadora = computadoras.id_computadora

    INNER JOIN laboratorios
        ON computadoras.id_laboratorio = laboratorios.id_laboratorio

    WHERE tickets.id_ticket = ?
    AND tickets.id_usuario = ?
    AND tickets.estado = 'Abierto'
";

$stmt = mysqli_prepare($conexion, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $id_ticket,
    $id_usuario
);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($resultado) != 1) {

    echo "No tenés permiso para editar este ticket.";

    exit();

}

$ticket = mysqli_fetch_assoc($resultado);


/* =========================
   COMPONENTES ACTUALES
========================= */

$componentesActuales = explode(
    ", ",
    $ticket["componentes_afectados"] ?? ""
);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Editar Ticket</title>

    <link rel="stylesheet" href="../css/estilos.css">

</head>

<body>

<?php include("../includes/menu_profesor.php"); ?>

<h1>
    Editar Ticket #<?php echo e($ticket["id_ticket"]); ?>
</h1>

<hr>


<p>

<strong>Laboratorio:</strong>

<?php echo htmlspecialchars($ticket["laboratorio"]); ?>

</p>


<p>

<strong>Computadora:</strong>

PC <?php echo htmlspecialchars($ticket["numero_pc"]); ?>

</p>


<hr>


<form action="actualizar_ticket_profesor.php" method="POST">


<input
    type="hidden"
    name="id_ticket"
    value="<?php echo e($ticket["id_ticket"]); ?>"
>


<!-- INTERNOS -->

<h3>Componentes internos</h3>


<label>

<input
    type="checkbox"
    name="componentes[]"
    value="Mother"

    <?php
    if (in_array("Mother", $componentesActuales))
        echo "checked";
    ?>
>

Mother

</label>

<br>


<label>

<input
    type="checkbox"
    name="componentes[]"
    value="Procesador"

    <?php
    if (in_array("Procesador", $componentesActuales))
        echo "checked";
    ?>
>

Procesador

</label>

<br>


<label>

<input
    type="checkbox"
    name="componentes[]"
    value="Memoria RAM"

    <?php
    if (in_array("Memoria RAM", $componentesActuales))
        echo "checked";
    ?>
>

Memoria RAM

</label>

<br>


<label>

<input
    type="checkbox"
    name="componentes[]"
    value="Disco"

    <?php
    if (in_array("Disco", $componentesActuales))
        echo "checked";
    ?>
>

Disco

</label>


<br><br>


<!-- EXTERNOS -->

<h3>Componentes externos</h3>


<label>

<input
    type="checkbox"
    name="componentes[]"
    value="Monitor"

    <?php
    if (in_array("Monitor", $componentesActuales))
        echo "checked";
    ?>
>

Monitor

</label>

<br>


<label>

<input
    type="checkbox"
    name="componentes[]"
    value="Teclado"

    <?php
    if (in_array("Teclado", $componentesActuales))
        echo "checked";
    ?>
>

Teclado

</label>

<br>


<label>

<input
    type="checkbox"
    name="componentes[]"
    value="Mouse"

    <?php
    if (in_array("Mouse", $componentesActuales))
        echo "checked";
    ?>
>

Mouse

</label>


<br><br>

<hr>


<label>

<strong>Observación / Problema:</strong>

</label>

<br>


<textarea
    name="observacion"
    rows="6"
    cols="60"
    required
><?php echo htmlspecialchars($ticket["descripcion"]); ?></textarea>


<br><br>


<button type="submit">

Guardar Cambios

</button>


</form>


<br>


<a href="mis_tickets.php">

← Volver a Mis Tickets

</a>


</body>

</html>