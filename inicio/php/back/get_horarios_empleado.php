<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';
sec_session_start();
set_time_limit(0);
date_default_timezone_set('America/Guatemala');
$data = array();

$result= array();
$fecha =$_POST['fecha'];
$date = date('Y-m-d', strtotime($fecha));
$result = acreditacion::get_horarios_empleados(0,$date);

foreach ($result as $row) {
  /*$encoded_image = base64_encode($row['Inv_fotografia']);
  $foto='';
  $foto.='<div class="media">';
  //html_+=hexToBase64(data[i].foto);

  if($encoded_image!=''){
    $foto.="<img class='u-avatar rounded-circle mr-3' src='data:image/jpeg;base64,{$encoded_image}' alt='Image description'>";
  }else{
    $foto.="<img class='u-avatar rounded-circle mr-3' src='assets/svg/mockups/escudo.png' style='width:55px; '> ";
  }
  $foto.='</div>';*/

  $sub_array = array(
    'gafete'=>$row['gafete'],
    'direccion'=>$row['direccion'],
    'empleado'=>$row['nombre'],
    'fecha'=>$row['fecha'],
    'entrada'=>date('H:i:s',strtotime($row['entrada'])),
    'entra_alm'=>date('H:i:s',strtotime($row['entra_alm'])),
    'sale_alm'=>date('H:i:s',strtotime($row['sale_alm'])),
    'salida'=>date('H:i:s',strtotime($row['salida']))/*,
    'institucion'=>$row['Inv_pro'],
    'status'=>$tipo*/
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
