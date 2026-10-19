<?php
session_start();

require_once __DIR__ . "/includes/funciones.php";

/* Si ya inició sesión, lo llevamos directamente a su panel */
if (isset($_SESSION["id_usuario"], $_SESSION["id_rol"])) {
    header("Location: inicio.php");
    exit();
}

$mensajes = [
    "campos"   => "Por favor, complete el email y la contraseña.",
    "email"    => "El email no tiene un formato válido. Ejemplo: nombre@gmail.com",
    "datos"    => "El email o la contraseña no son correctos. Revise los datos e intente de nuevo.",
    "rol"      => "Su usuario no tiene un rol válido. Consulte con el administrador.",
    "sesion"   => "Para continuar, inicie sesión.",
];

$error = $_GET["error"] ?? "";

$base   = "";
$menu   = "";
$titulo = "Iniciar sesión";

include __DIR__ . "/includes/cabecera.php";
?>

<div class="login">

    <div class="login-marca">
        <h1>Mesa de Ayuda Informática</h1>
        <p>Inventario de computadoras</p>
    </div>

    <div class="tarjeta">

        <h2 style="margin-top:0;">Iniciar sesión</h2>

        <?php if (isset($mensajes[$error])) { ?>
            <div class="alerta <?php echo $error === "sesion" ? "alerta-info" : "alerta-error"; ?>">
                <?php echo e($mensajes[$error]); ?>
            </div>
        <?php } ?>

        <form action="login.php" method="POST">

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" autocomplete="username" autofocus required>
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>

            <button type="submit" class="btn-primario">Ingresar</button>

        </form>

    </div>

</div>

<?php include __DIR__ . "/includes/pie.php"; ?>
