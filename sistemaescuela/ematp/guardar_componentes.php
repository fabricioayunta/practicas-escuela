<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["id_rol"] ?? 0) != 3) {
    header("Location: ../index.php");
    exit();
}

require_once "../conexion/conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: computadoras.php");
    exit();
}

$id         = (int)($_POST["id_computadora"] ?? 0);
$id_usuario = (int)$_SESSION["id_usuario"];

if ($id <= 0) {
    mostrar_error("Computadora inválida.", "computadoras.php");
}

/* Datos nuevos */

$nuevo = [
    "mother"        => trim($_POST["mother"] ?? ""),
    "procesador"    => trim($_POST["procesador"] ?? ""),
    "memoria_ram"   => trim($_POST["memoria_ram"] ?? ""),
    "disco"         => trim($_POST["disco"] ?? ""),
    "monitor"       => trim($_POST["monitor"] ?? ""),
    "teclado"       => trim($_POST["teclado"] ?? ""),
    "mouse"         => trim($_POST["mouse"] ?? ""),
    "observaciones" => trim($_POST["observaciones"] ?? ""),
];

foreach ($nuevo as $campo => $valor) {

    $limite = ($campo === "observaciones") ? 255 : 100;

    if (mb_strlen($valor) > $limite) {
        mostrar_error("Uno de los textos es demasiado largo (máximo " . $limite . " caracteres).");
    }
}

/* Comprobar que la computadora exista */

$stmt = mysqli_prepare($conexion, "SELECT id_computadora FROM computadoras WHERE id_computadora = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) !== 1) {
    mysqli_stmt_close($stmt);
    mostrar_error("No se encontró la computadora.", "computadoras.php");
}

mysqli_stmt_close($stmt);

/* Datos anteriores (para anotar qué cambió) */

$stmt = mysqli_prepare($conexion, "SELECT * FROM componentes WHERE id_computadora = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$anterior = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

mysqli_begin_transaction($conexion);

if ($anterior) {

    $stmt = mysqli_prepare(
        $conexion,
        "UPDATE componentes
         SET mother = ?, procesador = ?, memoria_ram = ?, disco = ?,
             monitor = ?, teclado = ?, mouse = ?, observaciones = ?
         WHERE id_computadora = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssi",
        $nuevo["mother"], $nuevo["procesador"], $nuevo["memoria_ram"], $nuevo["disco"],
        $nuevo["monitor"], $nuevo["teclado"], $nuevo["mouse"], $nuevo["observaciones"],
        $id
    );

} else {

    /* La computadora todavía no tenía fila de componentes: se crea */

    $stmt = mysqli_prepare(
        $conexion,
        "INSERT INTO componentes
            (mother, procesador, memoria_ram, disco, monitor, teclado, mouse, observaciones, id_computadora)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssi",
        $nuevo["mother"], $nuevo["procesador"], $nuevo["memoria_ram"], $nuevo["disco"],
        $nuevo["monitor"], $nuevo["teclado"], $nuevo["mouse"], $nuevo["observaciones"],
        $id
    );
}

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_rollback($conexion);
    mostrar_error("No se pudieron guardar los componentes.");
}

mysqli_stmt_close($stmt);


/* Anotar los cambios en el historial */

$etiquetas = [
    "mother"      => "Mother",
    "procesador"  => "Procesador",
    "memoria_ram" => "Memoria RAM",
    "disco"       => "Disco",
    "monitor"     => "Monitor",
    "teclado"     => "Teclado",
    "mouse"       => "Mouse",
];

$accion = "";

foreach ($etiquetas as $campo => $etiqueta) {

    $antes = (string)($anterior[$campo] ?? "");

    if ($antes !== $nuevo[$campo]) {
        $accion .= $etiqueta . ": " . $antes . " → " . $nuevo[$campo] . "\n";
    }
}

if ((string)($anterior["observaciones"] ?? "") !== $nuevo["observaciones"]) {
    $accion .= "Observaciones modificadas.\n";
}

if ($accion === "") {
    $accion = "No hubo cambios.";
}

$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO historial_computadoras (id_computadora, id_usuario, accion) VALUES (?, ?, ?)"
);

mysqli_stmt_bind_param($stmt, "iis", $id, $id_usuario, $accion);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

mysqli_commit($conexion);

header("Location: ver_computadora.php?id=" . $id);
exit();
