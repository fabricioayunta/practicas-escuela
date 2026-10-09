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
