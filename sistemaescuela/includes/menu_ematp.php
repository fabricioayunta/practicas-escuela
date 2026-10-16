<header class="menu">
    <div class="menu-interior">

        <span class="menu-titulo">Mesa de Ayuda Informática</span>

        <nav class="menu-enlaces">
            <a class="<?php echo activo("inicio.php"); ?>" href="inicio.php">Inicio</a>
            <a class="<?php echo activo(["tickets.php", "editar_ticket.php"]); ?>" href="tickets.php">Tickets</a>
            <a class="<?php echo activo(["computadoras.php", "ver_computadora.php", "editar_componentes.php"]); ?>" href="computadoras.php">Computadoras</a>
            <a class="menu-salir" href="../logout.php">Cerrar sesión</a>
        </nav>

    </div>
</header>
