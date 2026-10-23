<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";
require_once "../includes/funciones_panol.php";

$articulos = articulos_disponibles($conexion);

/* Artículo elegido desde el listado (opcional) */
$idElegido = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT) ?: 0;

$titulo = "Pedir un artículo";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Pedir un artículo</h1>
    <p>El préstamo queda registrado a su nombre: <?php echo e($_SESSION["panol_nombre"] . " " . $_SESSION["panol_apellido"]); ?>.</p>
</div>

<?php if (count($articulos) === 0) { ?>
    <div class="alerta alerta-info">
        No hay artículos disponibles para prestar en este momento.
    </div>
<?php } ?>

<div class="tarjeta">

    <form class="formulario" action="guardar_prestamo.php" method="POST">

        <input type="hidden" name="csrf_token" value="<?php echo e(token_formulario("panol_csrf_token")); ?>">

        <div class="campo">
            <label for="id_articulo">Artículo</label>
            <select id="id_articulo" name="id_articulo" required>
                <option value="">Seleccionar</option>
                <?php foreach ($articulos as $a) { ?>
                    <option value="<?php echo e($a["id_articulo"]); ?>"
                        <?php echo (int)$a["id_articulo"] === $idElegido ? "selected" : ""; ?>>
                        <?php echo e($a["nombre"]); ?> (disponibles: <?php echo e($a["disponibles"]); ?>)
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="campo">
            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" min="1" value="1" required>
        </div>

        <div class="campo">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" rows="3" maxlength="255"></textarea>
            <small>Opcional. Por ejemplo, para qué clase lo necesita.</small>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Pedir artículo</button>
            <a class="btn btn-secundario" href="articulos.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
