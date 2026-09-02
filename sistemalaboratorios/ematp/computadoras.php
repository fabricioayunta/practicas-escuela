<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

// Obtener laboratorios
$sqlLabs = "SELECT * FROM laboratorios ORDER BY nombre";
$laboratorios = mysqli_query($conexion, $sqlLabs);

// Si eligió un laboratorio
$computadoras = null;

if(isset($_GET["laboratorio"])){

    $idLaboratorio = $_GET["laboratorio"];

    $sqlPC = "SELECT *
              FROM computadoras
              WHERE id_laboratorio = '$idLaboratorio'
              ORDER BY numero_pc";

    $computadoras = mysqli_query($conexion,$sqlPC);

}
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<link rel="stylesheet" href="../css/estilos.css">
<title>Computadoras</title>

</head>

<body>
<?php include("../includes/menu_ematp.php"); ?>
<h1>Gestión de Computadoras</h1>

<a href="../inicio.php">← Volver al Panel</a>

<hr>

<form method="GET">

<label>Seleccione un laboratorio</label>

<br><br>

<select name="laboratorio" required>

<option value="">Seleccione...</option>

<?php while($lab=mysqli_fetch_assoc($laboratorios)){ ?>

<option
value="<?php echo $lab["id_laboratorio"]; ?>"

<?php

if(isset($_GET["laboratorio"])){

if($_GET["laboratorio"]==$lab["id_laboratorio"]) echo "selected";

}

?>

>

<?php echo $lab["nombre"]; ?>

</option>

<?php } ?>

</select>

<button type="submit">

Buscar

</button>

</form>

<br>

<?php

if($computadoras){

?>

<table border="1" cellpadding="10">

<tr>

<th>PC</th>

<th>Acciones</th>

</tr>

<?php

while($pc=mysqli_fetch_assoc($computadoras)){

?>

<tr>

<td>

PC <?php echo $pc["numero_pc"]; ?>

</td>

<td>

<a href="ver_computadora.php?id=<?php echo $pc["id_computadora"]; ?>">

Ver Componentes

</a>

</td>

</tr>

<?php

}

?>

</table>

<?php

}

?>

</body>
</html>