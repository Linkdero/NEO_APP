<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  date_default_timezone_set('America/Guatemala');
  include_once '../functions.php';

  $nombramiento = $_POST['vt_nombramiento'];
  $empleados = array();
  $empleados = viaticos::get_empleados_asistieron($nombramiento);

  $data = array();
  foreach ($empleados as $e) {
    $accion = '';
    $tipo = 0;
    if ($e['bln_confirma'] == 1) {

      $accion = '

        <a id="actions1Invoker1" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown">
        <i class="fa fa-sliders-h"></i></a>
        <div class="dropdown-menu menu-custom dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker1" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;">
        <div class="card-header d-flex align-items-center py-3">
        <h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;">
        <div>
        <ul class="list-unstyled mb-0">';

      if ($e['id_status'] == 932 || $e['id_status'] == 933 || $e['id_status'] == 935 || $e['id_status'] == 936 || $e['id_status'] == 938 || $e['id_status'] == 938 || $e['id_status'] == 7959
      || $e['id_status'] == 939 || $e['id_status'] == 8194) {

        if($e['nro_frm_vt_cons']  == 0 && $e['nro_frm_vt_liq'] == 0){
          $accion .= '
            <li class="mb-1">';
          //'<a class="d-flex align-items-center link-muted py-2 px-3"  onclick="get_viatico_detalle_por_persona_encabezado('."'sustituir_empleado'".','.$e['id_empleado'].','.$e['reng_num'].')">
          /*$accion .= '
                <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="cargarCambioEmp(' . $e['id_empleado'] . ',' . $e['reng_num'] . ')">
                <i class="fa fa-edit mr-2"></i> Cambiar empleado
              </a>
            </li>';*/

            $accion.='
            <li class="mb-1">
              <a class="d-flex align-items-center link-muted py-2 px-3" id="infoSustitucion" data-id="'.$e['id_empleado'].'", data-type="'.$nombramiento.'" data-title="'.$e['reng_num'].'">
                <i class="fa fa-receipt mr-2"></i> Sustituir empleado
                </a>
            </li>
            ';
        }

      }
      if ($e['nro_frm_vt_cons'] == 0 || $e['nro_frm_vt_liq'] == 0) {
        /*$accion .= '
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="confirmarAusenciaSingular(' . $nombramiento . ',' . $e['id_empleado'] . ',' . $e['reng_num'] . ')">
              <i class="fa fa-times mr-2"></i> No asistió
            </a>
          </li>';*/

          $accion.='
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" id="infoAusenciaS" data-id="'.$e['id_empleado'].'", data-type="'.$nombramiento.'" data-title="'.$e['reng_num'].'">
              <i class="fa fa-times-circle mr-2"></i> Anular empleado
              </a>
          </li>
          ';
      }
      if ($e['nro_frm_vt_ant'] > 0) {
        $accion .= '
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimir_anticipo(' . $nombramiento . ',0,0,0,' . $e['id_empleado'] . ')">
              <i class="fa fa-print mr-2"></i> Anticipo
            </a>
          </li>';
      }
      if ($e['nro_frm_vt_cons'] > 0) {
        $accion .= '
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimir_constancia(' . $nombramiento . ',' . $e['id_empleado'] . ')">
              <i class="fa fa-print mr-2"></i> Constancia
            </a>
          </li>';
      }
      if ($e['nro_frm_vt_liq'] > 0) {
        $accion .= '
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimir_liquidacion(' . $nombramiento . ',0,0,0,' . $e['id_empleado'] . ')">
              <i class="fa fa-print mr-2"></i> Liquidación
            </a>
          </li>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimirRazonamiento(' . $nombramiento.','. $e['id_empleado'] . ')">
              <i class="fa fa-print mr-2"></i> Razonamientos
            </a>
          </li>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimir_complemento(' . $nombramiento . ',0,0,0,' . $e['id_empleado'] . ')">
              <i class="fa fa-print mr-2"></i> Resumen de Gastos
            </a>
          </li>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimir_con_fecha(' . $nombramiento . ',4,' . $e['id_empleado'] . ')">
              <i class="fa fa-print mr-2"></i> Nombramiento definitivo
            </a>
          </li>
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimir_con_fecha(' . $nombramiento . ',3,' . $e['id_empleado'] . ')">
              <i class="fa fa-print mr-2"></i> Informe
            </a>
          </li>
          ';
      }
      /*$accion .= '

        <li class="mb-1">
          <a class="d-flex align-items-center link-muted py-2 px-3" onclick="get_viatico_detalle_por_persona_encabezado(' . "'constancia_por_empleado'" . ',' . $e['id_empleado'] . ',' . $e['reng_num'] . ')">
            <i class="fa fa-clock mr-2"></i> Información
          </a>
        </li>

        </ul>
        </div></div></div></div>
        ';*/
        if($e['nro_frm_vt_liq'] > 0){
          $info = array('id'=> $e['id_empleado'], 'reng_num'=>$e['reng_num']);
          $datos = "{'id':'".$e['id_empleado']."','reng_num':'".$e['reng_num']."'}";
          $accion .= '
          <li class="mb-1">
            <a class="d-flex align-items-center link-muted py-2 px-3" id="infoLiquidacion" onclick="showFacturaDetalle('.$datos.')" data-id="'.$e['id_empleado'].'", data-type="'.$nombramiento.'" data-title="'.$e['reng_num'].'">
              <i class="fa fa-receipt mr-2"></i> Facturas
              </a>
          </li>


            ';
        }

        $accion.= '</ul>
        </div></div></div></div>';

    }

    /*if($e['anticipo']>0){

      }
      if($e['constancia']>0){

      }
      if($e['exterior']>0){

      }if($e['liquidacion']>0){

      }*/
    $progress = "";
    if ($e['bln_confirma'] == 1 && $e['status_comision'] == 1) {
      if ($e['nro_frm_vt_ant'] == 0) {
        $progress .= '<div style="margin-top:0px"><span class="text-info">Registrado</span>';
        $progress .= '<div class="progress progress-striped skill-bar " style="height:6px">
                  <div class="progress-bar progress-bar-striped bg-info" role="progressbar" aria-valuenow="10" aria-valuemin="10" aria-valuemax="100" style="width: 5%">
                  </div>
                </div></div>';
      }
      if ($e['nro_frm_vt_ant'] > 0 && $e['id_pais'] == 'GT') {
        if ($e['nro_frm_vt_cons'] == 0 && fecha_dmy($e['fecha_salida_lugar']) == '01-01-1900') {
          if ($e['nro_frm_vt_liq'] == 0) {
            $progress .= '<div style="margin-top:0px"><span class="text-info">Pendiente</span>';
            $progress .= '<div class="progress progress-striped skill-bar " style="height:6px">
                       <div class="progress-bar progress-bar-striped bg-info" role="progressbar" aria-valuenow="10" aria-valuemin="10" aria-valuemax="100" style="width: 25%">
                       </div>
                     </div></div>';
            $tipo = 1;
          }
        } else {
          if ($e['nro_frm_vt_cons'] == 0 && fecha_dmy($e['fecha_salida_lugar']) != '01-01-1900') {
            if ($e['nro_frm_vt_liq'] == 0) {
              $progress .= '<div style="margin-top:0px"><span class="text-info">En proceso</span>';
              $progress .= '<div class="progress progress-striped skill-bar " style="height:6px">
                         <div class="progress-bar progress-bar-striped bg-info" role="progressbar" aria-valuenow="10" aria-valuemin="10" aria-valuemax="100" style="width: 40%">
                         </div>
                       </div></div>';
            }
          }
        }
      }
      if ($e['nro_frm_vt_ant'] > 0 && $e['id_pais'] != 'GT') {
        if ($e['nro_frm_vt_ext'] == 0 && fecha_dmy($e['fecha_salida_lugar']) == '01-01-1900') {
          if ($e['nro_frm_vt_liq'] == 0) {
            $progress .= '<div style="margin-top:0px"><span class="text-info">Pendiente</span>';
            $progress .= '<div class="progress progress-striped skill-bar " style="height:6px">
                      <div class="progress-bar progress-bar-striped bg-info" role="progressbar" aria-valuenow="10" aria-valuemin="10" aria-valuemax="100" style="width: 25%">
                      </div>
                    </div></div>';
            $tipo = 1;
          }
        } else {
          if ($e['nro_frm_vt_ext'] == 0 && fecha_dmy($e['fecha_salida_lugar']) != '01-01-1900') {
            if ($e['nro_frm_vt_liq'] == 0) {
              $progress .= '<div style="margin-top:0px"><span class="text-info">En proceso</span>';
              $progress .= '<div class="progress progress-striped skill-bar " style="height:6px">
                        <div class="progress-bar progress-bar-striped bg-info" role="progressbar" aria-valuenow="10" aria-valuemin="10" aria-valuemax="100" style="width: 40%">
                        </div>
                      </div></div>';
              $tipo = 1;
            }
          }
        }
      }

      if ($e['nro_frm_vt_ant'] > 0) {
        if ($e['nro_frm_vt_cons'] > 0 || $e['nro_frm_vt_ext'] > 0) {
          if ($e['nro_frm_vt_liq'] == 0 && $e['porcentaje_real'] > 0 && $e['facturas'] == 0) {
            $progress .= '<div style="margin-top:0px"><span class="text-info">Constancia</span>';
            $progress .= '<div class="progress progress-striped skill-bar " style="height:6px">
                       <div class="progress-bar progress-bar-striped bg-info" role="progressbar" aria-valuenow="10" aria-valuemin="10" aria-valuemax="100" style="width: 80%">
                       </div>
                     </div></div>';
            $tipo = 2;
          } else if ($e['nro_frm_vt_liq'] == 0 && $e['porcentaje_real'] > 0 && $e['facturas'] > 0) {
            $progress .= '<div style="margin-top:0px"><span class="text-info">En liquidación</span>';
            $progress .= '<div class="progress progress-striped skill-bar " style="height:6px">
                       <div class="progress-bar progress-bar-striped bg-info" role="progressbar" aria-valuenow="10" aria-valuemin="10" aria-valuemax="100" style="width: 95%">
                       </div>
                     </div></div>';
          }
        }
      }

      if ($e['nro_frm_vt_liq'] > 0) {
        $progress .= '<div style="margin-top:0px"><span class="text-success">Liquidado</span>';
        $progress .= '<div class="progress progress-striped skill-bar " style="height:6px">
                  <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="10" aria-valuemin="10" aria-valuemax="100" style="width: 100%">
                  </div>
                </div></div>';
      }
    } else {
      $progress .= '<div style="margin-top:0px"><span class="text-danger">Anulado</span>';
      $progress .= '<div class="progress progress-striped skill-bar " style="height:6px">
                <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" aria-valuenow="10" aria-valuemin="10" aria-valuemax="100" style="width: 100%">
                </div>
              </div></div>';
    }
    //$sustituye=($e['reng_sustituye']>0)?' -'.$e['id_empleado']:'';
    $emp = $e['primer_nombre'] . ' ' . $e['segundo_nombre'] . ' ' . $e['tercer_nombre'] . ' ' . $e['primer_apellido'] . ' ' . $e['segundo_apellido'] . ' ' . $e['tercer_apellido']; //.$sustituye;
    $resta = ($e['bln_anticipo'] == 0) ? number_format($e['monto_real'], 2, ".", ',') : number_format(($e['monto_real'] - $e['monto_asignado']), 2, '.', ',');
    $resta_ = ($e['bln_anticipo'] == 0) ? $e['monto_real'] : ($e['monto_real'] - $e['monto_asignado']);
    $vc = ($e['id_pais'] == 'GT') ? $e['nro_frm_vt_cons'] : $e['nro_frm_vt_ext'];
    $chk = ($e['id_status'] == 938 || $e['id_status'] == 7959 || $e['id_status'] == 939 || $e['id_status'] == 8194) ? 1 : 0;
    $anticipo = ($e['bln_anticipo'] == 0) ? '*' : '';

    $monto_real = $e['monto_real'] + $e['totalReint'];
    $total_reintegro = 0;
    if ($e['bln_anticipo'] == 1) {
      $reesta = $resta_;

      $resta_ = ($resta_ < 0) ? $resta_ * -1 : '';
      //echo $resta_;
      //if($monto_real<$e['monto_asignado'] && $e['totalReint']>0){
      if ($monto_real < $e['monto_asignado']) {

        if ($resta_ > $e['totalReint']) {
          $total_reintegro = ($e['otros_gastos'] > 0) ? '' . number_format(($e['otros_gastos'] - $resta_), 2, '.', ',') . '' : number_format(($resta_ - $e['totalReint']), 2, '.', ',');
        }
      } else {
        //$total_reintegro=number_format($resta,2,'.',',');
        $result = ($e['porcentaje_real'] < $e['porcentaje_proyectado']) ? ($e['otros_gastos'] - floatval($reesta)) - $e['totalReint'] : 0;
        $total_reintegro = ($e['otros_gastos'] > 0) ? number_format($result, 2, '.', ',') : number_format((($monto_real < $e['monto_asignado']) ? $e['totalReint'] : '0.00'), 2, '.', ',');
      }
      if ($monto_real > $e['monto_asignado'] && $e['totalReint'] > 0) {
        if ($resta_ > $e['totalReint']) {
          $total_reintegro = number_format($e['totalReint'], 2, '.', ',');
        }
      }

      //$total_reintegro.= ' --+ '. $reesta;

    }
    $mr = 0;
    $re_a = 0;
    $com = 0;

    if ($e['porcentaje_real'] < $e['porcentaje_proyectado']) {
      $re_a = ($e['porcentaje_proyectado'] - $e['porcentaje_real']) * 420;
    } else if ($e['porcentaje_real'] > $e['porcentaje_proyectado']) {
      $com = ($e['porcentaje_real'] - $e['porcentaje_proyectado']) * 420;
    }

    $complemento = 0;
    if ($e['id_pais'] == 'GT') {
      if ($e['fecha'] > '2021-06-30') {
        if ($com > 0) {
          $complemento = $com;
        } else {
          $complemento = '0.00';
        }
      } else {
        if ($e['monto_real'] > 0 || $e['monto_real'] == $e['monto_asignado']) {
          if ($resta > 0) {
            $complemento = '<span class="text-info"> ' . $resta . '</span>';
          } else {
            $complemento = '0.00';
          }
        } else {
          $complemento = '0.00';
        }
      }
    }

    if ($e['id_pais'] == 'GT') {
      if ($e['fecha'] > '2021-06-30') {
        $mr = 'Q ' . number_format(($e['monto_asignado'] + $e['otros_gastos'] - $e['totalReint'] - $re_a + $complemento), 2, '.', ',');
      } else {
        $mr = 'Q ' . number_format((($e['monto_real'] > 0) ? $e['monto_real'] : $e['monto_real'] * -1), 2, ".", ",");
      }
    } else {
      $mr = '* ' . number_format($e['tipo_cambio'], 2, ".", ",") . ' = Q ' . number_format(($e['monto_real'] * $e['tipo_cambio']), 2, ".", ",");
    }


    $bln_cheque = ($e['bln_cheque'] == 1) ? '<span class="stado_success" style="margin-left:0px"></span>' : '';


    $sub_array = array(
      'DT_RowId' => $e['id_empleado'],
      'codigo' => '',
      'reng_num' => $e['reng_num'],
      'id_persona' => $e['id_empleado'],
      'bln_confirma' => $e['bln_confirma'],
      'foto' => $e['id_empleado'],
      'empleado' => ($e['bln_confirma'] == 0 || $e['status_comision'] == 0) ? '<i class="fa fa-times-circle text-danger"> </i> ' . $emp : '<i class="fa fa-check-circle text-info"> </i> ' . $emp . ' ' . $bln_cheque,
      'asistencia' => ($e['bln_confirma'] == 1) ? 'CONFIRMADO' : 'NO CONFIRMADO',
      'va' => $e['nro_frm_vt_ant'],
      'vc' => (date('Y-m-d H:i:s', strtotime($e['fecha_procesado'])) > '2022-03-24 23:59:59') ? $e['nro_frm_vt_ant'] : $vc,
      'vl' => (date('Y-m-d H:i:s', strtotime($e['fecha_procesado'])) > '2022-03-24 23:59:59') ? $e['nro_frm_vt_ant'] : $e['nro_frm_vt_liq'],
      'p_p' => number_format($e['porcentaje_proyectado'], '2', '.', ',') . '%',
      'p_r' => number_format($e['porcentaje_real'], '2', '.', ',') . '%',
      'm_p' => ($e['id_pais'] == 'GT') ? $anticipo . ' Q ' . number_format($e['monto_asignado'], 2, ".", ",") : '$ ' . number_format($e['monto_asignado'], 2, ".", ","),
      'm_r' => $mr,
      'complemento' => ($complemento > 0) ? '<span class="text-info">' . number_format($complemento, 2, '.', ',') . '</span' : $complemento,
      'reintegro_' => ($e['fecha'] > '2021-06-30') ? (($re_a > 0) ? '<span class="text-danger">' . number_format($re_a, 2, '.', ',') . '</span>' : '0.00') : (($total_reintegro > 0 || $e['otros_gastos'] > 0) ? '<span class="text-danger">' . $total_reintegro . '</span>' : '0.00'),
      'reintegro' => ($e['totalReint'] > 0) ? '<span class="text-danger">' . number_format($e['totalReint'], 2, '.', ',') . '</span>' : '0.00',
      //'reintegro'=>($e['id_pais']=='GT')?($e['monto_real']>$e['monto_asignado'] || $e['monto_real']==$e['monto_asignado'])?'<span class="text-success">+ '.number_format(($e['monto_real']-$e['monto_asignado']), 2, ".", ",").'</span>':($e['monto_real']>0)?'<span class="text-danger">'.number_format(($e['monto_real']-$e['monto_asignado']), 2, ".", ",").'</span>':'0.00':'0.00',
      'estado' => $progress, //($e['nro_frm_vt_ant']>0)?'Anticipo entregado':($e['nro_frm_vt_cons']>0)?'Constancia nacional':($e['nro_frm_vt_ext']>0)?'Constancia extranjero':($e['nro_frm_vt_liq']==1)?'Liquidado':'NO',
      'cheque' => ($e['bln_cheque'] == 1) ? 'SI' : 'NO',
      'nro_cheque' => $e['nro_cheque'],
      'dato' => $chk,
      'accion' => $accion,
      'liquidado' => ($e['nro_frm_vt_liq'] > 0) ? 0 : 1,
      'tipo' => $tipo
    );
    $data[] = $sub_array;
    //$data[]=$e;

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
