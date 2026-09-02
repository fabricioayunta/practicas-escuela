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
   GENERAR TOKEN CSRF
   ============================== */

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}


/* ==============================
   OBTENER ID
   ============================== */

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    exit("ID inválido.");
}


/* ==============================
   BUSCAR USUARIO
   ============================== */

$stmt = mysqli_prepare(
    $conexion,
    "SELECT id_usuario, nombre, apellido, email, id_rol
     FROM usuarios
     WHERE id_usuario = ?
     LIMIT 1"
);

if (!$stmt) {
    error_log(mysqli_error($conexion));
    exit("No se pudo procesar la solicitud.");
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

if (!$usuario) {
    exit("Usuario no encontrado.");
}

?>

<?php include("../includes/menu_admin.php"); ?>

<h1>Editar Usuario</h1>

<form action="actualizar_usuario.php" method="POST">

    <!-- ID DEL USUARIO -->

    <input
        type="hidden"
        name="id_usuario"
        value="<?php echo (int)$usuario["id_usuario"]; ?>"
    >


    <!-- PROTECCIÓN CSRF -->

    <input
        type="hidden"
        name="csrf_token"
        value="<?php echo htmlspecialchars(
            $_SESSION["csrf_token"],
            ENT_QUOTES,
            "UTF-8"
        ); ?>"
    >


    <!-- NOMBRE -->

    <label for="nombre">Nombre:</label><br>

    <input
        type="text"
        id="nombre"
        name="nombre"
        maxlength="50"
        value="<?php echo htmlspecialchars(
            $usuario["nombre"] ?? "",
            ENT_QUOTES,
            "UTF-8"
        ); ?>"
        required
    >

    <br><br>


    <!-- APELLIDO -->

    <label for="apellido">Apellido:</label><br>

    <input
        type="text"
        id="apellido"
        name="apellido"
        maxlength="50"
        value="<?php echo htmlspecialchars(
            $usuario["apellido"] ?? "",
            ENT_QUOTES,
            "UTF-8"
        ); ?>"
        required
    >

    <br><br>


    <!-- EMAIL -->

    <label for="email">Email:</label><br>

    <input
        type="email"
        id="email"
        name="email"
        maxlength="100"
        value="<?php echo htmlspecialchars(
            $usuario["email"] ?? "",
            ENT_QUOTES,
            "UTF-8"
        ); ?>"
        required
    >

    <br><br>


    <!-- NUEVA CONTRASEÑA -->

    <label for="contrasena">
        Nueva contraseña:
    </label><br>

    <input
        type="password"
        id="contrasena"
        name="contrasena"
        minlength="8"
        maxlength="255"
        autocomplete="new-password"
    >

    <br>

    <small>
        Dejá este campo vacío si querés mantener la contraseña actual.
    </small>

    <br><br>


    <!-- ROL -->

    <label for="id_rol">Rol:</label><br>

    <select name="id_rol" id="id_rol" required>

        <option
            value="1"
            <?php
            if ((int)$usuario["id_rol"] === 1) {
                echo "selected";
            }
            ?>
        >
            Administrativo
        </option>

        <option
            value="2"
            <?php
            if ((int)$usuario["id_rol"] === 2) {
                echo "selected";
            }
            ?>
        >
            Profesor
        </option>

        <option
            value="3"
            <?php
            if ((int)$usuario["id_rol"] === 3) {
                echo "selected";
            }
            ?>
        >
            EMATP
        </option>

    </select>

    <br><br>


    <!-- BOTÓN -->

    <button type="submit">
        Actualizar
    </button>

</form>

<br>

<a href="usuarios.php">
    Volver
</a>