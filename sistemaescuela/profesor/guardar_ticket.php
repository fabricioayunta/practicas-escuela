<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id_usuario = $_SESSION["id_usuario"];
$titulo = $_POST["titulo"];
$descripcion = $_POST["descripcion"];
$id_laboratorio = $_POST["id_laboratorio"];
$numero_pc = $_POST["numero_pc"];

/* ==========================
   SUBIR FOTO (OPCIONAL)
========================== */

$nombreFoto = NULL;

if(isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0){

    $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));

    $permitidas = array("jpg","jpeg","png","webp");

    if(in_array($ext,$permitidas)){

        $nombreFoto = uniqid().".".$ext;

        move_uploaded_file(
            $_FILES["foto"]["tmp_name"],
            "../uploads/".$nombreFoto
        );

    }

}

/* ==========================
   BUSCAR COMPUTADORA
========================== */

$sqlComputadora = "SELECT id_computadora
                   FROM computadoras
                   WHERE id_laboratorio='$id_laboratorio'
                   AND numero_pc='$numero_pc'";

$resultado = mysqli_query($conexion,$sqlComputadora);

if(mysqli_num_rows($resultado)==1){

    $computadora = mysqli_fetch_assoc($resultado);

    $id_computadora = $computadora["id_computadora"];

    $sql = "INSERT INTO tickets
    (
        id_usuario,
        id_computadora,
        titulo,
        descripcion,
        foto
    )
    VALUES
    (
        '$id_usuario',
        '$id_computadora',
        '$titulo',
        '$descripcion',
        ".($nombreFoto ? "'$nombreFoto'" : "NULL")."
    )";

    if(mysqli_query($conexion,$sql)){

        header("Location: mis_tickets.php");
        exit();

    }else{

        echo "Error al crear el ticket.";

    }

}else{

    echo "No se encontró la computadora.";

}
?>