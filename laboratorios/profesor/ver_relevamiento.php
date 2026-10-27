<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";
require_once "../includes/relevamientos_vistas.php";

$id = rel_entero($_GET["id"] ?? "");
$ok = $_GET["ok"] ?? "";

$titulo = "Relevamiento";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<?php if ($ok === "creado") { ?>
    <div class="alerta alerta-exito" role="status">
        Relevamiento registrado correctamente. Gracias por completarlo.
    </div>
<?php } elseif ($ok === "repetido") { ?>
    <div class="alerta alerta-info" role="status">
        Este relevamiento ya estaba registrado. No se creó uno duplicado.
    </div>
<?php } ?>

<?php
/* El profesor solo puede abrir SUS relevamientos (el filtro es por usuario de la sesión) */
rel_vista_detalle($conexion, $id, 2, (int)$_SESSION["id_usuario"]);
?>

<div class="barra-acciones separada">
    <a class="btn btn-exito" href="relevamiento.php">+ Nuevo relevamiento</a>
    <a class="btn btn-secundario" href="mis_relevamientos.php">Ver mis relevamientos</a>
</div>

<?php include "../includes/pie.php"; ?>
