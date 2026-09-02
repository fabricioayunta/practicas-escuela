<?php

session_start();

require_once __DIR__ . "/conexion/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$email = trim($_POST["email"] ?? "");
$contrasena = $_POST["password"] ?? "";

if ($email === "" || $contrasena === "") {
    header("Location: index.php?error=campos");
    exit();
}

$email = filter_var($email, FILTER_VALIDATE_EMAIL);

if ($email === false) {
    header("Location: index.php?error=email");
    exit();
}


/* Buscar usuario */

$sql = "SELECT id_usuario, nombre, apellido, email, contrasena, id_rol
        FROM usuarios
        WHERE email = ?
        LIMIT 1";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    die("ERROR SQL: " . mysqli_error($conexion));
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);


/* Usuario no encontrado */

if (mysqli_num_rows($resultado) !== 1) {
    die("ERROR: El usuario no existe.");
}

$usuario = mysqli_fetch_assoc($resultado);


/* Comprobar contraseña */

if (!password_verify($contrasena, $usuario["contrasena"])) {
    die("ERROR: Contraseña incorrecta.");
}


/* Crear sesión */

session_regenerate_id(true);

$_SESSION["id_usuario"] = (int)$usuario["id_usuario"];
$_SESSION["nombre"] = $usuario["nombre"];
$_SESSION["apellido"] = $usuario["apellido"];
$_SESSION["email"] = $usuario["email"];
$_SESSION["id_rol"] = (int)$usuario["id_rol"];


/* Mostrar datos para comprobar */

echo "<h1>LOGIN CORRECTO</h1>";

echo "<p>Usuario: " . htmlspecialchars($_SESSION["nombre"]) . "</p>";
echo "<p>Rol: " . $_SESSION["id_rol"] . "</p>";

echo "<p>Sesión creada correctamente.</p>";

echo "<p>Redirigiendo al panel...</p>";

/* Redirección */

if ($_SESSION["id_rol"] === 1) {

    header("Refresh: 2; url=administrativo/dashboard.php");

} elseif ($_SESSION["id_rol"] === 2) {

    header("Refresh: 2; url=profesor/inicio.php");

} elseif ($_SESSION["id_rol"] === 3) {

    header("Refresh: 2; url=ematp/inicio.php");

} else {

    session_unset();
    session_destroy();

    die("ERROR: El rol no es válido.");
}

exit();

?>