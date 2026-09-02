<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_GET["id"];

// Obtener la computadora
$sqlPC = "SELECT
            computadoras.*,
            laboratorios.nombre AS laboratorio
          FROM computadoras
          INNER JOIN laboratorios
          ON computadoras.id_laboratorio = laboratorios.id_laboratorio
          WHERE computadoras.id_computadora = '$id'";

$resultadoPC = mysqli_query($conexion, $sqlPC);
$pc = mysqli_fetch_assoc($resultadoPC);

// Obtener componentes
$sql = "SELECT * FROM componentes
        WHERE id_computadora = '$id'";

$resultado = mysqli_query($conexion, $sql);
$componentes = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">

<head>
<link rel="stylesheet" href="../css/estilos.css">
<meta charset="UTF-8">

<title>Editar Componentes</title>

</head>

<body>
<?php include("../includes/menu_ematp.php"); ?>
<h1>Editar Componentes</h1>

<p>

<strong>Laboratorio:</strong>

<?php echo $pc["laboratorio"]; ?>

</p>

<p>

<strong>PC:</strong>

<?php echo $pc["numero_pc"]; ?>

</p>

<hr>

<form action="guardar_componentes.php" method="POST">

<input
type="hidden"
name="id_computadora"
value="<?php echo $id; ?>">

<label>Mother</label>

<br>

<input
type="text"
name="mother"
value="<?php echo $componentes["mother"]; ?>">

<br><br>

<label>Procesador</label>

<br>

<input
type="text"
name="procesador"
value="<?php echo $componentes["procesador"]; ?>">

<br><br>

<label>Memoria RAM</label>

<br>

<input
type="text"
name="memoria_ram"
value="<?php echo $componentes["memoria_ram"]; ?>">

<br><br>

<label>Disco</label>

<br>

<input
type="text"
name="disco"
value="<?php echo $componentes["disco"]; ?>">

<br><br>

<label>Monitor</label>

<br>

<input
type="text"
name="monitor"
value="<?php echo $componentes["monitor"]; ?>">

<br><br>

<label>Teclado</label>

<br>

<input
type="text"
name="teclado"
value="<?php echo $componentes["teclado"]; ?>">

<br><br>

<label>Mouse</label>

<br>

<input
type="text"
name="mouse"
value="<?php echo $componentes["mouse"]; ?>">

<br><br>

<label>Observaciones</label>

<br>

<textarea
name="observaciones"
rows="5"
cols="60"><?php echo $componentes["observaciones"]; ?></textarea>

<br><br>

<button type="submit">

Guardar Componentes

</button>

</form>

<br>

<a href="ver_computadora.php?id=<?php echo $id; ?>">

← Volver

</a>

</body>

</html>