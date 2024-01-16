<?php
include_once '../../../../inc/functions.php';

sec_session_start();
$u = usuarioPrivilegiado();
if (function_exists('verificar_session') && verificar_session() == true) :
  set_time_limit(0);
    date_default_timezone_set('America/Guatemala');

    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    $id_persona=$_SESSION['id_persona'];
    $filtro = (!empty($_POST['tipo_filtro']))?$_POST['tipo_filtro']:0;
    $fecha = date('Y-m-d', strtotime($_POST['fecha']));
    $fecha2 = date('Y-m-d', strtotime($_POST['fecha2']));

    $clased = new documento;
    $listado = array();
    $data = array();
    $tipo = '';

    if($filtro == 0){
      if($u->hasPrivilege(302)){
        //$listado = $clased->get_reporte_por_fase($fecha,$fecha2,3);
        $tipo = 3;
      }else if($u->hasPrivilege(308)){
        $listado = $clased->get_reporte_por_fase($fecha,$fecha2,2);
        $tipo = 2;
      }else if(usuarioPrivilegiado()->hasPrivilege(311)){
        $listado = $clased->get_reporte_por_fase($fecha,$fecha2,1);
        $tipo = 1;
      }
    }else if($u->hasPrivilege(301)){
      $listado = $clased->get_reporte_por_fase($fecha,$fecha2,$filtro);
      $tipo = $filtro;
    }




    foreach($listado as $l){
      $renglon = '';
      $cantidad = '';
      $insumos = '';
      if($tipo==3){
        $r = $clased->get_renglones_por_pedido($l['Ped_tra']);
        foreach ($r as $key => $re) {
          $renglon.=', '. $re['Ppr_Ren'];
          //$cantidad+=$re['Pedd_can'];
        }
        $cant = $clased->get_cantidades_pedido($l['Ped_tra']);
        foreach ($cant as $key => $c) {
          // code...
          $insumos .= ', '.$c['Ppr_Nom'];
          $cantidad .=', '.number_format($c['Pedd_can'],0,'','');
        }
      }

      $original1 = date('d-m-Y H:i:s', strtotime($l['recibido']));
      $my_wrapped_string1 = wordwrap($original1, 10, '<br />');
      $original2 = (!empty($l['devuelto']))?date('d-m-Y H:i:s', strtotime($l['devuelto'])):'No ha sido devuelto';
      $my_wrapped_string2 = wordwrap($original2, 10, '<br />');
      $original3 = '<strong>'.substr($renglon, 1).'</strong> ';//'- '.$l['Ppr_Des'];
      $my_wrapped_string3 = wordwrap($original3, 30, '<br />');
      $original4 = $l['estado'];
      $my_wrapped_string4 = wordwrap($original4, 50, '<br />');
      $original5 = $l['ped_observaciones'];
      $my_wrapped_string5 = wordwrap($original5, 30, '<br />');
      $original6 = str_replace(' ',' EN ',$l['estado']);
      $my_wrapped_string6 = wordwrap($original6, 10, '<br />');
      $direccion_o = $l['direccion'];
      $wrappedd =  wordwrap($direccion_o, 10, '<br />');

      $color = '';
      if($l['ped_tipo_seguimiento_id']==8140 || $l['ped_tipo_seguimiento_id']==8143 || $l['ped_tipo_seguimiento_id']==8146){
        $color = 'rgba(13, 209, 87, 0.1)';
      }else if($l['ped_tipo_seguimiento_id']==8148){
        $color = 'rgba(41, 114, 250, 0.1)';
      }else{
        $color = 'rgba(251, 65, 67, 0.1)';
      }
      $sub_array = array(
        'DT_RowId'=>$l['Ped_tra'],
        'pedido_num'=>'<div class="btn-group"><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/pedidos/pedido_detalle.php?ped_tra='.$l['Ped_tra'].'"><strong>'.$l['Ped_num'].'</strong></span></div>',
        'direccion'=>$wrappedd,
        'fecha'=>date('d-m-Y', strtotime($l['Ped_fec'])),
        'fecha_r'=>$my_wrapped_string1,
        'fecha_d'=>$my_wrapped_string2,
        'renglon'=>$my_wrapped_string3,
        'cantidad'=>wordwrap(substr($cantidad, 1), 10, '<br />'),
        'insumos'=>wordwrap(substr($insumos, 1), 10, '<br />'),
        'estado'=>$my_wrapped_string6,
        'observaciones'=>$my_wrapped_string5,
        'tipo_seguimiento'=>$l['ped_tipo_seguimiento_id'],
        'motivo'=>wordwrap($l['Ped_obs'], 30, '<br />'),
        'color'=>$color,
        'tipo'=>($tipo==3)?false:true,
        'tipo2'=>($tipo==3)?true:false


      );
      $data[] = $sub_array;

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
