<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$filtro = " WHERE 1=1 ";
$parametros = []; $tipos = "";
if(isset($_GET["estado"]) && $_GET["estado"]!==""){ $estado=$_GET["estado"]; if(in_array($estado,["Abierto","Pendiente","Cerrado"],true)){ $filtro .= " AND tickets.estado=?"; $parametros[]=$estado; $tipos.="s"; } }
if(isset($_GET["buscar"]) && $_GET["buscar"]!==""){ $buscar=trim($_GET["buscar"]); $filtro .= " AND (usuarios.nombre LIKE ? OR usuarios.apellido LIKE ?)"; $like="%".$buscar."%"; $parametros[]=$like; $parametros[]=$like; $tipos.="ss"; }
$sql = "SELECT tickets.id_ticket,tickets.titulo,tickets.estado,tickets.fecha_creacion,usuarios.nombre,usuarios.apellido,laboratorios.nombre AS laboratorio,computadoras.numero_pc FROM tickets INNER JOIN usuarios ON tickets.id_usuario=usuarios.id_usuario INNER JOIN computadoras ON tickets.id_computadora=computadoras.id_computadora INNER JOIN laboratorios ON computadoras.id_laboratorio=laboratorios.id_laboratorio $filtro ORDER BY tickets.fecha_creacion DESC";
$stmt=mysqli_prepare($conexion,$sql);
if($tipos!==""){ mysqli_stmt_bind_param($stmt,$tipos,...$parametros); }
mysqli_stmt_execute($stmt); $resultado=mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tickets</title>

    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>

<?php include("../includes/menu_admin.php"); ?>
<a href="inicio.php">← Volver</a>

<br><br>
<hr>

<form method="GET">

<label>Filtrar por estado:</label>

<select name="estado">

<option value="">Todos</option>

<option value="Abierto"
<?php if(isset($_GET["estado"]) && $_GET["estado"]=="Abierto") echo "selected"; ?>>
Abiertos
</option>

<option value="Pendiente"
<?php if(isset($_GET["estado"]) && $_GET["estado"]=="Pendiente") echo "selected"; ?>>
Pendientes
</option>

<option value="Cerrado"
<?php if(isset($_GET["estado"]) && $_GET["estado"]=="Cerrado") echo "selected"; ?>>
Cerrados
</option>
</select>
<br><br>

<label>Profesor</label>

<input
type="text"
name="buscar"
placeholder="Nombre o apellido">
<button type="submit">
🔍 Buscar
</button>

</form>

<br>
<table border="1" cellpadding="10">

<tr>

<th>ID</th>

<th>Profesor</th>

<th>Laboratorio</th>

<th>PC</th>

<th>Título</th>

<th>Estado</th>

<th>Fecha</th>

<th>Acciones</th>

</tr>

<?php while($fila=mysqli_fetch_assoc($resultado)){ ?>

<tr>

<td><?php echo e($fila["id_ticket"]); ?></td>

<td>
<?php
echo e($fila["nombre"])." ".e($fila["apellido"]);
?>
</td>

<td><?php echo e($fila["laboratorio"]); ?></td>

<td>PC <?php echo e($fila["numero_pc"]); ?></td>

<td><?php echo e($fila["titulo"]); ?></td>

<td>

<?php

$clase = "";

if($fila["estado"]=="Abierto"){
    $clase="abierto";
}
elseif($fila["estado"]=="Pendiente"){
    $clase="pendiente";
}
else{
    $clase="cerrado";
}

?>

<span class="estado <?php echo $clase; ?>">
<?php echo e($fila["estado"]); ?>
</span>

</td>

<td>
<?php echo date("d/m/Y H:i",strtotime($fila["fecha_creacion"])); ?>
</td>

<td>

<a
class="btn btn-azul"
href="ver_ticket.php?id=<?php echo e($fila["id_ticket"]); ?>">
👁 Ver
</a>
</td>

</tr>

<?php } ?>

</table>
</body>
</html>

