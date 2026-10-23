<?php
session_start();

require_once __DIR__ . "/conexion/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$email      = trim($_POST["email"] ?? "");
$contrasena = $_POST["password"] ?? "";

if ($email === "" || $contrasena === "") {
    header("Location: index.php?error=campos");
    exit();
}

if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    header("Location: index.php?error=email");
    exit();
}


/* Buscar usuario */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_usuario, nombre, apellido, email, contrasena, id_rol
     FROM usuarios
     WHERE email = ?
     LIMIT 1"
);

if (!$stmt) {
    mostrar_error("No se pudo consultar la base de datos. Intente nuevamente.", "index.php");
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$usuario   = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);


/*
   Mismo mensaje si el usuario no existe o si la contraseña es incorrecta
   (así no se revela qué emails están registrados).
*/

if (!$usuario || !password_verify($contrasena, $usuario["contrasena"])) {
    header("Location: index.php?error=datos");
    exit();
}


/* Comprobar que tenga acceso a este módulo (2 = Máquinas de Impresión 3D) */

if (!tiene_acceso($conexion, (int)$usuario["id_usuario"], 2)) {
    header("Location: index.php?error=acceso");
    exit();
}


/* Crear sesión de este módulo (con sus propias claves) */

session_regenerate_id(true);

$_SESSION["pi_id_usuario"] = (int)$usuario["id_usuario"];
$_SESSION["pi_nombre"]     = $usuario["nombre"];
$_SESSION["pi_apellido"]   = $usuario["apellido"];
$_SESSION["pi_email"]      = $usuario["email"];
$_SESSION["pi_id_rol"]     = (int)$usuario["id_rol"];


/* Redirección según el rol */

switch ($_SESSION["pi_id_rol"]) {

    case 1:
        header("Location: administrativo/inicio.php");
        break;

    case 2:
        header("Location: profesor/inicio.php");
        break;

    case 3:
        header("Location: ematp/inicio.php");
        break;

    default:
        unset(
            $_SESSION["pi_id_usuario"],
            $_SESSION["pi_nombre"],
            $_SESSION["pi_apellido"],
            $_SESSION["pi_email"],
            $_SESSION["pi_id_rol"]
        );
        header("Location: index.php?error=rol");
}

exit();
