<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$titulo = "Nuevo laboratorio";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Nuevo laboratorio</h1>
</div>

<div class="tarjeta">

    <form class="formulario" action="guardar_laboratorio.php" method="POST">

        <div class="campo">
            <label for="nombre">Nombre del laboratorio</label>
            <input type="text" id="nombre" name="nombre" maxlength="50" required>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar laboratorio</button>
            <a class="btn btn-secundario" href="laboratorios.php">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
