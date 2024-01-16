<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../functions.php';

    $id_persona=$_SESSION['id_persona'];
    $estado='';
    if(isset($_GET['estado'])){
      $estado=$_GET['estado'];
    }
    $clased = new documento;

    $listado = $clased->get_pedidos_by_estado($estado);
    $sub_array = array(
      'ped_tra'=>'',
      'ped_num'=>'-- Seleccionar --',
    );
    $data[] = $sub_array;
    foreach($listado as $l){

      $sub_array = array(
        'ped_tra'=>$l['Ped_tra'],
        'ped_num'=>$l['ped_num'],
      );
      $data[] = $sub_array;
    }

    echo json_encode($data);






else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
