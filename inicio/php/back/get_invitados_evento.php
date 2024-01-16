<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';
sec_session_start();
set_time_limit(0);
date_default_timezone_set('America/Guatemala');
$data = array();

$result= array();
$evento=$_SESSION['Evento'];
$puerta=$_SESSION['Punto'];
$result = acreditacion::get_invitados($evento);

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
  $tipo = '';
  if($row['Inv_activo']==0){
    $tipo='<span class="badge badge-danger mx-1">Inactivo</span>';
  }else{
    $tipo='<span class="badge badge-success mx-1" >Activo.</span>';
  }
  $sub_array = array(
    'foto'=>$row['Inv_cor'],
    'codigo'=>$row['Inv_ref'],
    'invitado'=>$row['Inv_nom'],
    'institucion'=>$row['Inv_pro'],
    'status'=>$tipo
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
