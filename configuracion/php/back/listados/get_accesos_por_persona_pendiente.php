<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $persona=$_POST['id_persona'];
    $accesos = array();
    $accesos=configuracion::get_accesos_by_persona_que_no_tiene($persona);
    $data = array();

    foreach ($accesos as $a){
      $accion='';
      if($a['id_status']==1){
        $accion='<button class="btn btn-sm btn-personalizado outline" onclick="establecer_modulo_a_empleado('.$a['id_modulo'].','.$persona.')"><i class="fa fa-check"></i></button>';
      }



      $sub_array = array(


        'id_modulo'=>$a['id_modulo'],
        'modulo'=>$a['modulo'],
        'estado'=>$a['id_status'],
        'accion'=>$accion
      );

      $data[]=$sub_array;
    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

    echo json_encode($results);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
