<?php

session_start();

// Elimina todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

// Vuelve al login
header("Location: index.php");
exit();
?>