<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_GET["id"];

$sql = "SELECT * FROM computadoras WHERE id_computadora='$id'";
$res = mysqli_query($conexion,$sql);
$pc = mysqli_fetch_assoc($res);

$labs = mysqli_query($conexion,"SELECT * FROM laboratorios");
?>
<?php include("../includes/menu_admin.php"); ?>
<h1>Editar Computadora</h1>

<a href="computadoras.php">← Volver</a>

<hr>

<form action="actualizar_computadora.php" method="POST">

<input type="hidden" name="id" value="<?php echo $pc["id_computadora"]; ?>">

<label>Laboratorio</label>
<br>
<select name="id_laboratorio">

<?php while($l = mysqli_fetch_assoc($labs)){ ?>
<option value="<?php echo $l["id_laboratorio"]; ?>"
<?php if($l["id_laboratorio"] == $pc["id_laboratorio"]) echo "selected"; ?>>
    <?php echo $l["nombre"]; ?>
</option>
<?php } ?>

</select>

<br><br>

<label>Número PC</label>
<br>
<input type="number" name="numero_pc" value="<?php echo $pc["numero_pc"]; ?>">

<br><br>

<label>Estado</label>
<br>
<select name="estado">
    <option value="Alta" <?php if($pc["estado"]=="Alta") echo "selected"; ?>>Alta</option>
    <option value="Baja" <?php if($pc["estado"]=="Baja") echo "selected"; ?>>Baja</option>
</select>

<br><br>

<button type="submit">Guardar cambios</button>

</form>