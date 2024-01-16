<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$data = array();

$result= array();
$evento=$_SESSION['Evento'];
$puerta=$_SESSION['Punto'];
$result = acreditacion::get_last_ingresos($evento,$puerta,date('Y-m-d'));

foreach ($result as $row) {
  $encoded_image = base64_encode($row['foto']);
  $foto='';
  $foto.='<div class="media">';
  //html_+=hexToBase64(data[i].foto);

  if($encoded_image!=''){
    $foto.="<img class='u-avatar rounded-circle mr-3' src='data:image/jpeg;base64,{$encoded_image}' alt='Image description'>";
  }else{
    $foto.="<img class='u-avatar rounded-circle mr-3' src='assets/svg/mockups/escudo.png' style='width:55px; '> ";
  }
  $tipo = '';
  if($row['tipo_registro']==1){
    $tipo='<span class="badge badge-danger mx-1">Salida</span>';
  }else{
    $tipo='<span class="badge badge-success mx-1" >Entrada</span>';
  }
  $sub_array = array(
    'foto'=>$foto,
    'invitado'=>$row['invitado'].' '.$row['institucion'],
    'institucion'=>$row['institucion'],
    'fecha'=>date('d-m-Y',strtotime($row['fecha'])),
    'hora'=>date('H:i:s', strtotime($row['fecha'])),
    'conteo'=>$tipo
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
