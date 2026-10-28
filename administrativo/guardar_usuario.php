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
   CONEXIÓN A LA BASE DE DATOS
   ============================== */

require_once "../conexion/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: crear_usuario.php");
    exit();
}

if (!token_valido("pi_csrf_token")) {
    mostrar_error("El formulario venció. Vuelva a abrir la creación del usuario e intente de nuevo.");
}


/* ==============================
   RECIBIR Y LIMPIAR DATOS
   ============================== */

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

if ($nombre === "" || $apellido === "") {
    mostrar_error("Nombre y apellido son obligatorios.");
}

if (
    strpbrk($nombre . $apellido, "\"'") !== false ||
    mb_strpos($nombre . $apellido, "´") !== false
) {
    mostrar_error("El nombre y apellido no pueden contener comillas.");
}

if (mb_strlen($nombre) > 50 || mb_strlen($apellido) > 50) {
    mostrar_error("El nombre o apellido es demasiado largo.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    mostrar_error("El email no es válido.");
}

if (mb_strlen($email) > 100) {
    mostrar_error("El email es demasiado largo.");
}

if (strlen($contrasena) < 8) {
    mostrar_error("La contraseña debe tener al menos 8 caracteres.");
}

if (!array_key_exists($id_rol, roles_sistema())) {
    mostrar_error("El rol seleccionado no es válido.");
}

if (count($modulos) === 0) {
    mostrar_error("Marque al menos un módulo al que pueda entrar el usuario.");
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
    mostrar_error("No se pudo procesar la solicitud.");
}

mysqli_stmt_bind_param($stmt, "s", $email);
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
   GENERAR HASH DE CONTRASEÑA
   ============================== */

$hash = password_hash($contrasena, PASSWORD_DEFAULT);

if ($hash === false) {
    mostrar_error("No se pudo procesar la contraseña.");
}


/* ==============================
   INSERTAR USUARIO Y SUS MÓDULOS
   ============================== */

mysqli_begin_transaction($conexion);

$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO usuarios
        (nombre, apellido, email, contrasena, id_rol)
     VALUES
        (?, ?, ?, ?, ?)"
);

if (!$stmt) {
    error_log(mysqli_error($conexion));
    mysqli_rollback($conexion);
    mostrar_error("No se pudo crear el usuario.");
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

if (!mysqli_stmt_execute($stmt)) {
    error_log(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
    mysqli_rollback($conexion);
    mostrar_error("No se pudo crear el usuario.");
}

$id_nuevo = (int)mysqli_insert_id($conexion);
mysqli_stmt_close($stmt);

if (!guardar_modulos_usuario($conexion, $id_nuevo, $modulos)) {
    error_log(mysqli_error($conexion));
    mysqli_rollback($conexion);
    mostrar_error("No se pudieron guardar los módulos del usuario.");
}

mysqli_commit($conexion);

header("Location: usuarios.php?ok=creado");
exit();

?>
