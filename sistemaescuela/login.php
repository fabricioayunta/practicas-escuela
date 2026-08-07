<?php

session_start();

include("conexion/conexion.php");


/* =========================
   RECIBIR DATOS DEL FORMULARIO
========================= */

$email = trim($_POST["email"] ?? "");
$contrasena = trim($_POST["password"] ?? "");


/* =========================
   VALIDAR DATOS
========================= */

if ($email == "" || $contrasena == "") {
    header("Location: index.php");
    exit();
}


/* =========================
   SANITIZAR EMAIL
========================= */

$email = filter_var($email, FILTER_SANITIZE_EMAIL);


/* =========================
   CONSULTAR USUARIO
========================= */

$sql = "SELECT *
        FROM usuarios
        WHERE email = ?
        LIMIT 1";

$stmt = mysqli_prepare($conexion, $sql);

mysqli_stmt_bind_param($stmt, "s", $email);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);


/* =========================
   COMPROBAR USUARIO
========================= */

if (mysqli_num_rows($resultado) == 1) {

    $usuario = mysqli_fetch_assoc($resultado);


    /* =========================
       COMPROBAR CONTRASEÑA
    ========================= */

    if ($contrasena === $usuario["contrasena"]) {

        /* Crear sesión */

        $_SESSION["id_usuario"] = $usuario["id_usuario"];

        $_SESSION["nombre"] = $usuario["nombre"];

        $_SESSION["apellido"] = $usuario["apellido"];

        $_SESSION["email"] = $usuario["email"];

        $_SESSION["id_rol"] = $usuario["id_rol"];


        /* Ir al único inicio */

        header("Location: inicio.php");
        exit();

    }

}


/* =========================
   LOGIN INCORRECTO
========================= */

header("Location: index.php?error=1");
exit();

?>