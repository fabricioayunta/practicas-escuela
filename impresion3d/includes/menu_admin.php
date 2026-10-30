<header class="menu">
    <div class="menu-interior">

        <span class="menu-titulo">Gestor de Máquinas 3D</span>

        <details class="menu-navegacion">
            <summary aria-label="Mostrar opciones de navegación">
                <span class="menu-icono" aria-hidden="true"><span></span><span></span><span></span></span>
            </summary>
            <nav class="menu-enlaces">
            <a class="<?php echo activo("inicio.php"); ?>" href="inicio.php">Inicio</a>
            <a class="<?php echo activo("dashboard.php"); ?>" href="dashboard.php">Resumen</a>
            <a class="<?php echo activo(["usuarios.php", "crear_usuario.php", "editar_usuario.php"]); ?>" href="usuarios.php">Usuarios</a>
            <a class="<?php echo activo(["repuestos.php", "crear_repuesto.php", "editar_repuesto.php"]); ?>" href="repuestos.php">Repuestos</a>
            <a class="<?php echo activo(["prestamos.php", "crear_prestamo.php"]); ?>" href="prestamos.php">Préstamos de insumos</a>
            <a href="../../index.php">Cambiar de módulo</a>
            <a class="menu-salir" href="../logout.php">Cerrar sesión</a>
            </nav>
        </details>

    </div>
</header>

<script>
    function limpiarMenuDesktop() {
        if (window.innerWidth > 700) {
            document.querySelectorAll('details.menu-navegacion').forEach(function(details) {
                details.removeAttribute('open');
            });
        }
    }

    // Ejecutar al cargar la página por si ya está en modo escritorio
    window.addEventListener('DOMContentLoaded', limpiarMenuDesktop);

    // Ejecutar al redimensionar la ventana
    window.addEventListener('resize', limpiarMenuDesktop);
</script>
