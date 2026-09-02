<?php

session_start();

// Cierra solo la sesión de este módulo
unset($_SESSION["panol_id_usuario"]);
unset($_SESSION["panol_nombre"]);
unset($_SESSION["panol_apellido"]);
unset($_SESSION["panol_email"]);

// Vuelve al login del módulo
header("Location: index.php");
exit();
?>
