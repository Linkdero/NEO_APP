
<?php
include_once '../../../../inc/functions.php';
include_once '../functions.php';
include_once 'individual/function_individual.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();
  $empleados = array();
  $id_nombramiento = $_POST['nombramiento'];
  $id_empleado = $_POST['id_empleado']; //($_POST['id_empleado'] > 0) ? $_POST['id_empleado'] : NULL;
  $dia = $_POST['dia'];
  $mes = $_POST['mes'];
  $year = $_POST['year'];

  $clasei = new hoja;
  $data = array();
  if($id_nombramiento > 0){
    //inicio
    $clase1 = new viaticos;
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "EXEC sp_sel_valida_exterior ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_nombramiento));
    $pais = $q0->fetch();

    $empleados = $clasei->get_liquidacion_individual($id_nombramiento, $id_empleado);

    if ($pais['id_pais'] == 'GT') {
      foreach ($empleados as $e) {
        $puestoComisionado = $clasei->getPuestoEmpleado($e['id_empleado'],$e['fecha_nom']);
        $contratoComisionado = $clasei->getContratoEmpleado($e['id_empleado'],$e['fecha_nom']);
        $apoyoEmpleado = $clasei->getApoyoEmpleado($e['id_empleado']);

        $cargo = (!empty($apoyoEmpleado['puesto'])) ? $apoyoEmpleado['puesto'] : '';
        $partida = (!empty($apoyoEmpleado['partida'])) ? $apoyoEmpleado['partida'] : '';
        $cargoN = ($puestoComisionado['fecha_inicio'] > $contratoComisionado['fecha_inicio']) ? $puestoComisionado['pueston'] : $contratoComisionado['pueston'];
        $partidaN = ($puestoComisionado['fecha_inicio'] > $contratoComisionado['fecha_inicio']) ? $puestoComisionado['partida'] : $contratoComisionado['partida'];
        //echo json_encode($puestoComisionado) ;
        $partidaN = (!empty($partidaN)) ? $partidaN : $partida;

        $sueldoa = $apoyoEmpleado['sueldo'];

        $sueldo = (!empty($puestoComisionado['sueldo'])) ? $puestoComisionado['sueldo'] : $contratoComisionado['sueldo'];

        $sueldoN = (!empty($sueldo)) ? $sueldo : $sueldoa;

        $resta = $e['total_real'] - $e['monto_asignado']; // se agregó total reintegro
        if ($resta < 0) {
          $resta = $resta * -1;
        }

        $resta = number_format((($resta)), 2, '.', ',');
        //echo $e['lugar'];
        $lugar = $e['lugar'];
        $dep = '';
        if ($e['descripcion_lugar'] == 1) {
          $valor = $clase1->get_historial_viatico($e['vt_nombramiento']);
          $va = json_decode($valor['val_nuevo'], true);
          $keys = array_keys($va);

          $deptos = $clase1->get_departamentos($pais['id_pais']);
          $munis = $clase1->get_municipios($va['id_departamento']);

          foreach ($munis["data"] as $m) {
            $dep .= ($va['id_municipio'] == $m['id_municipio']) ? $m['nombre'] : '';
          }
          foreach ($deptos["data"] as $d) {
            $dep .= ($va['id_departamento'] == $d['id_departamento']) ? '-' . $d['nombre'] : '';
          }
          $lugar = $dep . '-' . $e['pais'];
        }
        // varios destinos
        $array_destinos = array();

        if ($e['descripcion_lugar'] == 2) {
          $nombramientos = $clase1->get_nombramiento_definitivo_by_id('', '', '', $id_nombramiento);

          $data_f1 = array();
          $data_f4 = array();

          $data_h1 = array();
          $data_h2 = array();

          foreach ($nombramientos as $n) {
            $data_f1[] = (date('d-m-Y', strtotime($n['fecha_salida'])));
            $data_f4[] = (date('d-m-Y', strtotime($n['fecha_regreso'])));

            $data_h1[] = ($n['hora_salida_e']);
            $data_h2[] = ($n['hora_regreso_e']);
          }

          $valores = $clase1->get_historial_viatico_destinos($id_nombramiento);

          $x = 0;
          $y = 0;
          $validacion = '';
          $total = count($valores);
          $incremental = 0;
          foreach ($valores as $key => $valor) {

            $x++;
            //echo $key;
            if ($key < $total - 1) {
              $y = $key + 1;
            } else {
              $y = 0;
            }
            //echo $y;
            $h_ant = $valores[$key]['hora_salida_e'];
            $h_act = $valores[$y]['hora_salida_e'];

            $day_i = $valor['hora_salida_e'];
            $day_f = $valor['hora_regreso_e'];
            $f_i = $valor['fecha_ini'];
            $f_f = $valor['fecha_fin'];
            $h_i = $valor['h_ini'];
            $h_f = $valor['h_fin'];

            $id_actual = $valores[$key]['vt_nombramiento']; //$s[$key+1]['id_empleado'];
            $id_siguiente = $valores[$y]['vt_nombramiento'];

            $valor_s = '';
            $valor_sig = '';
            $valor_ant = '';
            $cual = '';
            if ($x == 1) {
              $valor_sig = min($data_h1);
              $valor_ant = $valores[$y]['hora_salida_e'];
            } else if ($x > 1 && $x < $total) {
              $valor_ant = $valores[$y]['hora_salida_e'];
              $valor_sig = $valores[$key]['hora_regreso_e'];
              $cual = 'siguiente=> ' . $valores[$y]['hora_salida_e'];
            } else if ($x == $total) {
              $valor_sig = $valores[$key]['hora_salida_e'];
              $valor_ant = max($data_h2);
              $cual = 'final';
            }

            $porcentaje = $clase1->get_porcentaje_por_destino($id_nombramiento, $valor['dias'], $valor_sig, $valor_ant, $valor['dia_inicio']);

            if ($valor['bln_confirma'] == 1) {
              $sub_array = array(
                'dep' => $valor['muni'] . '-' . $valor['depto'] . '-' . $e['pais'],
                'porcentaje' => $porcentaje,
                /*'f_i'=>$f_i,
                'f_f'=>$f_f,*/
                'h_i' => $valor_sig,
                'h_f' => $valor_ant,
                'valor' => $valor_s,
                'cual' => $cual

              );
              $array_destinos[] = $sub_array;
            }
          }
        }

        if(!empty($e['destino'])){
          $valores = $clase1->getDestinosByEmpleado($id_nombramiento,$e['id_empleado']);
          foreach($valores as $valor){

            $sub_array = array(
              'dep'=>$valor['municipio'].'-'.$valor['departamento'].'-'.$e['pais'],
              'porcentaje'=>'',
              'f_ini'=>fecha_dmy($valor['fecha_ini']),
              'f_fin'=>fecha_dmy($valor['fecha_fin']),
              'h_i'=>$valor['h_ini'],
              'h_f'=>$valor['h_fin'],
              'valor'=>'',
              'cual'=>''
            );
            $array_destinos[] = $sub_array;
          }
        }


        //fin varios destinos
        $a_favor = '';
        $total_reintegro = '';
        $monto_real = ($e['otros_gastos'] > 0) ? $e['total_real'] : $e['total_real'] + $e['totalReint'];
        $resta = ($e['bln_anticipo'] == 0) ? $e['total_real'] : $e['total_real'] - $e['monto_asignado'];
        $resta_ = ($e['bln_anticipo'] == 0) ? $e['total_real'] : ($e['total_real'] - $e['monto_asignado']);
        //echo ;
        $re_a = 0;
        if ($e['bln_anticipo'] == 1) {
          if (date('Y-m-d', strtotime($e['fecha_liquidacion'])) > '2021-06-10') {

            if ($e['porcentaje_real'] < $e['porcentaje_proyectado']) {
              $re_a = ($e['porcentaje_proyectado'] - $e['porcentaje_real']) * 420;
            }
            $total_reintegro =/*($e['otros_gastos']>0)?*/ floatval(/*$e['otros_gastos']-*/$e['totalReint'] + $re_a);/*:floatval($e['totalReint']+$re_a);*/
          } else {
            $reesta = $resta_;

            $resta_ = ($resta_ < 0) ? $resta_ * -1 : '';
            if ($monto_real < $e['monto_asignado']) {
              if ($resta_ > $e['totalReint']) {
                $total_reintegro = ($e['otros_gastos'] > 0) ? $e['otros_gastos'] - $resta_ : $resta_ - $e['totalReint'];
              }
              $total_reintegro = $resta;
            } else {
              $total_g = ($e['porcentaje_real'] < $e['porcentaje_proyectado']) ? ($e['otros_gastos'] - floatval($reesta)) : 0;
              $total_reintegro = ($e['otros_gastos'] > 0) ? $e['otros_gastos'] - $resta : $e['totalReint'];
            }
          }
        } else {
          $total_reintegro = '0.00';
        }
        $a_favor = 0;
        //echo 'total R: '.$total_reintegro. ' - - '. date('Y-m-d', strtotime($data_f1[0]));
        $operacion = (floatval($total_reintegro) < 0) ? '' . (floatval($total_reintegro) * -1) . '' : floatval($total_reintegro);
        if ($e['bln_anticipo'] == 1) {
          if (date('Y-m-d', strtotime($e['fecha_liquidacion'])) > '2021-06-10') {
            if ($pais['id_pais'] == 'GT') {
              if ($e['porcentaje_real'] > $e['porcentaje_proyectado']) {
                $a_favor = ($e['porcentaje_real'] - $e['porcentaje_proyectado']) * 420;
              } else {
                $a_favor = '0.00';
              }
            } else {
              $a_favor = '0.00';
            }
          } else {
            if ($pais['id_pais'] == 'GT') {
              if ($e['total_real'] > 0 || $e['total_real'] > $e['monto_asignado']) {
                if (floatval($operacion) >= 0) {
                  $a_favor = '' . floatval($resta) + floatval($operacion) . '';
                  //echo $a_favor;
                } else {
                  $a_favor = '0.00';
                }
              } else {
                $a_favor = '0.00';
              }
            } else {
              $a_favor = '0.00';
            }
          }
        } else {
          $a_favor = $resta;
        }

        $total_reintegro = ($id_nombramiento == 30888) ? 63 + $total_reintegro : $total_reintegro;
        $a_favor = ($id_nombramiento == 30888) ? 84 : $a_favor;
        $a_favor = ($id_nombramiento == 31952) ? 347 :$a_favor;

        $a_favor = ($id_nombramiento == 31973 && $e['id_empleado']== 5627) ? $a_favor-200 :$a_favor;
        $a_favor = ($id_nombramiento == 32019 && $e['id_empleado']== 412) ? $e['total_real']-300 :$a_favor;
        $total_ = 0;
        $monto_asignado = 0;
        if ($e['bln_anticipo'] == 1) {
          $monto_asignado = $e['monto_asignado'];
        }

        $rein = 0;
        if ($e['bln_anticipo'] == 1) {
          $rein = $e['totalReint'];
        }

        if ($e['fecha_liquidacion'] > '2021-06-30') {

          $total_ = ($e['porcentaje_real'] > $e['porcentaje_proyectado']) ? number_format((($monto_asignado) + $e['otros_gastos'] - $rein - ($re_a) + ($a_favor)), 2, '.', ',') : number_format(($e['monto_asignado'] + $e['otros_gastos'] - $e['totalReint'] - $re_a), 2, '.', ',');
        } else {
          $total_ = ($e['total_real'] > $e['monto_asignado']) ? number_format(($e['total_real']), 2, ".", ",") : number_format((($e['total_real'] > 0) ? $e['total_real'] : $e['total_real'] * -1), 2, ".", ",");
        }
        $totally = 0;
        if ($e['bln_anticipo'] == 1) {
          $totally = $monto_asignado + $e['otros_gastos'] - $e['totalReint'] - $re_a + $a_favor;
        } else {
          $totally = $a_favor;
          $totally = ($id_nombramiento == 31973 && $e['id_empleado']== 5627) ? $totally + 200 :$a_favor;
          $totally = ($id_nombramiento == 32019 && $e['id_empleado']== 412) ? $e['total_real'] :$a_favor;
        }


        if($e['bln_anticipo'] == 0){
          $total_ = number_format(($e['total_real']+ $e['otros_gastos']), 2, ".", ",") ;

          $total_= number_format(($e['total_real']), 2, ".", ",") ;
          $a_favor = ($e['total_real']- $e['otros_gastos']);//number_format(($e['total_real']- $e['otros_gastos']), 2, ".", ",") ;;
        }

        $destino = (!empty($e['destino'])) ? $e['destino'] : '';
        $descripcionlugar = (!empty($destino)) ? 2 : $e['descripcion_lugar'];



        $sub_array = array(
          'formulario' => number_format($e['nro_frm_vt_liq'], 0, "", ","),
          //'id_empleado'=>$e['id_empleado'],
          //'tipo_contrato'=>$e['tipo_contrato'],
          'monto_num' => number_format($e['total_real'], 2, '.', ','),
          'monto_real' => ($e['otros_gastos'] > 0) ? number_format(($e['total_real'] - $e['otros_gastos'] + $e['totalReint']), 2, '.', ',') : number_format(($e['total_real'] + $e['totalReint']), 2, '.', ','),
          'monto_anticipo' => ($e['bln_anticipo'] == 1) ? number_format($e['monto_asignado'], 2, ".", ",") : '0.00',
          'moneda' => $e['moneda'],
          'monto_letras' => (!empty($e['monto_en_letras'])) ? $e['monto_en_letras'] : '00/100',
          'cuota' => $e['cuota'],
          'total_real_cabecera' => ($e['fecha_liquidacion'] > '2021-06-30') ? number_format(($monto_asignado + $e['otros_gastos'] - $rein - $re_a + $a_favor), 2, '.', ',') : number_format((($e['total_real'] < 0) ? $e['total_real'] * -1 : $e['total_real']), 2, ".", ","),
          'total_real' => ($e['fecha_liquidacion'] > '2021-06-30') ? '' . number_format(($totally - $e['otros_gastos']), 2, '.', ',') . '' : (($e['otros_gastos'] > 0) ? number_format(($e['total_real'] - $e['otros_gastos']), 2, ".", ",") : number_format((($e['total_real'] > 0) ? $e['total_real'] : $e['total_real'] * -1), 2, ".", ",")),
          'total_real_total' => ($e['fecha_liquidacion'] > '2021-06-30') ? '' . number_format(($totally), 2, '.', ',') . '' : number_format((($e['total_real'] > 0) ? $e['total_real'] : $e['total_real'] * -1), 2, ".", ","),
          'total_real2' => number_format($e['total_real2'], 2, ".", ","),
          'porcentaje_proyectado' => number_format($e['porcentaje_proyectado'], 2, ".", ""),
          'porcentaje_real' => number_format($e['porcentaje_real'], 2, ".", ""),
          'justificacion' => $e['justificacion'],
          'monto_descuento_anticipo' => $e['monto_descuento_anticipo'],
          'otros_gastos' => number_format($e['otros_gastos'], 2, ".", ","),
          'total' => $e['total'],
          'justificacion_hospedaje' => $e['justifica_hospedaje'],
          'tipo_comision' => $e['tipo_comision'],

          'destino' => $lugar,


          'num_dias' => (date('Y-m-d', strtotime($e['fecha_liquidacion'])) > '2021-06-25') ? number_format((($e['porcentaje_real'] < 1) ? 1 : $e['porcentaje_real']), 1, '.', ',') : $e['dias_nombramiento'],
          'num_dias_detalle' => $e['dias_detalle'],
          //'nombramiento'=>$e['nro_nombramiento'],
          'numero_viatico_anticipo' => number_format($e['numero_viatico_anticipo'], 0, '.', ','),
          'hoy' => (date('Y-m-d', strtotime($e['fecha_liquidacion'])) > '2021-06-25') ? 'Guatemala ' . fechaCastellano($e['fecha_liquidacion']) : 'Guatemala ' . $e['fecha_liquidacion'],

          //'fecha_solicitud'=>$e['fecha'],
          'emp' => $e['nombre'],
          'cargo' => (!empty($cargoN)) ? $cargoN : $cargo,
          'partida' => $partidaN,
          'sueldo' => number_format($sueldoN,2,".",","),
          'monto_asignado_sustituido' => $e['monto_asignado_sustituido'],
          'reng_sustituye' => $e['reng_sustituye'],
          'resolucion' =>  $clasei->retornaResolucion($e['nro_frm_vt_liq'],3),
          'hospedaje' => $e['hospedaje'],
          'hospedaje2' => $e['hospedaje2'],
          'reintegro_alimentacion' => $e['reintegro_alimentacion'],
          'total_reintegro_' => ($e['totalReint'] > 0) ? number_format($e['totalReint'], 2, ".", ",") : '',
          'total_reintegro' => ($total_reintegro < 0) ? '' . number_format(($total_reintegro * -1), 2, '.', ',') . '' : number_format($total_reintegro, 2, '.', ','),
          'a_favor' => number_format($a_favor + $e['otros_gastos'], 2, '.', ','), //($e['bln_anticipo']==1)?($e['total_real']>0 && $e['total_real']>$e['monto_asignado'])?($resta>0)?''.number_format($resta+$e['totalReint'],2,'.',',').'':''.$resta.'' :'0.00':number_format($e['total_real'],2,'.',','),
          //'total_reintegro'=>($e['total_real']>0 && $e['total_real']==$e['monto_asignado'] || $e['total_real']>0 && $e['total_real']>$e['monto_asignado'])?($resta>0)?''.number_format($resta,2,".",",").'':''.number_format($resta,2,".",",").'' :'0.00' ,
          //'a_favor'=>($e['total_real']>0 && $e['total_real']==$e['monto_asignado'] || $e['total_real']>0 && $e['total_real']>$e['monto_asignado'])?($resta>0)?''.number_format($resta,2,".",",").'':''.number_format($resta,2,".",",").'' :'0.00' ,
          'a_favor_' => ($e['total_real'] > $e['monto_asignado']) ? number_format(($e['total_real'] - $e['monto_asignado']) + $e['totalReint'], 2, ".", ",") : number_format(($e['total_real'] - $e['monto_asignado']) + $e['totalReint'], 2, ".", ","), //number_format(($e['total']-$e['totalReint']),2,".",","),//'0.00',
          'total_' => $total_,
          'reintegro_texto' => ($e['bln_anticipo'] == 1) ? (($e['totalReint'] > 0) ? 'Reintegro por alimentación y hospedaje según Acuerdos Gubernativos No. 106-2016 / 148-2016 / 35-2017' : '') : (($e['totalReint'] > 0) ? 'Gastos no comprobados por alimentación según Acuerdos Guabernativos No. 106-2016 / 148-2016 / 35-2017' : ''),
          'utilizado' => ($e['bln_anticipo'] == 0) ? '    NO UTILIZADO' : '',
          'bln_cheque' => (!empty($e['bln_cheque'])) ? $e['bln_cheque'] : '',
          'desc_tipo_cambio' => '',
          'estado_viatico'=>$e['estado_viatico'],
          'descripcion_lugar' => $descripcionlugar,//$e['descripcion_lugar'],
          'destinos' => $array_destinos //:'',
        );
        $data[] = $sub_array;
      }
    } else {
      foreach ($empleados as $e) {

        $puestoComisionado = $clasei->getPuestoEmpleado($e['id_empleado'],$e['fecha']);
        $contratoComisionado = $clasei->getContratoEmpleado($e['id_empleado'],$e['fecha']);
        $apoyoEmpleado = $clasei->getApoyoEmpleado($e['id_empleado']);

        $cargo = (!empty($apoyoEmpleado['puesto'])) ? $apoyoEmpleado['puesto'] : '';
        $partida = (!empty($apoyoEmpleado['partida'])) ? $apoyoEmpleado['partida'] : '';
        $cargoN = ($puestoComisionado['fecha_inicio'] > $contratoComisionado['fecha_inicio']) ? $puestoComisionado['pueston'] : $contratoComisionado['pueston'];
        $partidaN = ($puestoComisionado['fecha_inicio'] > $contratoComisionado['fecha_inicio']) ? $puestoComisionado['partida'] : $contratoComisionado['partida'];
        //echo json_encode($puestoComisionado) ;
        $partidaN = (!empty($partidaN)) ? $partidaN : $partida;

        $cargoN = (!empty($cargoN)) ? $cargoN : $cargo;

        $resta = $e['total_real'] - $e['monto_asignado']; // se agregó total reintegro
        if ($resta < 0) {
          $resta = $resta * -1;
        }

        $resta = $resta;
        $a_favor = '';
        $total_reintegro = '';
        $monto_real = ($e['otros_gastos'] > 0) ? floatval($e['total_real']) : floatval($e['total_real']) + floatval($e['totalReint']);
        $resta_ = $monto_real - $e['monto_asignado'];

        if ($e['bln_anticipo'] == 1) {
          if ($monto_real < $e['monto_asignado']) {
            $total_reintegro = $resta;
          } else {
            $total_g = ($e['total_real'] - $e['otros_gastos']);
            $total_reintegro = ($e['otros_gastos'] > 0) ? floatval($e['otros_gastos']) - floatval($resta) : floatval($resta);
          }

          if ($monto_real > $e['monto_asignado'] && $e['totalReint'] > 0) {
            $total_reintegro = $e['totalReint'];
          }
        }
        if ($e['bln_anticipo'] == 1) {

          if ($e['total_real'] > 0 && $monto_real > $e['monto_asignado']) {

            if ($resta_ > 0) {
              $a_favor = ($e['otros_gastos'] > 0) ? $e['otros_gastos'] : floatval($resta_);
            } else {
              $a_favor = floatval($resta_);
            }
          } else {
            $a_favor = '0.00';
          }
        } else {
          $a_favor = $e['total_real'];
        }

        $total_reintegro = '';
        if ($e['bln_anticipo'] == 1) {
          if ($monto_real < $e['monto_asignado']) {
            $total_reintegro = $resta . '';
          } else {
            $total_g = ($e['total_real'] - $e['otros_gastos']);
            $total_reintegro = '' . ($e['otros_gastos'] > 0) ? floatval($e['otros_gastos']) - floatval($resta) : floatval($resta);
          }

          if ($monto_real > $e['monto_asignado'] && $e['totalReint'] > 0) {
            $total_reintegro = '' . $e['totalReint'];
          }
        }
        $sub_array = array(
          'formulario' => number_format($e['nro_frm_vt_liq'], 0, "", ","),
          //'id_empleado'=>$e['id_empleado'],
          //'tipo_contrato'=>$e['tipo_contrato'],
          'monto_num' => number_format($e['total_real'], 2, ".", ","),
          'monto_real' => ($e['otros_gastos'] > 0) ? number_format(($e['total_real'] + $e['totalReint'] - $e['otros_gastos']), 2, ".", ",") : number_format(($e['total_real'] + $e['totalReint']), 2, '.', ','),
          'total_real_cabecera' => number_format($e['total_real'], 2, ".", ","),
          'total_real_total' => number_format($e['total_real'], 2, ".", ","),
          'monto_anticipo' => number_format($e['monto_asignado'], 2, ".", ","),
          'moneda' => $e['moneda'],
          'monto_letras' => $e['monto_en_letras'],
          'cuota' => $e['cuota'],
          'total_real' => ($e['otros_gastos'] > 0) ? number_format(($e['total_real'] - $e['otros_gastos']), 2, ".", ",") : number_format($e['total_real'], 2, ".", ","),
          'total_real2' => number_format($e['total_real2'], 2, ".", ","),
          'porcentaje_proyectado' => number_format($e['porcentaje_proyectado'], 2, ".", ""),
          'porcentaje_real' => number_format($e['porcentaje_real'], 2, ".", ""),
          'justificacion' => $e['justificacion'],
          'monto_descuento_anticipo' => $e['monto_descuento_anticipo'],
          'otros_gastos' => number_format($e['otros_gastos'], 2, ".", ","),
          'total' => $e['total'],
          'justificacion_hospedaje' => $e['justifica_hospedaje'],
          'tipo_comision' => $e['tipo_comision'], //(!empty($e['tipo_comision']))?$e['tipo_comision']:'',

          'destino' => $e['lugar'],


          'num_dias' => $e['dias_nombramiento'],
          'num_dias_detalle' => $e['dias_detalle'],
          //'nombramiento'=>$e['nro_nombramiento'],
          'numero_viatico_anticipo' => number_format($e['numero_viatico_anticipo'], 0, '.', ','),
          'hoy' => (date('Y-m-d', strtotime($e['fecha_liquidacion'])) > '2021-06-25') ? 'Guatemala ' . fechaCastellano($e['fecha_liquidacion']) : 'Guatemala ' . $e['fecha_liquidacion'],

          //'fecha_solicitud'=>$e['fecha'],
          'emp' => $e['nombre'],
          'cargo' => $cargoN,//$e['plaza'],
          'partida' => $partidaN,//$e['partida'],
          'sueldo' => number_format($e['sueldo'], 2, ".", ","),
          'monto_asignado_sustituido' => $e['monto_asignado_sustituido'],
          'reng_sustituye' => $e['reng_sustituye'],
          'resolucion' => $clasei->retornaResolucion($e['nro_frm_vt_liq'],3),/*($e['nro_frm_vt_liq'] >45000) ? 'AUTORIZADO POR LA CONTRALORIA GENERAL DE CUENTAS SEGUN RESOLUCION No. F.O. –JM-47-2022C 000983 GESTIÓN NÚMERO: 644611 DE FECHA 09-03-2022
                   DE CUENTA S1-22 · 15000 Formulario de Viatico Liquidación en Forma Electrónica DEL No. 45001 AL 60000 SIN SERIE No. CORRELATIVO Y FECHA DE
                  AUTORIZACION DE IMPRESION 299-2022 DEL 27-04-2022 · ENVIO FISCAL 4-ASCC 19645 DEL 27-04-2022 LIBRO 4-ASCC FOLIO 157 ':$e['resolucion'],*/
          'hospedaje' => $e['hospedaje'],
          'hospedaje2' => $e['hospedaje2'],
          'reintegro_alimentacion' => $e['reintegro_alimentacion'],
          'total_reintegro_' => ($e['totalReint'] > 0) ? number_format($e['totalReint'], 2, ".", ",") : '',
          'total_reintegro' => (!empty($total_reintegro)) ? number_format($total_reintegro, 2, ".", ",") : 0.00, //($e['totalReint']>0)?number_format($e['totalReint'], 2, ".",","):'',
          'a_favor' => number_format($a_favor, 2, '.', ','), //($e['total_real']>$e['monto_asignado'])?number_format((($e['total_real']-$e['monto_asignado'])),2,".",","):number_format($e['total_real'],2,".",","),//number_format(($e['total_real']*$e['tipo_cambio']), 2, ".", ","),
          'total_' => ($e['total_real'] > $e['monto_asignado']) ? number_format((($e['total_real'] - $e['monto_asignado']) + $e['monto_asignado']), 2, ".", ",") : number_format($e['total_real'], 2, ".", ","),
          'reintegro_texto' => ($e['totalReint'] > 0) ? 'Reintegro por alimentación y hospedaje según Acuerdos Gubernativos No. 148-2016 / 106-2016' : '',
          'utilizado' => ($e['bln_anticipo'] == 0) ? '    NO UTILIZADO' : '', //:''
          'bln_cheque' => (!empty($e['bln_cheque'])) ? $e['bln_cheque'] : '',
          'desc_tipo_cambio' => 'TIPO DE CAMBIO: Q ' . number_format($e['tipo_cambio'], 2, '.', ',') . ' * $ 1.00  BANRURAL, ' . fecha_dmy($e['fecha_procesado']), //:'',
          'descripcion_lugar' => $e['descripcion_lugar'],
        );
        $data[] = $sub_array;
      }
    }
    //fin
  }else{
    $sub_array = array(
      'formulario'=>number_format($id_empleado, 0, ".", ","),
      //'id_empleado'=>$e['id_empleado'],
      //'tipo_contrato'=>$e['tipo_contrato'],
      'monto_num' => '',
      'monto_real' => '',
      'monto_anticipo' => '',
      'moneda' => '',
      'monto_letras' => 'FORMULARIO NO UTILIZADO',
      'cuota' => '',
      'total_real_cabecera' => '',
      'total_real' => '',
      'total_real_total' => '',
      'total_real2' => '',
      'porcentaje_proyectado' => '',
      'porcentaje_real' => '',
      'justificacion' => '',
      'monto_descuento_anticipo' => '',
      'otros_gastos' => '',
      'total' => '',
      'justificacion_hospedaje' => '',
      'tipo_comision' => '',

      'destino' => '',


      'num_dias' => '',
      'num_dias_detalle' => '',
      //'nombramiento'=>$e['nro_nombramiento'],
      'numero_viatico_anticipo' => '',
      'hoy' => '',

      //'fecha_solicitud'=>$e['fecha'],
      'emp' => '',
      'cargo' => '',
      'partida' => '',
      'sueldo' => '',
      'monto_asignado_sustituido' => '',
      'reng_sustituye' => '',
      'resolucion' =>  $clasei->retornaResolucion($id_empleado,3),
      'hospedaje' => '',
      'hospedaje2' => '',
      'reintegro_alimentacion' => '',
      'total_reintegro_' => '',
      'total_reintegro' => '',
      'a_favor' => '',
      //'total_reintegro'=>($e['total_real']>0 && $e['total_real']==$e['monto_asignado'] || $e['total_real']>0 && $e['total_real']>$e['monto_asignado'])?($resta>0)?''.number_format($resta,2,".",",").'':''.number_format($resta,2,".",",").'' :'0.00' ,
      //'a_favor'=>($e['total_real']>0 && $e['total_real']==$e['monto_asignado'] || $e['total_real']>0 && $e['total_real']>$e['monto_asignado'])?($resta>0)?''.number_format($resta,2,".",",").'':''.number_format($resta,2,".",",").'' :'0.00' ,
      'a_favor_' => '',
      'total_' => '',
      'reintegro_texto' => '',
      'utilizado' => '',
      'bln_cheque' => '',
      'desc_tipo_cambio' => '',
      'estado_viatico'=>'',
      'descripcion_lugar' => '',
      'destinos' => '',
    );
    $data[] = $sub_array;
    }
    $output = array(
      "data"    => $data
    );

    echo json_encode($output);



else :
  header("Location: ../index.php");
endif;

?>
