<?php
session_start();

require_once __DIR__ . "/../sistemalaboratorios/includes/funciones.php";

/* Si ya inició sesión en este módulo, lo llevamos directamente a su panel */
if (isset($_SESSION["pi_id_usuario"], $_SESSION["pi_id_rol"])) {
    header("Location: inicio.php");
    exit();
}

$mensajes = [
    "campos"   => "Por favor, complete el email y la contraseña.",
    "email"    => "El email no tiene un formato válido. Ejemplo: nombre@gmail.com",
    "datos"    => "El email o la contraseña no son correctos. Revise los datos e intente de nuevo.",
    "rol"      => "Su usuario no tiene un rol válido. Consulte con el administrador.",
    "acceso"   => "Su usuario no tiene acceso al módulo Pañol Informático. Consulte con el administrador.",
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
        <h1>🔌 Pañol Informático</h1>
        <p>Préstamos de insumos y stock de repuestos</p>
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

    <p style="text-align:center;">
        <a href="../index.php">← Volver al selector de módulos</a>
    </p>

</div>

<?php include __DIR__ . "/includes/pie.php"; ?>
