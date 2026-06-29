<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$sqlLaboratorios = "SELECT * FROM laboratorios ORDER BY nombre";
$laboratorios = mysqli_query($conexion, $sqlLaboratorios);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Ticket</title>
</head>

<body>

<h1>Crear Ticket</h1>

<p>Bienvenido <?php echo $_SESSION["nombre"]; ?></p>

<hr>

<form action="guardar_ticket.php" method="POST">

    <label>Título:</label><br>
    <input type="text" name="titulo" required>

    <br><br>

    <label>Laboratorio:</label><br>

    <select name="id_laboratorio" required>

        <option value="">Seleccione un laboratorio</option>

        <?php while($lab = mysqli_fetch_assoc($laboratorios)){ ?>

            <option value="<?php echo $lab["id_laboratorio"]; ?>">
                <?php echo $lab["nombre"]; ?>
            </option>

        <?php } ?>

    </select>

    <br><br>

    <label>Computadora:</label><br>

    <select name="numero_pc" required>

        <option value="">Seleccione una computadora</option>

        <?php
        for($i=1;$i<=16;$i++){
        ?>

            <option value="<?php echo $i; ?>">
                PC <?php echo $i; ?>
            </option>

        <?php
        }
        ?>

    </select>

    <br><br>

    <label>Descripción:</label><br>

    <textarea name="descripcion" rows="6" cols="50" required></textarea>

    <br><br>

    <button type="submit">
        Crear Ticket
    </button>

</form>

<br>

<a href="inicio.php">← Volver al panel</a>

</body>
</html>