<?php

session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");


/* =========================
   DATOS DEL PROFESOR
========================= */

$id_usuario = $_SESSION["id_usuario"];


/* =========================
   DATOS DEL FORMULARIO
========================= */

$id_laboratorio = $_POST["id_laboratorio"] ?? "";
$numero_pc = $_POST["numero_pc"] ?? "";
$observacion = trim($_POST["observacion"] ?? "");

$componentes = $_POST["componentes"] ?? [];


/* =========================
   VALIDAR DATOS
========================= */

if (
    $id_laboratorio == "" ||
    $numero_pc == "" ||
    $observacion == "" ||
    empty($componentes)
) {

    echo "Debe seleccionar laboratorio, computadora, al menos un componente y escribir una observación.";
    exit();

}


/* =========================
   COMPONENTES PERMITIDOS
========================= */

$componentesPermitidos = [
    "Mother",
    "Procesador",
    "Memoria RAM",
    "Disco",
    "Monitor",
    "Teclado",
    "Mouse"
];


$componentesValidos = [];


foreach ($componentes as $componente) {

    if (in_array($componente, $componentesPermitidos)) {

        $componentesValidos[] = $componente;

    }

}


if (empty($componentesValidos)) {

    echo "Debe seleccionar al menos un componente.";
    exit();

}


/* =========================
   CONVERTIR COMPONENTES A TEXTO
========================= */

$componentesTexto = implode(", ", $componentesValidos);


/* =========================
   TÍTULO AUTOMÁTICO
========================= */

$titulo = "Problema en: " . $componentesTexto;


/* =========================
   FOTO
========================= */

$fotoNombre = null;


if (
    isset($_FILES["foto"]) &&
    $_FILES["foto"]["error"] != UPLOAD_ERR_NO_FILE
) {

    if ($_FILES["foto"]["error"] != UPLOAD_ERR_OK) {

        echo "Hubo un error al subir la foto.";
        exit();

    }


    /* Máximo 5 MB */

    if ($_FILES["foto"]["size"] > 5 * 1024 * 1024) {

        echo "La foto no puede superar los 5 MB.";
        exit();

    }


    /* Comprobar que realmente sea una imagen */

    $tipoImagen = getimagesize(
        $_FILES["foto"]["tmp_name"]
    );


    if ($tipoImagen === false) {

        echo "El archivo seleccionado no es una imagen válida.";
        exit();

    }


    /* Tipos permitidos */

    $tiposPermitidos = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];


    if (!isset($tiposPermitidos[$tipoImagen["mime"]])) {

        echo "Solo se permiten imágenes JPG, PNG o WEBP.";
        exit();

    }


    /* Extensión */

    $extension = $tiposPermitidos[$tipoImagen["mime"]];


    /* Nombre único */

    $fotoNombre = uniqid("ticket_", true) . "." . $extension;


    /* Carpeta */

    $carpeta = "../uploads/tickets/";


    /* Crear carpeta si no existe */

    if (!is_dir($carpeta)) {

        mkdir($carpeta, 0755, true);

    }


    /* Guardar foto */

    if (!move_uploaded_file(
        $_FILES["foto"]["tmp_name"],
        $carpeta . $fotoNombre
    )) {

        echo "No se pudo guardar la foto.";
        exit();

    }

}


/* =========================
   BUSCAR COMPUTADORA
========================= */

$sqlComputadora = "
    SELECT id_computadora

    FROM computadoras

    WHERE id_laboratorio = ?
    AND numero_pc = ?

    LIMIT 1
";


$stmt = mysqli_prepare(
    $conexion,
    $sqlComputadora
);


mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $id_laboratorio,
    $numero_pc
);


mysqli_stmt_execute($stmt);


$resultado = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($resultado) != 1) {

    echo "No se encontró la computadora seleccionada.";
    exit();

}


$computadora = mysqli_fetch_assoc($resultado);

$id_computadora = $computadora["id_computadora"];


/* =========================
   INSERTAR TICKET
========================= */

$sql = "
    INSERT INTO tickets
    (
        id_usuario,
        id_computadora,
        titulo,
        descripcion,
        componentes_afectados,
        foto
    )

    VALUES
    (?, ?, ?, ?, ?, ?)
";


$stmt = mysqli_prepare(
    $conexion,
    $sql
);


/*
    6 variables:

    i = id_usuario
    i = id_computadora
    s = titulo
    s = observacion
    s = componentes
    s = foto
*/

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


/* =========================
   GUARDAR
========================= */

if (mysqli_stmt_execute($stmt)) {

    header("Location: mis_tickets.php");
    exit();

} else {

    echo "Error al crear el ticket: " . mysqli_error($conexion);

}

?>