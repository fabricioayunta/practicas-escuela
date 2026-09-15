```php
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

/* Obtener y validar datos */
$id_lab = filter_input(INPUT_POST, "id_laboratorio", FILTER_VALIDATE_INT);
$numero = filter_input(INPUT_POST, "numero_pc", FILTER_VALIDATE_INT);

/* Validar datos */
if ($id_lab === false || $id_lab === null || $id_lab <= 0) {
    exit("Laboratorio inválido.");
}

if ($numero === false || $numero === null || $numero <= 0) {
    exit("Número de PC inválido.");
}

/* Preparar consulta */
$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO computadoras (id_laboratorio, numero_pc, estado)
     VALUES (?, ?, 'Alta')"
);

if (!$stmt) {
    exit("Error al preparar la consulta.");
}

/* Vincular parámetros */
mysqli_stmt_bind_param($stmt, "ii", $id_lab, $numero);

/* Ejecutar */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: computadoras.php");
    exit();
}

/* Error */
mysqli_stmt_close($stmt);
exit("Error al guardar la computadora.");
?>
```
