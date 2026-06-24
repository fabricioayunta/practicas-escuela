<?php

session_start();

include("conexion/conexion.php");

$email = $_POST["email"];
$contrasena = $_POST["password"];

$sql = "SELECT * FROM usuarios
        WHERE email = '$email'
        AND contrasena = '$contrasena'";

$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) == 1) {

    $usuario = mysqli_fetch_assoc($resultado);

    $_SESSION["id_usuario"] = $usuario["id_usuario"];
    $_SESSION["nombre"] = $usuario["nombre"];
    $_SESSION["id_rol"] = $usuario["id_rol"];

    if ($usuario["id_rol"] == 1) {
        header("Location: administrativo/inicio.php");
        exit();
    }

    if ($usuario["id_rol"] == 2) {
        header("Location: profesor/inicio.php");
        exit();
    }

    if ($usuario["id_rol"] == 3) {
        header("Location: ematp/inicio.php");
        exit();
    }

} else {
    echo "Email o contraseña incorrectos.";
}

?>