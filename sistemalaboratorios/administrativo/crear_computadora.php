<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$labs = mysqli_query($conexion,"SELECT * FROM laboratorios");
?>
<?php include("../includes/menu_admin.php"); ?>
<h1>Nueva Computadora</h1>

<a href="computadoras.php">← Volver</a>

<hr>

<form action="guardar_computadora.php" method="POST">

<label>Laboratorio</label>
<br>
<select name="id_laboratorio" required>

<option value="">Seleccionar</option>

<?php while($l = mysqli_fetch_assoc($labs)){ ?>
<option value="<?php echo $l["id_laboratorio"]; ?>">
    <?php echo $l["nombre"]; ?>
</option>
<?php } ?>

</select>

<br><br>

<label>Número de PC</label>
<br>
<input type="number" name="numero_pc" min="1" max="50" required>

<br><br>

<button type="submit">Guardar</button>

</form>