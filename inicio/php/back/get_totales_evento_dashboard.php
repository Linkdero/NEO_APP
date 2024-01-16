<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$data = array();
//$d = saber_dia('2019-11-29');
$result= array();
$evento=$_SESSION['Evento'];
$punto=$_POST['punto'];
$result = acreditacion::get_reporte_totales($evento,$punto);

foreach ($result as $row) {
  $dia='';$c1=0;$c2=0;$c3=0;
  $pnt=62;
  $pnt_des=acreditacion::get_punto_por_id($pnt);
  $invitacion=acreditacion::get_reporte_totales_por_tipo($evento,1,$row['fecha'],$pnt);
  $acreditacion=acreditacion::get_reporte_totales_por_tipo($evento,2,$row['fecha'],$pnt);
  $emergente=acreditacion::get_reporte_totales_por_tipo($evento,3,$row['fecha'],$pnt);

  if($invitacion['conteo']!=0){
    $c1=$invitacion['conteo'];
  }
  if($acreditacion['conteo']!=0){
    $c2=$acreditacion['conteo'];
}
  if($emergente['conteo']!=0){
    $c3=$emergente['conteo'];
}
  $sub_array = array(
    'day'=>User::get_nombre_dia($row['fecha']),
    'punto'=>$pnt_des['Pnt_des'],
    'fecha'=>fecha_dmy($row['fecha']),
    'total'=>$row['conteo'],
    'conteo1'=>$c1,
    'conteo2'=>$c2,
    'conteo3'=>$c3
  );
  $data[]=$sub_array;
}

//echo json_encode($data);

$results = array(
  "sEcho" => 1,
  "iTotalRecords" => count($data),
  "iTotalDisplayRecords" => count($data),
  "aaData"=>$data);

  echo json_encode($results);
?>
