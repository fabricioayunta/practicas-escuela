<header class="menu">
    <div class="menu-interior">

        <span class="menu-titulo">🧰 Pañol</span>

        <nav class="menu-enlaces">
            <a class="<?php echo activo("inicio.php"); ?>" href="inicio.php">Inicio</a>
            <a class="<?php echo activo("dashboard.php"); ?>" href="dashboard.php">Resumen</a>
            <a class="<?php echo activo(["usuarios.php", "crear_usuario.php", "editar_usuario.php"]); ?>" href="usuarios.php">Usuarios</a>
            <a class="<?php echo activo(["articulos.php", "crear_articulo.php", "editar_articulo.php"]); ?>" href="articulos.php">Artículos</a>
            <a class="<?php echo activo(["prestamos.php", "crear_prestamo.php"]); ?>" href="prestamos.php">Préstamos</a>
            <a href="../../index.php">Cambiar de módulo</a>
            <a class="menu-salir" href="../logout.php">Cerrar sesión</a>
        </nav>

    </div>
</header>
