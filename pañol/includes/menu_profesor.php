<header class="menu">
    <div class="menu-interior">

        <span class="menu-titulo">Mesa de Ayuda de Herramientas</span>

        <details class="menu-navegacion">
            <summary aria-label="Mostrar opciones de navegación">
                <span class="menu-icono" aria-hidden="true"><span></span><span></span><span></span></span>
            </summary>
            <nav class="menu-enlaces">
            <a class="<?php echo activo("inicio.php"); ?>" href="inicio.php">Inicio</a>
            <a class="<?php echo activo(["articulos.php", "crear_prestamo.php"]); ?>" href="articulos.php">Pedir un artículo</a>
            <a class="<?php echo activo("mis_prestamos.php"); ?>" href="mis_prestamos.php">Mis préstamos</a>
            <a href="../../index.php">Cambiar de módulo</a>
            <a class="menu-salir" href="../logout.php">Cerrar sesión</a>
            </nav>
        </details>

    </div>
</header>
