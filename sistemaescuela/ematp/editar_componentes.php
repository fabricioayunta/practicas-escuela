<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = (int)($_GET["id"] ?? 0);

// Obtener la computadora
$sqlPC = "SELECT
            computadoras.*,
            laboratorios.nombre AS laboratorio
          FROM computadoras
          INNER JOIN laboratorios
          ON computadoras.id_laboratorio = laboratorios.id_laboratorio
          WHERE computadoras.id_computadora = ?";

$stmt = mysqli_prepare($conexion, $sqlPC);
mysqli_stmt_bind_param($stmt, "i", $id); mysqli_stmt_execute($stmt);
$pc = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Obtener componentes
$sql = "SELECT * FROM componentes
        WHERE id_computadora = ?";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id); mysqli_stmt_execute($stmt);
$componentes = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
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

<?php echo e($pc["laboratorio"]); ?>

</p>

<p>

<strong>PC:</strong>

<?php echo e($pc["numero_pc"]); ?>

</p>

<hr>

<form action="guardar_componentes.php" method="POST">

<input
type="hidden"
name="id_computadora"
value="<?php echo (int)$id; ?>">

<label>Mother</label>

<br>

<input
type="text"
name="mother"
value="<?php echo e($componentes["mother"]); ?>">

<br><br>

<label>Procesador</label>

<br>

<input
type="text"
name="procesador"
value="<?php echo e($componentes["procesador"]); ?>">

<br><br>

<label>Memoria RAM</label>

<br>

<input
type="text"
name="memoria_ram"
value="<?php echo e($componentes["memoria_ram"]); ?>">

<br><br>

<label>Disco</label>

<br>

<input
type="text"
name="disco"
value="<?php echo e($componentes["disco"]); ?>">

<br><br>

<label>Monitor</label>

<br>

<input
type="text"
name="monitor"
value="<?php echo e($componentes["monitor"]); ?>">

<br><br>

<label>Teclado</label>

<br>

<input
type="text"
name="teclado"
value="<?php echo e($componentes["teclado"]); ?>">

<br><br>

<label>Mouse</label>

<br>

<input
type="text"
name="mouse"
value="<?php echo e($componentes["mouse"]); ?>">

<br><br>

<label>Observaciones</label>

<br>

<textarea
name="observaciones"
rows="5"
cols="60"><?php echo e($componentes["observaciones"]); ?></textarea>

<br><br>

<button type="submit">

Guardar Componentes

</button>

</form>

<br>

<a href="ver_computadora.php?id=<?php echo (int)$id; ?>">

← Volver

</a>

</body>

</html>