<?php
session_start();
if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) { header("Location: ../index.php"); exit(); }
include("../conexion/conexion.php");
$id_ticket=(int)($_POST["id_ticket"] ?? 0); $estado=$_POST["estado"] ?? ""; $observacion=trim($_POST["observacion"] ?? ""); $id_usuario=(int)$_SESSION["id_usuario"];
$permitidos=["Abierto","Pendiente","Cerrado"]; if($id_ticket<=0 || !in_array($estado,$permitidos,true)){ exit("Datos inválidos."); }
$stmt=mysqli_prepare($conexion,"UPDATE tickets SET estado=?, id_ematp_asignado=? WHERE id_ticket=?"); mysqli_stmt_bind_param($stmt,"sii",$estado,$id_usuario,$id_ticket); mysqli_stmt_execute($stmt);
$stmt=mysqli_prepare($conexion,"INSERT INTO historialticket (id_ticket,estado,fecha,observacion,id_usuario) VALUES (?, ?, NOW(), ?, ?)"); mysqli_stmt_bind_param($stmt,"issi",$id_ticket,$estado,$observacion,$id_usuario); mysqli_stmt_execute($stmt);
header("Location: tickets.php"); exit();
?>