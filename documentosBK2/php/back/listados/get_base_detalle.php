<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $docto_id='';
    $categoria='';
    $base_id='';
    if(isset($_GET['docto_id'])){
    	$docto_id=$_GET['docto_id'];
    }
    if(isset($_GET['categoria'])){
    	$categoria=$_GET['categoria'];
    }

    if(isset($_GET['base_id'])){
    	$base_id=$_GET['base_id'];
    }

    if($categoria==8048 || $categoria == 8049){
      $cronograma = documento::get_base_detalle($docto_id,$base_id);
      $data = array();

      if($cronograma["status"] == 200){
          $response = "";
          //$data[] = $sub_array;
          foreach($cronograma["data"] as $c){
              //$response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
              $p=($base_id==145 || $base_id==146)?'1':'2';
              $sub_array = array(
                'actividad_id'=>$c['id_item'],
                'actividad_string_c'=>$c['descripcion_corta'],
                'actividad_string'=>$c['descripcion'],
                'actividad_fecha'=>(!empty($c['fecha']))?fecha_dmy($c['fecha']):'',
                'actividad_valor'=>(!empty($c['valor']))?number_format($c['valor'],0,'',''):'0',
                'actividad_parametros'=>$c['id_item'].'-'.$p
              );
              $data[] = $sub_array;
          }

      }else{
          $response = $cronograma["msg"];
      }
      echo json_encode($data);

    }else{
      $sub_array = array(
        'actividad_valor'=>0,
        'actividad_parametros'=>'error'
      );
      $data[] = $sub_array;

      echo json_encode($data);
    }





else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
