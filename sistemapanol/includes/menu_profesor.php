<header class="menu">
    <div class="menu-interior">

        <span class="menu-titulo">🧰 Pañol</span>

        <nav class="menu-enlaces">
            <a class="<?php echo activo("inicio.php"); ?>" href="inicio.php">Inicio</a>
            <a class="<?php echo activo(["articulos.php", "crear_prestamo.php"]); ?>" href="articulos.php">Pedir un artículo</a>
            <a class="<?php echo activo("mis_prestamos.php"); ?>" href="mis_prestamos.php">Mis préstamos</a>
            <a href="../../index.php">Cambiar de módulo</a>
            <a class="menu-salir" href="../logout.php">Cerrar sesión</a>
        </nav>

    </div>
</header>
