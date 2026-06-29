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

// Buscar el id de la computadora seleccionada
$sqlComputadora = "SELECT id_computadora
                   FROM computadoras
                   WHERE id_laboratorio = '$id_laboratorio'
                   AND numero_pc = '$numero_pc'";

$resultado = mysqli_query($conexion, $sqlComputadora);

if (mysqli_num_rows($resultado) == 1) {

    $computadora = mysqli_fetch_assoc($resultado);
    $id_computadora = $computadora["id_computadora"];

    // Guardar el ticket
    $sql = "INSERT INTO tickets
            (id_usuario, id_computadora, titulo, descripcion)
            VALUES
            ('$id_usuario', '$id_computadora', '$titulo', '$descripcion')";

    if (mysqli_query($conexion, $sql)) {
        header("Location: mis_tickets.php");
        exit();
    } else {
        echo "Error al crear el ticket.";
    }

} else {

    echo "No se encontró la computadora seleccionada.";

}
?>