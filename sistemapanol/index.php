<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="css/estilos.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pañol</title>
</head>
<body>

    <h1>🧰 Pañol</h1>

    <a href="../index.php">← Volver al selector de módulos</a>

    <br><br>

    <form action="login.php" method="POST">

        <label for="email">Email:</label>
        <br>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Contraseña:</label>
        <br>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">Iniciar Sesión</button>

    </form>

    <?php if (isset($_GET["error"])) { ?>

        <p>Email o contraseña incorrectos, o no tenés acceso a este módulo.</p>

    <?php } ?>

</body>
</html>
