<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_GET["id"];

// Obtener datos de la computadora (incluye el campo 'estado')
$sql = "SELECT
            computadoras.*,
            laboratorios.nombre AS laboratorio
        FROM computadoras
        INNER JOIN laboratorios
            ON computadoras.id_laboratorio = laboratorios.id_laboratorio
        WHERE computadoras.id_computadora = '$id'";

$resultado = mysqli_query($conexion, $sql);
$pc = mysqli_fetch_assoc($resultado);

// Obtener componentes
$sqlComponentes = "SELECT *
                   FROM componentes
                   WHERE id_computadora = '$id'";

$resultadoComponentes = mysqli_query($conexion, $sqlComponentes);
$componentes = mysqli_fetch_assoc($resultadoComponentes);

// Historial de modificaciones
$sqlHistorial = "SELECT
                    historial_computadoras.*,
                    usuarios.nombre,
                    usuarios.apellido
                FROM historial_computadoras
                INNER JOIN usuarios
                ON historial_computadoras.id_usuario = usuarios.id_usuario
                WHERE id_computadora='$id'
                ORDER BY fecha DESC";

$historial = mysqli_query($conexion,$sqlHistorial);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../css/estilos.css">
    <meta charset="UTF-8">
    <title>Computadora</title>
</head>
<body>
<?php include("../includes/menu_ematp.php"); ?>
<h1>Computadora PC <?php echo $pc["numero_pc"]; ?></h1>

<a href="computadoras.php?laboratorio=<?php echo $pc["id_laboratorio"]; ?>">
← Volver
</a>

<hr>

<h3>Información</h3>

<p><strong>Laboratorio:</strong> <?php echo $pc["laboratorio"]; ?></p>
<p><strong>PC:</strong> <?php echo $pc["numero_pc"]; ?></p>

<!-- SECCIÓN DE ESTADO -->
<p><strong>Estado Actual:</strong>

<?php echo ($pc["estado"] == "Alta") ? "🟢 Operativa" : "🔴 Fuera de servicio"; ?>

</p>



<?php if ($pc["estado"] == "Alta") { ?>
    <!-- Mostrar botón de BAJA si está operativa -->
    <form action="cambiar_estado.php" method="POST">
        <input type="hidden" name="id_computadora" value="<?php echo $id; ?>">
      <input type="hidden" name="nuevo_estado" value="Baja">
        <textarea name="motivo" required placeholder="Motivo de la baja..."></textarea><br>
        <button type="submit">Dar de Baja</button>
    </form>
<?php } else { ?>
    <!-- Mostrar botón de ALTA si está de baja -->
    <form action="cambiar_estado.php" method="POST">
        <input type="hidden" name="id_computadora" value="<?php echo $id; ?>">
       <input type="hidden" name="nuevo_estado" value="Alta">
        <button type="submit">Dar de Alta</button>
    </form>
<?php } ?>
<!-- FIN DE LA NUEVA SECCIÓN -->

<hr>

<h3>Componentes</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Componente</th>
    <th>Detalle</th>
</tr>
<tr>
    <td>Mother</td>
    <td><?php echo $componentes["mother"]; ?></td>
</tr>
<tr>
    <td>Procesador</td>
    <td><?php echo $componentes["procesador"]; ?></td>
</tr>
<tr>
    <td>Memoria RAM</td>
    <td><?php echo $componentes["memoria_ram"]; ?></td>
</tr>
<tr>
    <td>Disco</td>
    <td><?php echo $componentes["disco"]; ?></td>
</tr>
<tr>
    <td>Monitor</td>
    <td><?php echo $componentes["monitor"]; ?></td>
</tr>
<tr>
    <td>Teclado</td>
    <td><?php echo $componentes["teclado"]; ?></td>
</tr>
<tr>
    <td>Mouse</td>
    <td><?php echo $componentes["mouse"]; ?></td>
</tr>
<tr>
    <td>Observaciones</td>
    <td><?php echo $componentes["observaciones"]; ?></td>
</tr>
</table>

<br>

<a href="editar_componentes.php?id=<?php echo $pc["id_computadora"]; ?>">
<button>Editar Componentes</button>
</a>
<hr>

<h2>Historial de la Computadora</h2>

<table border="1" cellpadding="10">
<tr>
<th>Fecha</th>
<th>EMATP</th>
<th>Acción</th>
</tr>

<?php while($fila=mysqli_fetch_assoc($historial)){ ?>
<tr>
<td>
<?php echo date("d/m/Y H:i",strtotime($fila["fecha"])); ?>
</td>
<td>
<?php echo $fila["nombre"]." ".$fila["apellido"]; ?>
</td>
<td>
<!-- nl2br permite que los motivos con saltos de línea se vean bien -->
<?php echo nl2br($fila["accion"]); ?>
</td>
</tr>
<?php } ?>
</table>

</body>
</html>