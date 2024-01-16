<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $ini = $_POST['ini'];
    $fin = $_POST['fin'];
    $formularios = array();
    $formularios = viaticos::get_formularios_utilizados($ini,$fin);

    $data = array();
    $monto_ant = 0;
    $monto_act = 0;
    $y = 0;
    $validacion = '';
    $icono = '';
    $final = count($formularios) - 1;
    $message;
    foreach ($formularios as $key => $f){
      if(!empty($f['nro_frm_vt_ant'])){

        if ($y < $final) {
          $y = $key + 1;
          $id_actual = $formularios[$key]['nro_frm_vt_ant']; //$s[$key+1]['id_empleado'];
          $id_siguiente = $formularios[$y]['nro_frm_vt_ant'];


          //$sumar=$s['gastos'];
          //$incremental+=1;
          if ($id_siguiente == $id_actual) {
            /*$message = 'Reutilizado';
            $icono = '<span class="fa fa-times-circle text-danger"></span>';*/
          } else {
            if($f['id_estado'] == 3 && !empty($f['nro_frm_vt_liq'])){
              $message = 'Liquidado';
              $icono = '<span class="fa fa-check-circle text-success"></span>';
            }else if($f['id_estado'] == 1){
              $message = 'En Proceso';
              $icono = '<span class="fa fa-check-circle text-warning"></span>';
            }else if($f['id_estado'] == 2 || $f['id_estado'] == 3 && empty($f['nro_frm_vt_liq'])){
              $message = 'Anulado';
              $icono = '<span class="fa fa-times-circle text-danger"></span>';
            }

            $sub_array = array(
              'vt_nombramiento'=>$f['vt_nombramiento'],//.' Y: '.$y.' - key: '.$key,
              'nro_frm_vt_ant'=>(!empty($f['nro_frm_vt_ant'])) ? $icono.' '.$f['nro_frm_vt_ant'] : '',
              'nro_frm_vt_cons'=>(!empty($f['nro_frm_vt_cons'])) ? $f['nro_frm_vt_cons'] : '',
              'nro_frm_vt_ext'=>(!empty($f['nro_frm_vt_ext'])) ? $f['nro_frm_vt_ext'] : '',
              'nro_frm_vt_liq'=>(!empty($f['nro_frm_vt_liq'])) ? $f['nro_frm_vt_liq'] : '',
              'estado_frm'=>$message,
              'estado'=>$f['state'],
            );
            $data[] = $sub_array;

          }

        }



          //$data[]=$e;
      }

    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data
  );

  echo json_encode($results);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
