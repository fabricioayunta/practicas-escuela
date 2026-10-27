<header class="menu">
    <div class="menu-interior">

        <span class="menu-titulo">Mesa de Ayuda de Laboratorios</span>

        <details class="menu-navegacion">
            <summary aria-label="Mostrar opciones de navegación">
                <span class="menu-icono" aria-hidden="true"><span></span><span></span><span></span></span>
            </summary>
            <nav class="menu-enlaces">
            <a class="<?php echo activo("inicio.php"); ?>" href="inicio.php">Inicio</a>
            <a class="<?php echo activo(["tickets.php", "editar_ticket.php"]); ?>" href="tickets.php">Tickets</a>
            <a class="<?php echo activo(["computadoras.php", "ver_computadora.php", "editar_componentes.php"]); ?>" href="computadoras.php">Computadoras</a>
            <a class="<?php echo activo(["relevamientos.php", "ver_relevamiento.php", "historial_relevamientos_pc.php"]); ?>" href="relevamientos.php">Relevamientos</a>
            <a href="../../index.php">Cambiar de módulo</a>
            <a class="menu-salir" href="../logout.php">Cerrar sesión</a>
            </nav>
        </details>

    </div>
</header>
