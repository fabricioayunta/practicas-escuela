<?php
/*
|--------------------------------------------------------------------------
| FUNCIONES COMPARTIDAS
|--------------------------------------------------------------------------
*/

/* Escapa texto para mostrarlo de forma segura en HTML */
if (!function_exists("e")) {
    function e($valor)
    {
        return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
    }
}

/* Devuelve "activo" si la página actual está en la lista (para el menú) */
function activo($archivos)
{
    $actual = basename($_SERVER["SCRIPT_NAME"]);
    return in_array($actual, (array)$archivos, true) ? "activo" : "";
}

/* Cuenta registros: contar($conexion, "SELECT COUNT(*) AS total FROM ...") */
function contar($conexion, $sql)
{
    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        return 0;
    }

    $fila = mysqli_fetch_assoc($resultado);

    return (int)($fila["total"] ?? 0);
}

/* Devuelve la clase CSS de un estado de ticket */
function clase_estado($estado)
{
    if ($estado === "Abierto") {
        return "abierto";
    }

    if ($estado === "Pendiente") {
        return "pendiente";
    }

    return "cerrado";
}

/*
   Muestra una pantalla de error clara, con un botón para volver,
   y detiene la ejecución.
*/
function mostrar_error($mensaje, $volver = "")
{
    $css = @file_get_contents(__DIR__ . "/../css/estilos.css");

    if ($volver === "") {
        $botonVolver = '<a class="btn btn-secundario" href="javascript:history.back()">← Volver</a>';
    } else {
        $botonVolver = '<a class="btn btn-secundario" href="' . e($volver) . '">← Volver</a>';
    }

    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Aviso</title><style>' . $css . '</style></head><body class="sin-menu">';
    echo '<main class="contenedor contenedor-estrecho">';
    echo '<div class="tarjeta">';
    echo '<h1>No se pudo completar la acción</h1>';
    echo '<div class="alerta alerta-error">' . e($mensaje) . '</div>';
    echo '<div class="acciones-form">' . $botonVolver . '</div>';
    echo '</div></main></body></html>';
    exit();
}

/* Devuelve la clase CSS de un estado de préstamo (Herramientas y Máquinas de Impresión 3D) */
function clase_prestamo($estado)
{
    return $estado === "Pendiente" ? "pendiente" : "abierto";
}

/* Roles del sistema (tabla roles). Valen igual en los tres módulos */
function roles_sistema()
{
    return [1 => "Administrativo", 2 => "Profesor", 3 => "EMATP"];
}

/* Módulos del sistema (tabla modulos) */
function modulos_sistema()
{
    return [1 => "Mesa de Ayuda de Laboratorios", 2 => "Mesa de Ayuda de Máquinas de Impresión 3D", 3 => "Mesa de Ayuda de Herramientas"];
}

/* Filtra los módulos recibidos del formulario dejando solo los que existen */
function filtrar_modulos($recibidos)
{
    $validos = [];

    foreach ((array)$recibidos as $modulo) {
        $modulo = (int)$modulo;

        if (array_key_exists($modulo, modulos_sistema()) && !in_array($modulo, $validos, true)) {
            $validos[] = $modulo;
        }
    }

    return $validos;
}

/* Dibuja las casillas de módulos (marcando los indicados en $marcados) */
function casillas_modulos($marcados = [])
{
    echo '<fieldset><legend>Módulos a los que puede entrar</legend><div class="opciones" style="margin-bottom:14px;">';

    foreach (modulos_sistema() as $id => $nombre) {

        $marca = in_array($id, $marcados, true) ? " checked" : "";

        echo '<label class="opcion">';
        echo '<input type="checkbox" name="modulos[]" value="' . $id . '"' . $marca . '>';
        echo e($nombre);
        echo '</label>';
    }

    echo '</div></fieldset>';
}

/* Devuelve los id de los módulos a los que puede entrar un usuario */
function modulos_de_usuario($conexion, $id_usuario)
{
    $modulos = [];

    $stmt = mysqli_prepare($conexion, "SELECT id_modulo FROM usuarios_modulos WHERE id_usuario = ?");

    if (!$stmt) {
        return $modulos;
    }

    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    while ($fila = mysqli_fetch_assoc($resultado)) {
        $modulos[] = (int)$fila["id_modulo"];
    }

    mysqli_stmt_close($stmt);

    return $modulos;
}

/* Indica si un usuario puede entrar a un módulo */
function tiene_acceso($conexion, $id_usuario, $id_modulo)
{
    return in_array((int)$id_modulo, modulos_de_usuario($conexion, $id_usuario), true);
}

/*
   Reemplaza los módulos de un usuario por los indicados.
   Se usa dentro de una transacción: devuelve false si algo falló.
*/
function guardar_modulos_usuario($conexion, $id_usuario, $modulos)
{
    $stmt = mysqli_prepare($conexion, "DELETE FROM usuarios_modulos WHERE id_usuario = ?");

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!$ok) {
        return false;
    }

    foreach ($modulos as $id_modulo) {

        $stmt = mysqli_prepare($conexion, "INSERT INTO usuarios_modulos (id_usuario, id_modulo) VALUES (?, ?)");

        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_modulo);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if (!$ok) {
            return false;
        }
    }

    return true;
}

/*
   Token de seguridad de los formularios.
   Cada módulo usa su propia clave de sesión, así cerrar sesión
   en un módulo no invalida los formularios abiertos en otro.
*/
function token_formulario($clave = "csrf_token")
{
    if (
        !isset($_SESSION[$clave]) ||
        !is_string($_SESSION[$clave]) ||
        strlen($_SESSION[$clave]) !== 64
    ) {
        $_SESSION[$clave] = bin2hex(random_bytes(32));
    }

    return $_SESSION[$clave];
}

function token_valido($clave = "csrf_token")
{
    return isset($_SESSION[$clave], $_POST["csrf_token"]) &&
        is_string($_SESSION[$clave]) &&
        is_string($_POST["csrf_token"]) &&
        hash_equals($_SESSION[$clave], $_POST["csrf_token"]);
}

/* Componentes que se pueden marcar en un ticket */
function componentes_internos()
{
    return ["Mother", "Procesador", "Memoria RAM", "Disco"];
}

function componentes_externos()
{
    return ["Monitor", "Teclado", "Mouse"];
}

/* Filtra una lista recibida del formulario dejando solo componentes permitidos */
function filtrar_componentes($recibidos)
{
    $permitidos = array_merge(componentes_internos(), componentes_externos());
    $validos    = [];

    foreach ((array)$recibidos as $componente) {
        if (in_array($componente, $permitidos, true) && !in_array($componente, $validos, true)) {
            $validos[] = $componente;
        }
    }

    return $validos;
}

/*
   Título del ticket: el comienzo de la descripción que escribió el profesor.
   (Los componentes son opcionales, así que no siempre hay de dónde sacarlo.)
*/
function titulo_ticket($descripcion)
{
    $texto = preg_replace('/\s+/u', ' ', trim($descripcion));

    return mb_strlen($texto) > 90 ? rtrim(mb_substr($texto, 0, 87)) . "..." : $texto;
}

/* Dibuja las casillas de componentes (marcando las indicadas en $marcados) */
function casillas_componentes($marcados = [])
{
    $grupos = [
        "Componentes internos" => componentes_internos(),
        "Componentes externos" => componentes_externos(),
    ];

    foreach ($grupos as $titulo => $lista) {

        echo '<fieldset><legend>' . e($titulo) . '</legend><div class="opciones" style="margin-bottom:14px;">';

        foreach ($lista as $componente) {

            $marca = in_array($componente, $marcados, true) ? " checked" : "";

            echo '<label class="opcion">';
            echo '<input type="checkbox" name="componentes[]" value="' . e($componente) . '"' . $marca . '>';
            echo e($componente);
            echo '</label>';
        }

        echo '</div></fieldset>';
    }
}
