<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$modulos = mysqli_query($conexion,"SELECT * FROM modulos ORDER BY id_modulo");
?>
<?php include("../includes/menu_admin.php"); ?>
<h1>Crear Usuario</h1>

<form action="guardar_usuario.php" method="POST">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Apellido:</label><br>
    <input type="text" name="apellido" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña:</label><br>
    <input type="text" name="contrasena" required><br><br>

    <label>Rol:</label><br>
    <select name="id_rol" required>
        <option value="1">Administrativo</option>
        <option value="2">Profesor</option>
        <option value="3">EMATP</option>
    </select><br><br>

    <label>Módulos a los que accede:</label><br>

    <?php while($m = mysqli_fetch_assoc($modulos)){ ?>

    <input type="checkbox" name="modulos[]" value="<?php echo $m["id_modulo"]; ?>">
    <?php echo $m["nombre"]; ?>
    <br>

    <?php } ?>

    <br>

    <button type="submit">Crear usuario</button>

</form>

<br>
<a href="usuarios.php">Volver</a>
