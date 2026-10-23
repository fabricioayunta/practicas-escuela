<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

/* Laboratorios */
$laboratorios = mysqli_query($conexion, "SELECT id_laboratorio, nombre FROM laboratorios ORDER BY nombre");

/* Computadoras agrupadas por laboratorio (para completar el segundo desplegable) */
$pcsPorLaboratorio = [];

$resultadoPC = mysqli_query(
    $conexion,
    "SELECT id_laboratorio, numero_pc, estado
     FROM computadoras
     ORDER BY id_laboratorio, numero_pc"
);

while ($pc = mysqli_fetch_assoc($resultadoPC)) {
    $pcsPorLaboratorio[$pc["id_laboratorio"]][] = [
        "numero" => (int)$pc["numero_pc"],
        "estado" => $pc["estado"],
    ];
}

$titulo = "Crear ticket";
$menu   = "profesor";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Crear un ticket</h1>
    <p>Complete los pasos para avisar que una computadora tiene un problema.</p>
</div>

<div class="tarjeta">

    <form class="formulario" action="guardar_ticket.php" method="POST" enctype="multipart/form-data">

        <h2 style="margin-top:0;">1. ¿Dónde está la computadora?</h2>

        <div class="campo">
            <label for="id_laboratorio">Laboratorio</label>
            <select id="id_laboratorio" name="id_laboratorio" required>
                <option value="">Seleccione un laboratorio</option>
                <?php while ($lab = mysqli_fetch_assoc($laboratorios)) { ?>
                    <option value="<?php echo e($lab["id_laboratorio"]); ?>">
                        <?php echo e($lab["nombre"]); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="campo">
            <label for="numero_pc">Número de computadora</label>
            <select id="numero_pc" name="numero_pc" required disabled>
                <option value="">Primero elija el laboratorio</option>
            </select>
        </div>

        <h2>2. ¿Qué parte tiene el problema?</h2>
        <p class="ayuda">Marque una o más opciones.</p>

        <?php casillas_componentes(); ?>

        <h2>3. Cuéntenos qué pasa</h2>

        <div class="campo">
            <label for="observacion">Descripción del problema</label>
            <textarea
                id="observacion"
                name="observacion"
                placeholder="Por ejemplo: el monitor no enciende desde ayer."
                required></textarea>
        </div>

        <div class="campo">
            <label for="foto">Foto del problema (opcional)</label>
            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
            <small>Formatos permitidos: JPG, PNG o WEBP. Tamaño máximo: 5 MB.</small>
        </div>

        <div class="acciones-form">
            <button type="submit" class="btn-exito">Enviar ticket</button>
            <a class="btn btn-secundario" href="inicio.php">Cancelar</a>
        </div>

    </form>

</div>

<script>
    var pcsPorLaboratorio = <?php echo json_encode($pcsPorLaboratorio, JSON_HEX_TAG | JSON_HEX_AMP); ?>;

    var selectLab = document.getElementById("id_laboratorio");
    var selectPC  = document.getElementById("numero_pc");

    selectLab.addEventListener("change", function () {

        var lista = pcsPorLaboratorio[selectLab.value] || [];

        selectPC.innerHTML = "";

        if (selectLab.value === "" || lista.length === 0) {
            selectPC.disabled = true;
            selectPC.innerHTML = '<option value="">' +
                (selectLab.value === "" ? "Primero elija el laboratorio" : "Este laboratorio no tiene computadoras") +
                '</option>';
            return;
        }

        selectPC.disabled = false;
        selectPC.innerHTML = '<option value="">Seleccione una computadora</option>';

        lista.forEach(function (pc) {
            var opcion = document.createElement("option");
            opcion.value = pc.numero;
            opcion.textContent = "PC " + pc.numero + (pc.estado === "Baja" ? " (fuera de servicio)" : "");
            selectPC.appendChild(opcion);
        });
    });
</script>

<?php include "../includes/pie.php"; ?>
