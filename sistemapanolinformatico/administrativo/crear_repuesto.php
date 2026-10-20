<?php
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || ($_SESSION["pi_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Nuevo repuesto";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Nuevo repuesto</h1>
    <p>Registra un repuesto y la cantidad que hay en el pañol.</p>
</div>

<div class="tarjeta">

    <form class="formulario" action="guardar_repuesto.php" method="POST">

        <input type="hidden" name="csrf_token" value="<?php echo e(token_formulario("pi_csrf_token")); ?>">

        <div class="campo">
            <label for="nombre">Nombre del repuesto</label>
            <input type="text" id="nombre" name="nombre" maxlength="100" required>
        </div>

        <div class="campo">
            <label for="descripcion">Descripción</label>
            <input type="text" id="descripcion" name="descripcion" maxlength="255">
            <small>Opcional.</small>
        </div>

        <div class="campo">
            <label for="cantidad">Cantidad disponible</label>
            <input type="number" id="cantidad" name="cantidad" min="0" max="9999" value="0" required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar repuesto</button>
            <a class="btn btn-secundario" href="repuestos.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
