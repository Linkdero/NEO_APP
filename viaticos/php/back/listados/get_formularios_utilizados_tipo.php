<?php
include_once '../../../../inc/functions.php';
sec_session_start();
ini_set('memory_limit', '128M');
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $ini = $_POST['ini'];
    $fin = $_POST['fin'];
    $tipo = $_POST['tipo'];
    $message = array();
    $campo = 'nro_frm_vt_ant';

    if($tipo == 2){
      $campo = 'nro_frm_vt_cons';
      $message = array(
        'estado'=>'Formulario Anulado',
      );
    }else if($tipo == 3){
      $campo = 'nro_frm_vt_liq';
    }else if($tipo == 4){
      $campo = 'nro_frm_vt_ext';
    }
    $formularios = array();
    $formularios = viaticos::get_formularios_utilizados_tipo($ini,$fin,$tipo,$campo);

    $data = array();
    $monto_ant = 0;
    $monto_act = 0;
    $y = 0;
    $fila = 0;
    $fila2 = 0;
    $ant = 0;
    $validacion = '';
    $icono = '';
    $final = count($formularios) - 1;
    $message;
    $verificador = 0;
    $verificador2 = 0;


    foreach ($formularios as $key => $f){
      if(!empty($f[$campo])){
        $y = $key + 1;
        $fila = $key - 1;
        $fila2 = $key + 1;
        if(isset($formularios[$fila][$campo]) && isset($formularios[$fila2][$campo]) && isset($formularios[$key][$campo])){
          //inicio
          if ($y < $final) {

            $id_actual = $formularios[$key][$campo]; //$s[$key+1]['id_empleado'];
            $id_siguiente = $formularios[$y][$campo];

            $verificador = $formularios[$key][$campo] - $formularios[$fila][$campo];
            $verificador2 = $formularios[$fila2][$campo] - $formularios[$key][$campo];

            //$sumar=$s['gastos'];
            //$incremental+=1;
            $control = 0;
            if ($id_siguiente == $id_actual) {
              $control = 1;
              $message = 'Reutilizado';
              $icono = '<span class="fa fa-times-circle text-danger"></span>';
            } else {
              if($f['id_estado'] == 3  && $f['bln_confirma'] == 1){//&& !empty($f['nro_frm_vt_liq'])){
                if($tipo == 2){
                  if($f['id_pais'] != 'GT'){
                    $message = 'Formulario Anulado';
                    $icono = '<span class="fa fa-times-circle text-danger"></span>';
                  }else{
                    $message = 'Formulario Liquidado';
                    $icono = '<span class="fa fa-check-circle text-success"></span>';
                  }
                }else{
                  $message = 'Formulario Liquidado';
                  $icono = '<span class="fa fa-check-circle text-success"></span>';
                }


              }else if($f['id_estado'] == 1){
                $message = 'Formulario En Proceso';
                $icono = '<span class="fa fa-check-circle text-warning"></span>';
              }else if($f['id_estado'] == 2 || $f['id_estado'] == 3 && $f['bln_confirma'] == 0){// empty($f['nro_frm_vt_liq'])){
                $message = 'Formulario Anulado';
                $icono = '<span class="fa fa-times-circle text-danger"></span>';
              }
            }
            $correlativo;
            $numeroform = 0;
            if($tipo == 1){
              $correlativo = '<a onclick="imprimir_anticipo('.$f['vt_nombramiento'].',0,0,0,'.$f['id_empleado'].')">'.$f['nro_frm_vt_ant'].'</a>';//$f['nro_frm_vt_ant'];
              $numeroform = $f['nro_frm_vt_ant'];
            }else if($tipo == 2){
              //$correlativo = $f['nro_frm_vt_cons'];
              $correlativo = '<a onclick="imprimir_constancia('.$f['vt_nombramiento'].','.$f['id_empleado'].')">'.$f['nro_frm_vt_cons'].'</a>';
              $numeroform = $f['nro_frm_vt_cons'];
            }else if($tipo == 3){
              $correlativo = '<a onclick="imprimir_liquidacion('.$f['vt_nombramiento'].',0,0,0,'.$f['id_empleado'].')">'.$f['nro_frm_vt_liq'].'</a>';
              $numeroform = $f['nro_frm_vt_liq'];
            }else if($tipo == 4){
              $correlativo = $f['nro_frm_vt_ext'];
              $numeroform = $f['nro_frm_vt_ext'];
            }



            if($control == 0){
              $sub_array = array(
                'vt_nombramiento'=>'<a data-toggle="modal" data-target="#modal-remoto-lgg2" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='.$f['vt_nombramiento'].'"><i class="fa fa-cog"></i> '.$f['vt_nombramiento'].'</a>',//.' Y: '.$y.' - key: '.$key,
                'correlativo'=>$icono.' '.$correlativo,
                'verificador'=>$verificador .' || '.$verificador2,
                'id_estado'=>$f['id_estado'],
                'empleado'=>$f['empleado'],
                'direccion'=>$f['direccion'],
                'fecha'=>fecha_dmy($f['fecha']),
                'nro_frm_vt_ant'=>(!empty($f['nro_frm_vt_ant'])) ? $icono.' '.$f['nro_frm_vt_ant'] : '',
                'nro_frm_vt_cons'=>(!empty($f['nro_frm_vt_cons'])) ? $f['nro_frm_vt_cons'] : '',
                'nro_frm_vt_ext'=>(!empty($f['nro_frm_vt_ext'])) ? $f['nro_frm_vt_ext'] : '',
                'nro_frm_vt_liq'=>(!empty($f['nro_frm_vt_liq'])) ? $f['nro_frm_vt_liq'] : '',
                'estado_frm'=>$message,
                'estado'=>$f['state'],
                'id_pais'=>($f['id_pais'] == 'GT') ? 'NACIONAL' : 'EXTERIOR'
              );
              $data[] = $sub_array;
            }

            if($verificador2 > 1){
              //$correlativo2 = 0;
              $incremental = 1;
              $icono = '<span class="fa fa-times-circle text-danger"></span>';
              $correlativo2 = intval($numeroform);
              while($incremental < $verificador2){
                //inicio

                //$correlativo2 += intval($numeroform) + 1;
                $correlativo3 = $correlativo2+$incremental;
                if($tipo == 1){
                  $correlativo = '<a onclick="imprimir_anticipo(0,0,0,0,'.$correlativo3.')">'.$correlativo3.'</a>';//$f['nro_frm_vt_ant'];
                  $numeroform = $f['nro_frm_vt_ant'];
                }else if($tipo == 2){
                  //$correlativo = $f['nro_frm_vt_cons'];
                  $correlativo = '<a onclick="imprimir_constancia(0,'.$correlativo3.')">'.$correlativo3.'</a>';
                  $numeroform = $f['nro_frm_vt_cons'];
                }else if($tipo == 3){
                  $correlativo = '<a onclick="imprimir_liquidacion(0,0,0,0,'.$correlativo3.')">'.$correlativo3.'</a>';
                  $numeroform = $f['nro_frm_vt_liq'];
                }else if($tipo == 4){
                  $correlativo = $f['nro_frm_vt_ext'];
                  $numeroform = $f['nro_frm_vt_ext'];
                }
                $sub_array = array(
                  'vt_nombramiento'=>'FORMULARIO ANULADO',//'<a data-toggle="modal" data-target="#modal-remoto-lgg2" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='.$f['vt_nombramiento'].'"><i class="fa fa-cog"></i> '.$f['vt_nombramiento'].'</a>',//.' Y: '.$y.' - key: '.$key,
                  'correlativo'=>$icono.' '.$correlativo.'',
                  'verificador'=>$verificador .' || '.$verificador2,
                  'id_estado'=>'----',//$f['id_estado'],
                  'empleado'=>'FORMULARIO NO UTILIZADO',//$f['empleado'],
                  'direccion'=>'otros',//$f['direccion'],
                  'fecha'=>fecha_dmy($formularios[$key]['fecha']),//fecha_dmy($f['fecha']),
                  'nro_frm_vt_ant'=>'a',//(!empty($f['nro_frm_vt_ant'])) ? $icono.' '.$f['nro_frm_vt_ant'] : '',
                  'nro_frm_vt_cons'=>'a',//(!empty($f['nro_frm_vt_cons'])) ? $f['nro_frm_vt_cons'] : '',
                  'nro_frm_vt_ext'=>'a',//(!empty($f['nro_frm_vt_ext'])) ? $f['nro_frm_vt_ext'] : '',
                  'nro_frm_vt_liq'=>'a',//(!empty($f['nro_frm_vt_liq'])) ? $f['nro_frm_vt_liq'] : '',
                  'estado_frm'=>'Formulario Anulado',//$message,
                  'estado'=>'ANULADO',//$f['state'],
                  'id_pais'=>'OTROSSS'
                );
                $data[] = $sub_array;
                //fin
                $incremental ++;
              };

              /*for($faltante = 0; $faltante <= $verificador2; $faltante ++){
                $correlativo2 = intval($numeroform) + 1;
                $sub_array = array(
                  'vt_nombramiento'=>'a',//'<a data-toggle="modal" data-target="#modal-remoto-lgg2" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='.$f['vt_nombramiento'].'"><i class="fa fa-cog"></i> '.$f['vt_nombramiento'].'</a>',//.' Y: '.$y.' - key: '.$key,
                  'correlativo'=>$correlativo2.'jajaja',
                  'verificador'=>$verificador .' || '.$verificador2,
                  'id_estado'=>'a',//$f['id_estado'],
                  'empleado'=>'a',//$f['empleado'],
                  'direccion'=>'a',//$f['direccion'],
                  'fecha'=>'a',//fecha_dmy($f['fecha']),
                  'nro_frm_vt_ant'=>'a',//(!empty($f['nro_frm_vt_ant'])) ? $icono.' '.$f['nro_frm_vt_ant'] : '',
                  'nro_frm_vt_cons'=>'a',//(!empty($f['nro_frm_vt_cons'])) ? $f['nro_frm_vt_cons'] : '',
                  'nro_frm_vt_ext'=>'a',//(!empty($f['nro_frm_vt_ext'])) ? $f['nro_frm_vt_ext'] : '',
                  'nro_frm_vt_liq'=>'a',//(!empty($f['nro_frm_vt_liq'])) ? $f['nro_frm_vt_liq'] : '',
                  'estado_frm'=>'a',//$message,
                  'estado'=>'a',//$f['state'],
                );
                $data[] = $sub_array;
              }*/

            }


          }else{
            $fila = 0;
          }

            //$data[]=$e;
        }
          //fin
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
