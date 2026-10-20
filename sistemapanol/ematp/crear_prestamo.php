<?php
session_start();

if (!isset($_SESSION["panol_id_usuario"]) || ($_SESSION["panol_id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";
require_once "../includes/funciones_panol.php";

/* Solo se pueden prestar los artículos de alta que tienen unidades disponibles */
$articulos = articulos_disponibles($conexion);

$titulo = "Nuevo préstamo";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Nuevo préstamo</h1>
    <p>Solo aparecen los artículos dados de alta que tienen unidades disponibles.</p>
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
                    <option value="<?php echo e($a["id_articulo"]); ?>">
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
            <label for="persona">Prestado a</label>
            <input type="text" id="persona" name="persona" maxlength="100" required>
            <small>Nombre y apellido de quien se lleva el artículo.</small>
        </div>

        <div class="campo">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" rows="3" maxlength="255"></textarea>
            <small>Opcional.</small>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar préstamo</button>
            <a class="btn btn-secundario" href="prestamos.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
