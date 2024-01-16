<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $data = array();


    $array = evaluar_flags_by_sistema($_SESSION['id_persona'],8017);

    /*$response = array_filter(array_map(function($row) {
    return ($row['id_pantalla'] == 302 && $row['flag_autoriza'] == 1) ? true : false ;}, $array));*/
    $response = array_filter( $array, function( $e ) {
      return ($e['id_pantalla'] == 303);
    });
    /*$key = str_replace('[', '', array_keys($response));*/
    //2 = compras;
    //6 subsecretaria
    //8 planificacion
    //5 director
    //12 presupuesto
    //13 tesoreria


    $pos = $array[3]['id_persona'];
    $data = array(
      'plani'=>($array[8]['flag_es_menu'] == 1) ? true : false,
      'plani_au'=>($array[8]['flag_autoriza'] == 1) ? true : false,
      'plani_rev'=>($array[8]['flag_actualizar'] == 1) ? true : false,
      'plani_seguimiento'=>($array[8]['flag_descarga'] == 1) ? true : false,

      'ssa'=>($array[6]['flag_es_menu'] == 1) ? true : false,
      'ssa_au'=>($array[6]['flag_autoriza'] == 1) ? true : false,

      'compras'=>($array[2]['flag_es_menu'] == 1) ? true : false,
      'compras_au'=>($array[2]['flag_autoriza'] == 1 && $array[4]['flag_es_menu'] == 1) ? true : false,
      'compras_recepcion'=>($array[2]['flag_insertar'] == 1) ? true : false,
      'compras_tecnico'=>($array[2]['flag_actualizar'] == 1) ? true : false,
      'compras_asignar_tecnico'=>($array[2]['flag_autoriza'] == 1) ? true : false,
      'compras_tecnico_factura'=>($array[9]['flag_actualizar'] == 1) ? true : false,
      'compras_tecnico_anulacion'=>($array[9]['flag_imprimir'] == 1) ? true : false,

      'directorf_au'=>($array[5]['flag_autoriza'] == 1) ? true : false,
      'presupuesto'=>($array[12]['flag_es_menu'] == 1) ? true : false,
      'presupuesto_au'=>($array[12]['flag_autoriza'] == 1) ? true : false,

      'tesoreria'=>($array[13]['flag_es_menu'] == 1) ? true : false,
      'tesoreria_recepcion'=>($array[13]['flag_insertar'] == 1) ? true : false,
      'tesoreria_au'=>($array[13]['flag_autoriza'] == 1) ? true : false,

      'facturas'=>($array[9]['flag_es_menu'] == 1) ? true : false,
      'facturas_cur'=>($array[9]['flag_descarga'] == 1) ? true : false,

      'registro'=>($array[2]['flag_es_menu'] == 1 || $array[12]['flag_es_menu'] == 1 || $array[5]['flag_es_menu'] == 1) ? true : false,


      'subdirectorfinanciero'=>($array[5]['flag_es_menu'] == 1) ? true : false,
      //'array'=>$response
      //'array'=>($array[3]['id_persona'] == 1) ? true : false,
      //'response'=>$pos//array_search('flag_acceso', $array)//$response//array_search('flag_acceso', $response)
    );


    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
