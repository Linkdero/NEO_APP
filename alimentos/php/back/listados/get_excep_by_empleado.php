<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    // include_once '../../functions_plaza.php';
    $id_persona=$_GET['id_persona'];
    $excepciones = array();
    $excepciones=alimentos::get_excepciones_by_empleado($id_persona);
    $data = array();

    foreach($excepciones as $ex){

      $arreglo = array(
        'fecha1'=>date('d-m-Y', strtotime($ex['fecha1'])),
        'fecha2'=>date('d-m-Y', strtotime($ex['fecha2'])),
        'desayuno'=>($ex['desayuno']==1)?'fa fa-check-circle text-info' : 'fa fa-times-circle text-muted',
        'almuerzo'=>($ex['almuerzo']==1)?'fa fa-check-circle text-info' : 'fa fa-times-circle text-muted',
        'cena'=>($ex['cena']==1)?'fa fa-check-circle text-info' : 'fa fa-times-circle text-muted',
        'id_excepcion'=>$ex['id_excepcion'],
        'observaciones'=>$ex['observaciones'],
        'id_persona'=>$ex['id_persona'],

    );

      $data[]=$arreglo;

    }

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
