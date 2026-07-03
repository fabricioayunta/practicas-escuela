<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {
    header("Location: ../index.php");
    exit();
}

include("../conexion/conexion.php");

$id = $_POST["id_computadora"];
$id_usuario = $_SESSION["id_usuario"];
// Obtener los valores actuales antes de modificarlos
$sqlAnterior = "SELECT * FROM componentes WHERE id_computadora='$id'";
$resultadoAnterior = mysqli_query($conexion, $sqlAnterior);
$anterior = mysqli_fetch_assoc($resultadoAnterior);

$mother = $_POST["mother"];
$procesador = $_POST["procesador"];
$memoria_ram = $_POST["memoria_ram"];
$disco = $_POST["disco"];
$monitor = $_POST["monitor"];
$teclado = $_POST["teclado"];
$mouse = $_POST["mouse"];
$observaciones = $_POST["observaciones"];

// Actualizar componentes
$sql = "UPDATE componentes SET
mother='$mother',
procesador='$procesador',
memoria_ram='$memoria_ram',
disco='$disco',
monitor='$monitor',
teclado='$teclado',
mouse='$mouse',
observaciones='$observaciones'
WHERE id_computadora='$id'";

if(mysqli_query($conexion,$sql)){

    // Guardar historial
   $accion = "";

if($anterior["mother"] != $mother){
    $accion .= "Mother: ".$anterior["mother"]." → ".$mother."\n";
}

if($anterior["procesador"] != $procesador){
    $accion .= "Procesador: ".$anterior["procesador"]." → ".$procesador."\n";
}

if($anterior["memoria_ram"] != $memoria_ram){
    $accion .= "Memoria RAM: ".$anterior["memoria_ram"]." → ".$memoria_ram."\n";
}

if($anterior["disco"] != $disco){
    $accion .= "Disco: ".$anterior["disco"]." → ".$disco."\n";
}

if($anterior["monitor"] != $monitor){
    $accion .= "Monitor: ".$anterior["monitor"]." → ".$monitor."\n";
}

if($anterior["teclado"] != $teclado){
    $accion .= "Teclado: ".$anterior["teclado"]." → ".$teclado."\n";
}

if($anterior["mouse"] != $mouse){
    $accion .= "Mouse: ".$anterior["mouse"]." → ".$mouse."\n";
}

if($anterior["observaciones"] != $observaciones){
    $accion .= "Observaciones modificadas.\n";
}

if($accion == ""){
    $accion = "No hubo cambios.";
}

    $sqlHistorial = "INSERT INTO historial_computadoras
    (id_computadora,id_usuario,accion)
    VALUES
    ('$id','$id_usuario','$accion')";

    mysqli_query($conexion,$sqlHistorial);

    header("Location: ver_computadora.php?id=".$id);
    exit();

}else{

    echo "Error al guardar los componentes.";

}
?>