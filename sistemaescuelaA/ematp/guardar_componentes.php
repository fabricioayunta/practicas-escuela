<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) { header("Location: ../index.php"); exit(); }
include("../conexion/conexion.php");
$id=(int)($_POST["id_computadora"] ?? 0); $id_usuario=(int)$_SESSION["id_usuario"];
$mother=trim($_POST["mother"]??""); $procesador=trim($_POST["procesador"]??""); $memoria_ram=trim($_POST["memoria_ram"]??""); $disco=trim($_POST["disco"]??""); $monitor=trim($_POST["monitor"]??""); $teclado=trim($_POST["teclado"]??""); $mouse=trim($_POST["mouse"]??""); $observaciones=trim($_POST["observaciones"]??"");
$stmt=mysqli_prepare($conexion,"SELECT * FROM componentes WHERE id_computadora=?"); mysqli_stmt_bind_param($stmt,"i",$id); mysqli_stmt_execute($stmt); $anterior=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
$stmt=mysqli_prepare($conexion,"UPDATE componentes SET mother=?, procesador=?, memoria_ram=?, disco=?, monitor=?, teclado=?, mouse=?, observaciones=? WHERE id_computadora=?"); mysqli_stmt_bind_param($stmt,"ssssssssi",$mother,$procesador,$memoria_ram,$disco,$monitor,$teclado,$mouse,$observaciones,$id);
if(mysqli_stmt_execute($stmt)){
 $accion=""; foreach([["Mother",$anterior["mother"]??"",$mother],["Procesador",$anterior["procesador"]??"",$procesador],["Memoria RAM",$anterior["memoria_ram"]??"",$memoria_ram],["Disco",$anterior["disco"]??"",$disco],["Monitor",$anterior["monitor"]??"",$monitor],["Teclado",$anterior["teclado"]??"",$teclado],["Mouse",$anterior["mouse"]??"",$mouse]] as $c){if($c[1]!==$c[2]) $accion.=$c[0].": ".$c[1]." → ".$c[2]."\n";} if(($anterior["observaciones"]??"")!==$observaciones)$accion.="Observaciones modificadas.\n"; if($accion==="")$accion="No hubo cambios.";
 $stmt=mysqli_prepare($conexion,"INSERT INTO historial_computadoras (id_computadora,id_usuario,accion) VALUES (?,?,?)"); mysqli_stmt_bind_param($stmt,"iis",$id,$id_usuario,$accion); mysqli_stmt_execute($stmt); header("Location: ver_computadora.php?id=".$id); exit();
} echo "Error al guardar los componentes.";
?>