<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../functions.php';

    $id_persona=$_SESSION['id_persona'];
    $ped_tra='';
    $verificacion='';
    if(isset($_GET['ped_tra'])){
      $ped_tra=$_GET['ped_tra'];
    }
    if(isset($_GET['verificacion'])){
      $verificacion=$_GET['verificacion'];
    }

    $clased = new documento;

    $listado = $clased->get_seguimiento_list($verificacion);

    foreach($listado as $l){
      $validacion=false;
      if(!empty($ped_tra)){
        $v = $clased->get_check_seguimiento($ped_tra, $l['ped_seguimiento_id']);
        $validacion = ($v['conteo'] == 1)?true:false;
      }

      $sub_array = array(
        'seguimiento_id'=>$l['seguimiento_id'],
        'tipo_seguimiento'=>$l['descripcion'],
        'ped_seguimiento_id'=>$l['ped_seguimiento_id'],
        'ped_seguimiento_nom'=>$l['ped_seguimiento_nom'],
        'checked'=>$validacion,
        'validar_chequeado'=>$validacion
      );
      $data[] = $sub_array;
    }

    echo json_encode($data);






else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
