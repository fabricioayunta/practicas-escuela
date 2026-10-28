<?php

session_start();

/* ==============================
   VERIFICAR SESIÓN Y ROL
   ============================== */

if (
    !isset($_SESSION["pi_id_usuario"]) ||
    !isset($_SESSION["pi_id_rol"]) ||
    $_SESSION["pi_id_rol"] != 1
) {
    header("Location: ../index.php");
    exit();
}


/* ==============================
   CONEXIÓN
   ============================== */

require_once "../conexion/conexion.php";

/* Módulo en el que estamos (2 = Máquinas de Impresión 3D) */
$moduloActual = 2;


/* ==============================
   VERIFICAR TOKEN DE SEGURIDAD
   ============================== */

if (!token_valido("pi_csrf_token")) {
    mostrar_error("El formulario venció. Vuelva a abrir la edición del usuario e intente de nuevo.");
}


/* ==============================
   RECIBIR DATOS
   ============================== */

$id = (int)($_POST["id_usuario"] ?? 0);

$nombre = $_POST["nombre"] ?? null;
$apellido = $_POST["apellido"] ?? null;
if (!is_string($nombre) || !is_string($apellido)) {
    mostrar_error("El nombre y apellido no son válidos.");
}
$nombre = trim($nombre);
$apellido = trim($apellido);
$email = trim($_POST["email"] ?? "");

$contrasena = $_POST["contrasena"] ?? "";

$id_rol = (int)($_POST["id_rol"] ?? 0);

$modulos = filtrar_modulos($_POST["modulos"] ?? []);


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
    strpbrk($nombre . $apellido, "\"'") !== false ||
    mb_strpos($nombre . $apellido, "´") !== false
) {
    mostrar_error("El nombre y apellido no pueden contener comillas.");
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

if (!array_key_exists($id_rol, roles_sistema())) {
    mostrar_error("El rol seleccionado no es válido.");
}

if (count($modulos) === 0) {
    mostrar_error("Marque al menos un módulo al que pueda entrar el usuario.");
}

if ($contrasena !== "" && strlen($contrasena) < 8) {
    mostrar_error("La contraseña debe tener al menos 8 caracteres.");
}

/* Evitar que el administrador se quite a sí mismo el acceso */
if ($id === (int)$_SESSION["pi_id_usuario"] && $id_rol !== 1) {
    mostrar_error("No puede quitarse a sí mismo el rol de administrador.");
}

if ($id === (int)$_SESSION["pi_id_usuario"] && !in_array($moduloActual, $modulos, true)) {
    mostrar_error("No puede quitarse a sí mismo el acceso a este módulo.");
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
if (!mysqli_stmt_execute($stmt) || !mysqli_stmt_store_result($stmt)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudo validar el usuario en la base de datos.");
}

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

if (!mysqli_stmt_execute($stmt) || !mysqli_stmt_store_result($stmt)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mostrar_error("No se pudo validar el email en la base de datos.");
}

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    mostrar_error("El email ya está registrado.");
}

mysqli_stmt_close($stmt);


/* ==============================
   PREPARAR LA ACTUALIZACIÓN
   ============================== */

if ($contrasena === "") {

    /* Sin cambiar la contraseña */

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

    /* Con nueva contraseña */

    $hash = password_hash(
        $contrasena,
        PASSWORD_DEFAULT
    );

    if ($hash === false) {
        mostrar_error("No se pudo procesar la contraseña.");
    }

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
   EJECUTAR (USUARIO + MÓDULOS)
   ============================== */

mysqli_begin_transaction($conexion);

if (!mysqli_stmt_execute($stmt)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mysqli_rollback($conexion);
    mostrar_error("No se pudo actualizar el usuario.");
}

mysqli_stmt_close($stmt);

if (!guardar_modulos_usuario($conexion, $id, $modulos)) {
    error_log(mysqli_error($conexion));
    mysqli_rollback($conexion);
    mostrar_error("No se pudieron guardar los módulos del usuario.");
}

mysqli_commit($conexion);

header("Location: usuarios.php?ok=editado");
exit();

?>
