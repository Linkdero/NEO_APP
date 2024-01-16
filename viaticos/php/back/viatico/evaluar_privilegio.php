<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $data = array();
    $u = usuarioPrivilegiado();

    $array = evaluar_flags_by_sistema($_SESSION['id_persona'],1121);


    //$key = array_search(4, array_column($array, 'id_pantalla'));
    //echo $key;

    $key_di = array_search(2, array_column($array, 'id_pantalla'));
    $key_che = array_search(4, array_column($array, 'id_pantalla'));
    $key_cal = array_search(316, array_column($array, 'id_pantalla'));
    $key_efe = array_search(317, array_column($array, 'id_pantalla'));

    //2 director
    $director  = (!empty($key_di)) ? (($array[array_search(2, array_column($array, 'id_pantalla'))]['flag_es_menu'] == 1) ? true : false) : false;//($u->hasPrivilege(2)) ? true : false;
    //3 cálculo
    //usuarioPrivilegiado()->hasPrivilege(3)

    //impresion cheuqe
    $cheque  = (!empty($key_che)) ? (($array[array_search(4, array_column($array, 'id_pantalla'))]['flag_es_menu'] == 1) ? true : false) : false;//($u->hasPrivilege(4)) ? true : false;
    //$ali = ($array[8]['flag_es_menu'] == 1) ? true : false;//(usuarioPrivilegiado()->hasPrivilege(10)) ? true : false;
    //efectivo
    $efectivo  = (!empty($key_cal)) ? (($array[array_search(316, array_column($array, 'id_pantalla'))]['flag_es_menu'] == 1) ? true : false) : false;//($u->hasPrivilege(316)) ? true : false;
    //calculo
    $calculo  = (!empty($key_efe)) ? (($array[array_search(317, array_column($array, 'id_pantalla'))]['flag_es_menu'] == 1) ? true : false) : false;//($u->hasPrivilege(317)) ? true : false;

    $data = array(
      'director'=>$director,
      'cheque'=>$cheque,
      'efectivo'=>$efectivo,
      'calculo'=>$calculo,
      //'array'=>$array
    );

    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
