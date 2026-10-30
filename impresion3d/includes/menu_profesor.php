<header class="menu">
    <div class="menu-interior">

        <span class="menu-titulo">Gestor de Máquinas 3D</span>

        <details class="menu-navegacion">
            <summary aria-label="Mostrar opciones de navegación">
                <span class="menu-icono" aria-hidden="true"><span></span><span></span><span></span></span>
            </summary>
            <nav class="menu-enlaces">
            <a class="<?php echo activo("inicio.php"); ?>" href="inicio.php">Inicio</a>
            <a class="<?php echo activo("crear_prestamo.php"); ?>" href="crear_prestamo.php">Pedir un insumo</a>
            <a class="<?php echo activo("mis_prestamos.php"); ?>" href="mis_prestamos.php">Mis préstamos</a>
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