<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 1) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

$resultado = mysqli_query(
    $conexion,
    "SELECT
        usuarios.id_usuario,
        usuarios.nombre,
        usuarios.apellido,
        usuarios.email,
        roles.nombre AS rol
     FROM usuarios
     LEFT JOIN roles ON usuarios.id_rol = roles.id_rol
     ORDER BY usuarios.apellido, usuarios.nombre"
);

$ok = $_GET["ok"] ?? "";

$titulo = "Usuarios";
$menu   = "admin";

include "../includes/cabecera.php";
?>

<div class="encabezado-pagina">
    <h1>Usuarios</h1>
    <p>Personas que pueden ingresar al sistema.</p>
</div>

<?php if ($ok === "creado") { ?>
    <div class="alerta alerta-exito">El usuario se creó correctamente.</div>
<?php } elseif ($ok === "editado") { ?>
    <div class="alerta alerta-exito">El usuario se actualizó correctamente.</div>
<?php } elseif ($ok === "eliminado") { ?>
    <div class="alerta alerta-exito">El usuario se eliminó correctamente.</div>
<?php } ?>

<div class="barra-acciones">
    <a class="btn btn-exito" href="crear_usuario.php">+ Crear usuario</a>
    <a class="btn btn-secundario" href="inicio.php">← Volver al inicio</a>
</div>

<div class="tabla-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo e($fila["nombre"]); ?></td>
                <td><?php echo e($fila["apellido"]); ?></td>
                <td><?php echo e($fila["email"]); ?></td>
                <td><?php echo e($fila["rol"] ?? "—"); ?></td>
                <td>
                    <div class="acciones">

                        <a class="btn btn-chico" href="editar_usuario.php?id=<?php echo e($fila["id_usuario"]); ?>">Editar</a>

                        <?php if ((int)$fila["id_usuario"] !== (int)$_SESSION["id_usuario"]) { ?>
                            <a class="btn btn-peligro btn-chico"
                               href="eliminar_usuario.php?id=<?php echo e($fila["id_usuario"]); ?>"
                               onclick="return confirm('¿Seguro que desea eliminar a este usuario?');">Eliminar</a>
                        <?php } ?>

                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>
</div>

<?php include "../includes/pie.php"; ?>
