<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_GET["id"];

$sql = "SELECT * FROM usuarios WHERE id_usuario = $id";
$resultado = mysqli_query($conexion, $sql);

$usuario = mysqli_fetch_assoc($resultado);

// Módulos disponibles
$modulos = mysqli_query($conexion,"SELECT * FROM modulos ORDER BY id_modulo");

// Módulos que ya tiene asignados
$asignados = array();

$resAsignados = mysqli_query($conexion,"SELECT id_modulo FROM usuarios_modulos WHERE id_usuario = $id");

while($fila = mysqli_fetch_assoc($resAsignados)){

    $asignados[] = $fila["id_modulo"];

}
?>
<?php include("../includes/menu_admin.php"); ?>
<h1>Editar Usuario</h1>

<form action="actualizar_usuario.php" method="POST">

    <input type="hidden" name="id_usuario" value="<?php echo $usuario["id_usuario"]; ?>">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?php echo $usuario["nombre"]; ?>" required><br><br>

    <label>Apellido:</label><br>
    <input type="text" name="apellido" value="<?php echo $usuario["apellido"]; ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo $usuario["email"]; ?>" required><br><br>

    <label>Contraseña:</label><br>
    <input type="text" name="contrasena" value="<?php echo $usuario["contrasena"]; ?>" required><br><br>

    <label>Rol:</label><br>
    <select name="id_rol">
        <option value="1" <?php if($usuario["id_rol"]==1) echo "selected"; ?>>Administrativo</option>
        <option value="2" <?php if($usuario["id_rol"]==2) echo "selected"; ?>>Profesor</option>
        <option value="3" <?php if($usuario["id_rol"]==3) echo "selected"; ?>>EMATP</option>
    </select><br><br>

    <label>Módulos a los que accede:</label><br>

    <?php while($m = mysqli_fetch_assoc($modulos)){ ?>

    <input type="checkbox" name="modulos[]" value="<?php echo $m["id_modulo"]; ?>"
    <?php if(in_array($m["id_modulo"], $asignados)) echo "checked"; ?>>
    <?php echo $m["nombre"]; ?>
    <br>

    <?php } ?>

    <br>

    <button type="submit">Actualizar</button>

</form>

<br>
<a href="usuarios.php">Volver</a>
