<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

if (
    !isset($_SESSION["csrf_token"]) ||
    !is_string($_SESSION["csrf_token"]) ||
    strlen($_SESSION["csrf_token"]) !== 64
) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    mostrar_error("ID de laboratorio inválido.", "laboratorios.php");
}

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_laboratorio, nombre FROM laboratorios WHERE id_laboratorio = ?"
);
if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudo cargar el laboratorio.", "laboratorios.php");
}

if (!mysqli_stmt_bind_param($stmt, "i", $id)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudo cargar el laboratorio.", "laboratorios.php");
}

if (!mysqli_stmt_execute($stmt)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudo cargar el laboratorio.", "laboratorios.php");
}

$resultado = mysqli_stmt_get_result($stmt);
if (!$resultado) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudo cargar el laboratorio.", "laboratorios.php");
}

$laboratorio = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if (!$laboratorio) {
    mostrar_error("No se encontró el laboratorio.", "laboratorios.php");
}

$titulo = "Editar laboratorio";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Editar laboratorio</h1>
</div>

<div class="tarjeta">

    <form class="formulario" action="actualizar_laboratorio.php" method="POST">

        <input type="hidden" name="id_laboratorio" value="<?php echo (int)$laboratorio["id_laboratorio"]; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION["csrf_token"]); ?>">

        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="18"
                   value="<?php echo e($laboratorio["nombre"]); ?>" autocomplete="off"
                   pattern="[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9]{1,18}"
                   oninput="let letras = 0, numeros = 0; this.value = Array.from(this.value).filter(caracter => { if (/[0-9]/.test(caracter)) return ++numeros <= 2; if (/[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]/.test(caracter)) return ++letras <= 16; return true; }).slice(0, 18).join('')"
                   title="Use hasta 16 letras y 2 números."
                   required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar cambios</button>
            <a class="btn btn-secundario" href="laboratorios.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
