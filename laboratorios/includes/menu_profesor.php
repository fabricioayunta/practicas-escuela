<header class="menu">
    <div class="menu-interior">

        <span class="menu-titulo">Mesa de Ayuda de Laboratorios</span>

        <details class="menu-navegacion">
            <summary aria-label="Mostrar opciones de navegación">
                <span class="menu-icono" aria-hidden="true"><span></span><span></span><span></span></span>
            </summary>
            <nav class="menu-enlaces">
            <a class="<?php echo activo("inicio.php"); ?>" href="inicio.php">Inicio</a>
            <a class="<?php echo activo("crear_ticket.php"); ?>" href="crear_ticket.php">Crear ticket</a>
            <a class="<?php echo activo(["mis_tickets.php", "editar_ticket.php"]); ?>" href="mis_tickets.php">Mis tickets</a>
            <a href="../../index.php">Cambiar de módulo</a>
            <a class="menu-salir" href="../logout.php">Cerrar sesión</a>
            </nav>
        </details>

    </div>
</header>
