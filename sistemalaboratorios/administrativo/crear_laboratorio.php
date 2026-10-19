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

$titulo = "Nuevo laboratorio";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Nuevo laboratorio</h1>
    <p>Registra un laboratorio con un identificador corto y fácil de reconocer.</p>
</div>

<div class="tarjeta tarjeta-laboratorio">

    <form class="formulario formulario-laboratorio" action="guardar_laboratorio.php" method="POST">

        <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION["csrf_token"]); ?>">

        <div class="campo campo-nombre-laboratorio">
            <label for="nombre">Nombre del laboratorio</label>
            <input type="text" id="nombre" name="nombre" maxlength="18"
                   pattern="[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9]{1,18}"
                   oninput="let letras = 0, numeros = 0; this.value = Array.from(this.value).filter(caracter => { if (/[0-9]/.test(caracter)) return ++numeros <= 2; if (/[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]/.test(caracter)) return ++letras <= 16; return true; }).slice(0, 18).join('')"
                   title="Use hasta 16 letras y 2 números."
                   required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar laboratorio</button>
            <a class="btn btn-secundario" href="laboratorios.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
