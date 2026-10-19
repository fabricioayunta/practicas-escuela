<header class="menu">
    <div class="menu-interior">

        <span class="menu-titulo">Mesa de Ayuda Informática</span>

        <nav class="menu-enlaces">
            <a class="<?php echo activo("inicio.php"); ?>" href="inicio.php">Inicio</a>
            <a class="<?php echo activo("dashboard.php"); ?>" href="dashboard.php">Resumen</a>
            <a class="<?php echo activo(["usuarios.php", "crear_usuario.php", "editar_usuario.php"]); ?>" href="usuarios.php">Usuarios</a>
            <a class="<?php echo activo(["laboratorios.php", "crear_laboratorio.php", "editar_laboratorio.php"]); ?>" href="laboratorios.php">Laboratorios</a>
            <a class="<?php echo activo(["computadoras.php", "crear_computadora.php", "editar_computadora.php"]); ?>" href="computadoras.php">Computadoras</a>
            <a class="<?php echo activo(["tickets.php", "ver_ticket.php"]); ?>" href="tickets.php">Tickets</a>
            <a class="menu-salir" href="../logout.php">Cerrar sesión</a>
        </nav>

    </div>
</header>
