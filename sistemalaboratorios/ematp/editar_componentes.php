<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$id = (int)($_GET["id"] ?? 0);

/* Computadora */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT
        computadoras.*,
        laboratorios.nombre AS laboratorio
     FROM computadoras
     INNER JOIN laboratorios
        ON computadoras.id_laboratorio = laboratorios.id_laboratorio
     WHERE computadoras.id_computadora = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$pc = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$pc) {
    mostrar_error("No se encontró la computadora.", "computadoras.php");
}

/* Componentes actuales (si todavía no hay ninguno, los campos quedan vacíos) */

$stmt = mysqli_prepare($conexion, "SELECT * FROM componentes WHERE id_computadora = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$componentes = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: [];
mysqli_stmt_close($stmt);

$campos = [
    "mother"      => "Mother",
    "procesador"  => "Procesador",
    "memoria_ram" => "Memoria RAM",
    "disco"       => "Disco",
    "monitor"     => "Monitor",
    "teclado"     => "Teclado",
    "mouse"       => "Mouse",
];

$titulo = "Editar componentes";
$menu   = "ematp";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Editar componentes</h1>
    <p><?php echo e($pc["laboratorio"]); ?> · PC <?php echo e($pc["numero_pc"]); ?></p>
</div>

<div class="tarjeta">

    <form class="formulario" action="guardar_componentes.php" method="POST">

        <input type="hidden" name="id_computadora" value="<?php echo (int)$id; ?>">

        <?php foreach ($campos as $campo => $etiqueta) { ?>
            <div class="campo">
                <label for="<?php echo e($campo); ?>"><?php echo e($etiqueta); ?></label>
                <input type="text" id="<?php echo e($campo); ?>" name="<?php echo e($campo); ?>"
                       maxlength="100" value="<?php echo e($componentes[$campo] ?? ""); ?>">
            </div>
        <?php } ?>

        <div class="campo">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" maxlength="255"><?php echo e($componentes["observaciones"] ?? ""); ?></textarea>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Guardar componentes</button>
            <a class="btn btn-secundario" href="ver_computadora.php?id=<?php echo (int)$id; ?>">Cancelar</a>
        </div>

    </form>

</div>

<?php include "../includes/pie.php"; ?>
