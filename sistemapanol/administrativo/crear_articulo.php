<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Nuevo artículo";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Nuevo artículo</h1>
    <p>Se crea dado de alta, listo para prestar.</p>
</div>

<div class="tarjeta">

    <form class="formulario" action="guardar_articulo.php" method="POST">

        <input type="hidden" name="csrf_token" value="<?php echo e(token_formulario("panol_csrf_token")); ?>">

        <div class="campo">
            <label for="nombre">Nombre del artículo</label>
            <input type="text" id="nombre" name="nombre" maxlength="100" required>
        </div>

        <div class="campo">
            <label for="descripcion">Descripción</label>
            <input type="text" id="descripcion" name="descripcion" maxlength="255">
            <small>Opcional. Por ejemplo, dónde se usa o qué incluye.</small>
        </div>

        <div class="campo">
            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" min="1" max="9999" value="1" required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar artículo</button>
            <a class="btn btn-secundario" href="articulos.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
