<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_POST["id_usuario"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$email = $_POST["email"];
$contrasena = $_POST["contrasena"];
$id_rol = $_POST["id_rol"];
$modulos = $_POST["modulos"] ?? array();

$sql = "UPDATE usuarios SET
        nombre='$nombre',
        apellido='$apellido',
        email='$email',
        contrasena='$contrasena',
        id_rol='$id_rol'
        WHERE id_usuario=$id";

if (mysqli_query($conexion, $sql)) {

    // Volver a cargar los módulos a los que accede el usuario
    mysqli_query($conexion, "DELETE FROM usuarios_modulos WHERE id_usuario=$id");

    foreach ($modulos as $id_modulo) {

        $sqlModulo = "INSERT INTO usuarios_modulos (id_usuario, id_modulo)
                      VALUES ('$id', '$id_modulo')";

        mysqli_query($conexion, $sqlModulo);

    }

    header("Location: usuarios.php");
    exit();
} else {
    echo "Error al actualizar usuario";
}
?>
