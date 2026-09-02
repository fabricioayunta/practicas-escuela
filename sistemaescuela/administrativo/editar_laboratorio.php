<?php

session_start();

/* Verificar sesión y rol de administrador */
if (
    !isset($_SESSION["id_usuario"]) ||
    !isset($_SESSION["id_rol"]) ||
    (int)$_SESSION["id_rol"] !== 1
) {
    header("Location: ../index.php");
    exit();
}

/* Conexión a la base de datos */
require_once("../conexion/conexion.php");

/* Obtener y validar ID */
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id <= 0) {
    exit("ID de laboratorio inválido.");
}

/* Buscar laboratorio */
$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_laboratorio, nombre
     FROM laboratorios
     WHERE id_laboratorio = ?"
);

if (!$stmt) {
    exit("Error al preparar la consulta.");
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    exit("Error al consultar el laboratorio.");
}

$resultado = mysqli_stmt_get_result($stmt);
$laboratorio = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

/* Verificar existencia */
if (!$laboratorio) {
    exit("Laboratorio no encontrado.");
}

/* Sanitizar datos para mostrar en HTML */
$idLaboratorio = (int)$laboratorio["id_laboratorio"];
$nombreLaboratorio = htmlspecialchars(
    $laboratorio["nombre"] ?? "",
    ENT_QUOTES,
    "UTF-8"
);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estilos.css">
    <title>Editar Laboratorio</title>
</head>

<body>

<?php include("../includes/menu_admin.php"); ?>

<h1>Editar Laboratorio</h1>

<a href="laboratorios.php">← Volver</a>

<hr>

<form action="actualizar_laboratorio.php" method="POST">

    <input
        type="hidden"
        name="id_laboratorio"
        value="<?php echo $idLaboratorio; ?>"
    >

    <label for="nombre">Nombre</label>

    <br><br>

    <input
        type="text"
        id="nombre"
        name="nombre"
        value="<?php echo $nombreLaboratorio; ?>"
        maxlength="100"
        required
        autocomplete="off"
    >

    <br><br>

    <button type="submit">Guardar Cambios</button>

</form>

</body>
</html>