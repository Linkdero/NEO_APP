<?php

$vt_nombramiento=$_POST['vt_nombramiento'];
$id_empleado=$_POST['id_persona'];
//$bln_confirma=$_POST['confirma'];

$fecha_salida_saas=date('Y-m-d', strtotime($_POST['fecha_salida_saas']));
$hora_salida_saas=$_POST['hora_salida_saas'];
$fecha_llegada_lugar=date('Y-m-d', strtotime($_POST['fecha_llegada_lugar']));
$hora_llegada_lugar=$_POST['hora_llegada_lugar'];

$fecha_salida_lugar=date('Y-m-d', strtotime($_POST['fecha_salida_lugar']));
$hora_salida_lugar=$_POST['hora_salida_lugar'];
$fecha_regreso_saas=date('Y-m-d', strtotime($_POST['fecha_regreso_saas']));
$hora_regreso_saas=$_POST['hora_regreso_saas'];

echo $fecha_salida_saas
?>
