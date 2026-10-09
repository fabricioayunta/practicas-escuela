```php
<?php

require_once "conexion/conexion.php";

/*
|--------------------------------------------------------------------------
| USUARIOS A CREAR
|--------------------------------------------------------------------------
*/

$usuarios = [

    [
        "nombre" => "Administrador",
        "apellido" => "Sistema",
        "email" => "admin@gmail.com",
        "password" => "123456",
        "rol" => 1
    ],

    [
        "nombre" => "Profesor",
        "apellido" => "Sistema",
        "email" => "profe@gmail.com",
        "password" => "123456",
        "rol" => 2
    ],

    [
        "nombre" => "EMATP",
        "apellido" => "Sistema",
        "email" => "ematp@gmail.com",
        "password" => "123456",
        "rol" => 3
    ]

];


/*
|--------------------------------------------------------------------------
| CREAR USUARIOS
|--------------------------------------------------------------------------
*/

foreach ($usuarios as $usuario) {

    /* Verificar si ya existe */

    $sql = "SELECT id_usuario
            FROM usuarios
            WHERE email = ?
            LIMIT 1";

    $stmt = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $usuario["email"]
    );

    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultado) > 0) {

        echo " Ya existe: "
            . htmlspecialchars($usuario["email"])
            . "<br>";

        mysqli_stmt_close($stmt);

        continue;
    }

    mysqli_stmt_close($stmt);


    /* Crear contraseña segura */

    $hash = password_hash(
        $usuario["password"],
        PASSWORD_DEFAULT
    );


    /* Insertar usuario */

    $sql = "INSERT INTO usuarios
            (nombre, apellido, email, contrasena, id_rol)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $usuario["nombre"],
        $usuario["apellido"],
        $usuario["email"],
        $hash,
        $usuario["rol"]
    );

    if (mysqli_stmt_execute($stmt)) {

        echo " Creado: "
            . htmlspecialchars($usuario["email"])
            . " | Rol: "
            . $usuario["rol"]
            . "<br>";

    } else {

        echo " Error creando "
            . htmlspecialchars($usuario["email"])
            . ": "
            . mysqli_error($conexion)
            . "<br>";
    }

    mysqli_stmt_close($stmt);
}

echo "<br>";
echo "<strong>Proceso terminado.</strong>";

?>