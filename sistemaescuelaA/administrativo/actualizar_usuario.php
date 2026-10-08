<?php

session_start();

/* ==============================
   VERIFICAR SESIÓN Y ROL
   ============================== */

if (
    !isset($_SESSION["id_usuario"]) ||
    !isset($_SESSION["id_rol"]) ||
    $_SESSION["id_rol"] != 1
) {
    header("Location: ../index.php");
    exit();
}


/* ==============================
   CONEXIÓN
   ============================== */

require_once "../conexion/conexion.php";


/* ==============================
   VERIFICAR TOKEN DE SEGURIDAD
   ============================== */

if (
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"] ?? "")
) {
    mostrar_error("El formulario venció. Vuelva a abrir la edición del usuario e intente de nuevo.");
}


/* ==============================
   RECIBIR DATOS
   ============================== */

$id = (int)($_POST["id_usuario"] ?? 0);

$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$email = trim($_POST["email"] ?? "");

$contrasena = $_POST["contrasena"] ?? "";

$id_rol = (int)($_POST["id_rol"] ?? 0);


/* ==============================
   VALIDACIONES
   ============================== */

if ($id <= 0) {
    mostrar_error("Usuario inválido.");
}

if ($nombre === "" || $apellido === "") {
    mostrar_error("Nombre y apellido son obligatorios.");
}

if (
    mb_strlen($nombre) > 50 ||
    mb_strlen($apellido) > 50
) {
    mostrar_error("El nombre o apellido es demasiado largo.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    mostrar_error("El email no es válido.");
}

if (mb_strlen($email) > 100) {
    mostrar_error("El email es demasiado largo.");
}

if (!in_array($id_rol, [1, 2, 3], true)) {
    mostrar_error("El rol seleccionado no es válido.");
}

/* Evitar que el administrador se quite a sí mismo el acceso */
if ($id === (int)$_SESSION["id_usuario"] && $id_rol !== 1) {
    mostrar_error("No puede quitarse a sí mismo el rol de administrador.");
}


/* ==============================
   COMPROBAR QUE EL USUARIO EXISTA
   ============================== */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_usuario
     FROM usuarios
     WHERE id_usuario = ?
     LIMIT 1"
);

if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudo procesar la solicitud.");
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) !== 1) {
    mysqli_stmt_close($stmt);
    mostrar_error("El usuario no existe.");
}

mysqli_stmt_close($stmt);


/* ==============================
   COMPROBAR EMAIL DUPLICADO
   ============================== */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_usuario
     FROM usuarios
     WHERE email = ?
     AND id_usuario != ?
     LIMIT 1"
);

if (!$stmt) {
    error_log(mysqli_error($conexion));
    mostrar_error("No se pudo procesar la solicitud.");
}

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $email,
    $id
);

mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    mostrar_error("El email ya está registrado.");
}

mysqli_stmt_close($stmt);


/* ==============================
   ACTUALIZAR SIN CAMBIAR CONTRASEÑA
   ============================== */

if ($contrasena === "") {

    $stmt = mysqli_prepare(
        $conexion,
        "UPDATE usuarios
         SET nombre = ?,
             apellido = ?,
             email = ?,
             id_rol = ?
         WHERE id_usuario = ?"
    );

    if (!$stmt) {
        error_log(mysqli_error($conexion));
        mostrar_error("No se pudo actualizar el usuario.");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssii",
        $nombre,
        $apellido,
        $email,
        $id_rol,
        $id
    );

} else {

    /* ==============================
       VALIDAR NUEVA CONTRASEÑA
       ============================== */

    if (strlen($contrasena) < 8) {
        mostrar_error("La contraseña debe tener al menos 8 caracteres.");
    }

    /* ==============================
       GENERAR HASH
       ============================== */

    $hash = password_hash(
        $contrasena,
        PASSWORD_DEFAULT
    );

    if ($hash === false) {
        mostrar_error("No se pudo procesar la contraseña.");
    }


    /* ==============================
       ACTUALIZAR CON NUEVA CONTRASEÑA
       ============================== */

    $stmt = mysqli_prepare(
        $conexion,
        "UPDATE usuarios
         SET nombre = ?,
             apellido = ?,
             email = ?,
             contrasena = ?,
             id_rol = ?
         WHERE id_usuario = ?"
    );

    if (!$stmt) {
        error_log(mysqli_error($conexion));
        mostrar_error("No se pudo actualizar el usuario.");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssssii",
        $nombre,
        $apellido,
        $email,
        $hash,
        $id_rol,
        $id
    );
}


/* ==============================
   EJECUTAR
   ============================== */

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header("Location: usuarios.php?ok=editado");
    exit();
}


/* ==============================
   ERROR
   ============================== */

error_log(mysqli_stmt_error($stmt));

mysqli_stmt_close($stmt);

mostrar_error("No se pudo actualizar el usuario.");

?>