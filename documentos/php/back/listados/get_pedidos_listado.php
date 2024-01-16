<?php
include_once '../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
if (function_exists('verificar_session') && verificar_session() == true) :

  date_default_timezone_set('America/Guatemala');

  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';



  $id_persona = $_SESSION['id_persona'];
  $tipo = $_POST['tipo'];
  $year = $_POST['year'];
  $depto = '';
  $dir = '';

  $clased = new documento;
  $listado = array();
  $data = array();

  $top = 0;

  if ($u->hasPrivilege(301) || $u->hasPrivilege(302) || $u->hasPrivilege(308) || $u->hasPrivilege(311)) {
    $listado = $clased->get_pedidos_remesas_by_secretaria($year,$tipo);
  } else {
    $clase = new empleado;
    $e = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);
    if (!empty($e['id_dirf'])) {
      if ($e['id_subdireccion_funcional'] == 37) {
        $depto = 207; //subdireccion de mantenimiento y servicios generales
      } else {
        $depto = $e['id_dirf'];
      }
    } else {
      if (!empty($e['id_subsecre'])) {
        $depto = $e['id_subsecre'];
      } else {
        $depto = $e['id_secre'];
      }
    }

    $dir = $clased->devuelve_direccion_app_pos($depto);
    if ($dir == 6) {
      $dir = 207;
    }
    if($_SESSION['id_persona']== 5449){
      $dir = 2;
    }
    $listado = $clased->get_pedidos_remesas_by_direccion($dir, $tipo, $year);
  }

  foreach ($listado as $l) {

    $str = $l['ped_obs'];
    $original = $l['ped_obs'];
    $my_wrapped_string = wordwrap($original, 50, '<br />');
    $progress = '';

    $stateComprado = (!empty($l['comprado']) == 0) ? $l['verificacion'] : 'INSUMO COMPRADO';
    $progress .= '<div style="margin-top:0px; "><span class="badge-sm text-' . $l['color'] . '">' . $l['estado'] . '<br>' . $stateComprado . '</span>';
    $progress .= ($l['porcentaje'] > 0) ? '<div class="progress progress-striped skill-bar " style="height:6px">
              <div class="progress-bar progress-bar-striped bg-' . $l['color'] . '" role="progressbar" aria-valuenow="' . $l['porcentaje'] . '" aria-valuemin="' . $l['porcentaje'] . '" aria-valuemax="100" style="width: ' . $l['porcentaje'] . '%">
              </div>
            </div></div>' : '';
    $accion = '<div class="btn btn-group"><div class="">';
    $accion .= '<a id="actions1Invoker1" class=" btn btn-soft-info btn-sm" href="#!"onclick="impresion_pedido(' . $l['ped_tra'] . ',1)"><i class="fa fa-print"></i></a>';
    //$accion .='<div class="dropdown-menu dpm1 dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker1" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu' . $l['ped_tra'] . '1"></div></div></div></div>';
    //$accion .= '</div><div class="">';
    $accion .= '<a id="actions1Invoker2" class=" btn btn-soft-info btn-sm" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/pedidos/pedido_detalle.php?ped_tra='.$l['ped_tra'].'"><i class="far fa-file"></i></a></div>';
    /*$accion.= '<div class="dropdown-menu dpm2 dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker2" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;">
        <ul class="list-unstyled mb-0">
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3 text-info" >
              <i class="fa fa-pen mr-2"></i> Pedido y Remesa
            </a>
          </li>
        </ul>
      </div></div></div>';*/



    if ($l['estado'] == '') {
      $progress = '<div style="margin-top:0px; "><span class="badge-sm text-dark">SIN PROCESAR<br></span>';
      $progress .= '<div class="progress progress-striped skill-bar " style="height:6px">
      <div class="progress-bar progress-bar-striped bg-dark" role="progressbar" aria-valuenow=0 aria-valuemin=0 aria-valuemax="100" style="width: 0%">
      </div>
    </div></div>';
      //$accion .= '<button class="btn btn-soft-danger btn-sm" onclick="anular_pedido(' . $l['ped_tra'] . ',' . $l['ped_num'] . ',' . $id_persona . ')"><i class="fa fa-times"></i></button>';
    }

    $accion .= '</div></div>';
    $sub_array = array(
      'DT_RowId' => $l['ped_tra'],
      'pedido_num' => $l['ped_num'], //$tipo.' - - '..' | '.$dir,
      'pedido_interno' => $l['Ped_num_interno'],
      'pac'=>(!empty($l['Pac_id'])) ? 'Programado' : '',
      'fecha' => fecha_dmy($l['ped_fec']),
      'direccion' => $l['direccion'],
      'departamento' => $l['departamento'],
      'asignado' => $l['asignado'],
      'ubicacion' => $l['direccion'] . ' <br> ' . $l['departamento'],
      'observaciones' => $my_wrapped_string,
      'estado' => $progress,
      'porcentaje' => $l['porcentaje'],
      'acciones' => $accion,
      'urgente'=>'',//(!empty($l['Ped_urgente'])) ? '<span class="text-danger"><i class="fa fa-engine-warning"></i> Urgente </span>' : 'NOPESS'
    );
    $data[] = $sub_array;
  }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData" => $data
  );

  echo json_encode($results);






else :
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
