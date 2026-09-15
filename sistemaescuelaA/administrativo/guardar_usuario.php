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
   CONEXIÓN A LA BASE DE DATOS
   ============================== */

require_once "../conexion/conexion.php";


/* ==============================
   RECIBIR Y LIMPIAR DATOS
   ============================== */

$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$email = trim($_POST["email"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";
$id_rol = (int)($_POST["id_rol"] ?? 0);


/* ==============================
   VALIDACIONES
   ============================== */

if ($nombre === "" || $apellido === "") {
    exit("Nombre y apellido son obligatorios.");
}

if (mb_strlen($nombre) > 50 || mb_strlen($apellido) > 50) {
    exit("El nombre o apellido es demasiado largo.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("El email no es válido.");
}

if (mb_strlen($email) > 100) {
    exit("El email es demasiado largo.");
}

if (strlen($contrasena) < 8) {
    exit("La contraseña debe tener al menos 8 caracteres.");
}

if (!in_array($id_rol, [1, 2, 3], true)) {
    exit("El rol seleccionado no es válido.");
}


/* ==============================
   VERIFICAR QUE EL EMAIL NO EXISTA
   ============================== */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_usuario
     FROM usuarios
     WHERE email = ?
     LIMIT 1"
);

if (!$stmt) {
    error_log(mysqli_error($conexion));
    exit("No se pudo procesar la solicitud.");
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    exit("El email ya está registrado.");
}

mysqli_stmt_close($stmt);


/* ==============================
   GENERAR HASH DE CONTRASEÑA
   ============================== */

$hash = password_hash($contrasena, PASSWORD_DEFAULT);

if ($hash === false) {
    exit("No se pudo procesar la contraseña.");
}


/* ==============================
   INSERTAR USUARIO
   ============================== */

$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO usuarios
        (nombre, apellido, email, contrasena, id_rol)
     VALUES
        (?, ?, ?, ?, ?)"
);

if (!$stmt) {
    error_log(mysqli_error($conexion));
    exit("No se pudo crear el usuario.");
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssi",
    $nombre,
    $apellido,
    $email,
    $hash,
    $id_rol
);


/* ==============================
   EJECUTAR
   ============================== */

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header("Location: usuarios.php");
    exit();
}


/* ==============================
   ERROR
   ============================== */

error_log(mysqli_stmt_error($stmt));

mysqli_stmt_close($stmt);

exit("No se pudo crear el usuario.");

?>