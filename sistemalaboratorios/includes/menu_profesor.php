<header class="menu">
    <div class="menu-interior">

        <span class="menu-titulo">Mesa de Ayuda Informática</span>

        <nav class="menu-enlaces">
            <a class="<?php echo activo("inicio.php"); ?>" href="inicio.php">Inicio</a>
            <a class="<?php echo activo("crear_ticket.php"); ?>" href="crear_ticket.php">Crear ticket</a>
            <a class="<?php echo activo(["mis_tickets.php", "editar_ticket.php"]); ?>" href="mis_tickets.php">Mis tickets</a>
            <a class="menu-salir" href="../logout.php">Cerrar sesión</a>
        </nav>

    </div>
</header>
