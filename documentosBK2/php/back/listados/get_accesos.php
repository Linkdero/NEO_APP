<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $accesos = array();
    $id_pantalla ='';

    if(isset($_GET['id_direccion'])){
      $id_pantalla=$_GET['id_direccion'];
    }

    //$id_pantalla = $_GET['id_pantalla'];

    $clase = new documento();
    $accesos = $clase->get_accesos($id_pantalla);


    $data = array();
    $sub_array = array(
      'id_persona'=>'',
      'empleado'=>'- Seleccionar -'
    );
    $data[] = $sub_array;
    foreach ($accesos as $a){
      $sub_array = array(
        'DT_RowId'=>$a['id_persona'],
        'id_persona'=>$a['id_persona'],
        'empleado'=>$a['primer_nombre'].' '.$a['segundo_nombre'].' '.$a['tercer_nombre'].' '.$a['primer_apellido'].' '.$a['segundo_apellido'].' '.$a['tercer_apellido']
      );
      $data[] = $sub_array;
    }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
