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

    <link rel="stylesheet" href="../css/estilos.css">

</head>

<body>

<?php include("../includes/menu_profesor.php"); ?>

<h1>Crear Ticket</h1>

<p>
    Bienvenido <?php echo htmlspecialchars($_SESSION["nombre"]); ?>
</p>

<hr>

<form action="guardar_ticket.php" method="POST" enctype="multipart/form-data">

    <!-- LABORATORIO -->

    <label>Laboratorio:</label>

    <br>

    <select name="id_laboratorio" required>

        <option value="">
            Seleccione un laboratorio
        </option>

        <?php while($lab = mysqli_fetch_assoc($laboratorios)){ ?>

            <option value="<?php echo $lab["id_laboratorio"]; ?>">

                <?php echo htmlspecialchars($lab["nombre"]); ?>

            </option>

        <?php } ?>

    </select>


    <br><br>


    <!-- COMPUTADORA -->

    <label>Computadora:</label>

    <br>

    <select name="numero_pc" required>

        <option value="">
            Seleccione una computadora
        </option>

        <?php for($i = 1; $i <= 16; $i++){ ?>

            <option value="<?php echo $i; ?>">

                PC <?php echo $i; ?>

            </option>

        <?php } ?>

    </select>


    <br><br>

    <hr>


    <!-- COMPONENTES INTERNOS -->

    <h3>Componentes internos</h3>

    <label>

        <input
            type="checkbox"
            name="componentes[]"
            value="Mother"
        >

        Mother

    </label>

    <br>

    <label>

        <input
            type="checkbox"
            name="componentes[]"
            value="Procesador"
        >

        Procesador

    </label>

    <br>

    <label>

        <input
            type="checkbox"
            name="componentes[]"
            value="Memoria RAM"
        >

        Memoria RAM

    </label>

    <br>

    <label>

        <input
            type="checkbox"
            name="componentes[]"
            value="Disco"
        >

        Disco

    </label>


    <br><br>


    <!-- COMPONENTES EXTERNOS -->

    <h3>Componentes externos</h3>

    <label>

        <input
            type="checkbox"
            name="componentes[]"
            value="Monitor"
        >

        Monitor

    </label>

    <br>

    <label>

        <input
            type="checkbox"
            name="componentes[]"
            value="Teclado"
        >

        Teclado

    </label>

    <br>

    <label>

        <input
            type="checkbox"
            name="componentes[]"
            value="Mouse"
        >

        Mouse

    </label>


    <br><br>

    <hr>


    <!-- OBSERVACIÓN -->

    <label>
        <strong>Observación / Problema:</strong>
    </label>

    <br>

    <textarea
        name="observacion"
        rows="6"
        cols="60"
        placeholder="Describa qué sucede con el componente seleccionado..."
        required
    ></textarea>


    <br><br>
<br><br>

<label>
    <strong>Foto del problema (opcional):</strong>
</label>

<br>

<input
    type="file"
    name="foto"
    accept="image/jpeg,image/png,image/webp"
>

<br>

<small>
    Formatos permitidos: JPG, PNG o WEBP. Máximo 5 MB.
</small>

<br><br>
    <button type="submit">
        Crear Ticket
    </button>

</form>

<br>

<a href="../inicio.php">
    ← Volver al panel
</a>

</body>

</html>