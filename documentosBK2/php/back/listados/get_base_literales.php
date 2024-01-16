<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $docto_id='';
    $tipo='';
    if(isset($_GET['docto_id'])){
    	$docto_id=$_GET['docto_id'];
    }
    if(isset($_GET['categoria'])){
    	$categoria=$_GET['categoria'];
    }
    if(isset($_GET['tipo'])){
    	$tipo=$_GET['tipo'];
    }

    if($categoria==8048 || $categoria == 8049){
      $listado = documento::get_base_literales_by_docto($docto_id,$tipo);
      $data = array();

      if($listado["status"] == 200){
          $response = "";
          //$data[] = $sub_array;
          foreach($listado["data"] as $l){
            $sub_array = array(
              'docto_id'=>$l['docto_id'],
              'base_id'=>$l['base_id'],
              'base_literal_id'=>$l['base_literal_id'],
              'base_literal_nom'=>$l['base_literal_nom'],
              'base_literal_titulo'=>$l['base_literal_titulo'],
              'base_literal_descripcion'=>$l['base_literal_descripcion'],
            );
            $data[] = $sub_array;
          }

      }else{
          $response = $listado["msg"];
      }
      echo json_encode($data);

    }else{
      $sub_array = array(
        'base_literal_id'=>0,
        'base_literal_desc'=>'error'
      );
      $data[] = $sub_array;

      echo json_encode($data);
    }





else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
