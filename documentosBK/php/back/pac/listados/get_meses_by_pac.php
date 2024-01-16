<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../../functions.php';


    $response = array();
    $pac_id='';
    if(isset($_GET['pac_id'])){
      $pac_id=$_GET['pac_id'];
    }
    $clased = new documento;

    $meses = $clased->get_meses_by_id($pac_id);
    $data = array();
    $compras = false;
    $u = usuarioPrivilegiado();
    if ($u->hasPrivilege(302) && $u->hasPrivilege(325)) {
      $compras = true;
    }
    foreach ($meses AS $m) {
      $sub_array = array(
        'pac_id'=>$m['pac_id'],
        'id_pac_mes'=>'mm'.$m['pac_id_mes'],
        'id_mes'=>$m['pac_id_mes'],
        'mes'=>User::get_nombre_mes($m['pac_id_mes']),
        'cantidad'=>(!empty($m['cantidad'])) ? $m['cantidad'] : NULL,
        'monto'=>(!empty($m['cantidad'])) ? $m['monto'] : NULL,
        'cantidad_real'=>(!empty($m['cantidad_real'])) ? $m['cantidad_real'] : NULL,
        'monto_real'=>(!empty($m['cantidad_real'])) ? $m['monto_real'] : NULL,
        'id_status'=>$m['id_status'],
        'checked'=>(!empty($m['cantidad'])) ? true : false,
        'compras'=>$compras
      );
      $data[] = $sub_array;
    }
  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
?>
