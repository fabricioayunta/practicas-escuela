<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 2) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: crear_ticket.php");
    exit();
}

$id_usuario = (int)$_SESSION["id_usuario"];


/* =========================
   DATOS DEL FORMULARIO
========================= */

$id_laboratorio = (int)($_POST["id_laboratorio"] ?? 0);
$numero_pc      = (int)($_POST["numero_pc"] ?? 0);
$observacion    = trim($_POST["observacion"] ?? "");
$componentes    = filtrar_componentes($_POST["componentes"] ?? []);

if ($id_laboratorio <= 0 || $numero_pc <= 0) {
    mostrar_error("Debe elegir el laboratorio y el número de computadora.", "crear_ticket.php");
}

if (empty($componentes)) {
    mostrar_error("Debe marcar al menos un componente con problemas.", "crear_ticket.php");
}

if ($observacion === "") {
    mostrar_error("Debe escribir una descripción del problema.", "crear_ticket.php");
}

$componentesTexto = implode(", ", $componentes);
$titulo           = "Problema en: " . $componentesTexto;


/* =========================
   BUSCAR COMPUTADORA
========================= */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_computadora
     FROM computadoras
     WHERE id_laboratorio = ? AND numero_pc = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "ii", $id_laboratorio, $numero_pc);
mysqli_stmt_execute($stmt);

$computadora = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$computadora) {
    mostrar_error("No se encontró la computadora seleccionada.", "crear_ticket.php");
}

$id_computadora = (int)$computadora["id_computadora"];


/* =========================
   FOTO (OPCIONAL)
========================= */

$fotoNombre = null;

if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] != UPLOAD_ERR_NO_FILE) {

    if ($_FILES["foto"]["error"] != UPLOAD_ERR_OK) {
        mostrar_error("Hubo un error al subir la foto. Pruebe con una foto más chica.", "crear_ticket.php");
    }

    if ($_FILES["foto"]["size"] > 5 * 1024 * 1024) {
        mostrar_error("La foto no puede superar los 5 MB.", "crear_ticket.php");
    }

    $tipoImagen = getimagesize($_FILES["foto"]["tmp_name"]);

    if ($tipoImagen === false) {
        mostrar_error("El archivo seleccionado no es una imagen válida.", "crear_ticket.php");
    }

    $tiposPermitidos = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];

    if (!isset($tiposPermitidos[$tipoImagen["mime"]])) {
        mostrar_error("Solo se permiten imágenes JPG, PNG o WEBP.", "crear_ticket.php");
    }

    $extension  = $tiposPermitidos[$tipoImagen["mime"]];
    $fotoNombre = uniqid("ticket_", true) . "." . $extension;
    $carpeta    = "../uploads/tickets/";

    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0755, true);
    }

    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $carpeta . $fotoNombre)) {
        mostrar_error("No se pudo guardar la foto en el servidor.", "crear_ticket.php");
    }
}


/* =========================
   GUARDAR EL TICKET
========================= */

mysqli_begin_transaction($conexion);

$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO tickets
        (id_usuario, id_computadora, titulo, descripcion, componentes_afectados, foto)
     VALUES (?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "iissss",
    $id_usuario,
    $id_computadora,
    $titulo,
    $observacion,
    $componentesTexto,
    $fotoNombre
);

if (!mysqli_stmt_execute($stmt)) {

    $detalle = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    mysqli_rollback($conexion);

    /* Si no se pudo guardar el ticket, se borra la foto ya subida */
    if ($fotoNombre !== null) {
        @unlink("../uploads/tickets/" . $fotoNombre);
    }

    error_log("Error al crear ticket: " . $detalle);
    mostrar_error("No se pudo crear el ticket. Si el problema continúa, avise al administrador.", "crear_ticket.php");
}

$id_ticket = mysqli_insert_id($conexion);
mysqli_stmt_close($stmt);


/* Primer registro del historial del ticket */

$estadoInicial = "Abierto";
$textoInicial  = "Ticket creado.";

$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO historialticket (id_ticket, estado, fecha, observacion, id_usuario)
     VALUES (?, ?, NOW(), ?, ?)"
);

mysqli_stmt_bind_param($stmt, "issi", $id_ticket, $estadoInicial, $textoInicial, $id_usuario);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_commit($conexion);

header("Location: mis_tickets.php?ok=creado");
exit();
