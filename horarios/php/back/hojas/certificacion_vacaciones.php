<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  //if (usuarioPrivilegiado_acceso()->accesoModulo(7847)) {
    date_default_timezone_set('America/Guatemala');
    include_once '../../../../horarios/php/back/functions.php';
    include_once '../../../../empleados/php/back/functions.php';

    $id_persona = $_POST['id_persona'];
    $BOLETA = new Boleta();
    $empleados = $BOLETA->get_dias_asignados_cert($id_persona);

    $clase = new empleado;

    $tipo = $clase->get_tipo_contrato_by_empleado($id_persona);
    $tpersona =$clase->get_tipo_persona($id_persona);
    $dir_n = '';
    $pus_n = '';
    $f_fin = '';

    $arregloMovimientos = array();

    if($tipo['id_contrato']==7 || $tpersona['id_status']==2312 || $tpersona['id_status']==5611){
      if(empty($tipo['id_empleado'])){
        $e = $clase->get_apoyo_actual_by_persona($id_persona);
      }else{
        $e = $clase->get_empleado_puesto_actual($id_persona);
        $dir_n = (!empty($e['direccionn']))?$e['direccionn']:'-- -- -- --';
        $pus_n = (!empty($e['pueston']))?$e['pueston']:'-- -- -- --';
        $f_fin = (!empty($e['fecha_efectiva_resicion']))?$e['fecha_efectiva_resicion']:'-- -- -- --';
      }
    }else if($tipo['id_contrato']==1075){
      $e = $clase->get_contrato_actual_by_persona($id_persona);

      $dir_n = (!empty($e['direccionf']))?$e['direccionf']:'-- -- -- --';
      $pus_n = (!empty($e['puestof']))?$e['puestof']:'-- -- -- --';
      $f_fin = (!empty($e['fecha_finalizacion']))?$e['fecha_finalizacion']:'-- -- -- --';
    }

    include_once '../../../../empleados/php/back/functions_plaza.php';
    include_once '../../../../empleados/php/back/functions_contratos.php';
    $plazas=plaza::get_plazas_por_empleado($id_persona,1);

    foreach($plazas AS $key => $p){
      if(!empty($p['id_status'])){
        $verificador = '';
        if($key == 0){
          $verificador = 'pi';
        }
        $sub_array = array(
          'verificador'=>$verificador,
          'tipo'=>'presupuestado',
          'fecha_ini'=>$p['fecha_toma_posesion'],
          'fecha_fin'=>$p['fecha_efectiva_resicion'],
          'id_status'=>$p['id_status']
        );
        $arregloMovimientos[]=$sub_array;
      }

    }

    $contratos=contrato::get_contratos_por_empleado($id_persona,1);
    foreach($contratos AS $key => $c){
      if(!empty($c['id_status'])){
        $verificador = '';
        if($key == 0){
          $verificador = 'pi';
        }
        $sub_array = array(
          'verificador'=>$verificador,
          'tipo'=>'contrato',
          'fecha_ini'=>$c['fecha_acuerdo_aprobacion'],
          'fecha_fin'=>$c['fecha_efectiva_resicion'],
          'id_status'=>$c['id_status'],
        );
        $arregloMovimientos[]=$sub_array;
      }

    }


    $diapen = 0;
    $per_ini = '';
    $per_fin = '';
    $dias_proporcionales = 0;
    $tipo_contrato = '';
    $subtotal = 0;

    foreach ($empleados as $empleado) {
      $empleado_baja = 0;
      $dias = $BOLETA->calular_dias_proporcional($per_ini,$per_fin);
      $dias_proporcionales = $dias;
      $subtotal = strval($empleado['dia_asi'] - $empleado['dia_goz']);
      if($empleado['dia_asi'] == 20){
        $per_fin = '31-12-'.$empleado['anio_des'];
        $dias_proporcionales = $empleado['dia_asi'];
        foreach ($arregloMovimientos AS $key => $value) {
          if($value['tipo'] == "contrato"){
            if(date('Y', strtotime($value['fecha_ini']))  == $empleado['anio_des']){
              $per_ini = fecha_dmy($value['fecha_ini']);
              $dias = $BOLETA->calular_dias_proporcional($per_ini,$per_fin);
              $dias_proporcionales = $dias;
              $subtotal = $dias_proporcionales - $empleado['dia_goz'];
            }
          }else{
            $per_ini = '01-01-'.$empleado['anio_des'];
          }
        }

        //$per_ini = $empleado['anio_des'];


      }else{
        $total = count($arregloMovimientos);
        foreach ($arregloMovimientos AS $key => $value) {
          //echo  date('Y', strtotime($value['fecha_ini'])) .' |--| '. $empleado['anio_des'];


          if($key == 0 && date('Y', strtotime($value['fecha_ini'])) == $empleado['anio_des']){

            if(date('m-d', strtotime($value['fecha_ini'])) > '01-01' && date('Y', strtotime($value['fecha_ini'])) == $empleado['anio_des']){
              $per_ini = fecha_dmy($value['fecha_ini']);
              if(date('Y', strtotime($value['fecha_fin'])) != date('Y', strtotime($value['fecha_ini']))){
                $per_fin = '31-12-'.date('Y',strtotime($value['fecha_ini'])); // porque la fecha fin muchas veces no existe
                $tipo_contrato = $value['tipo'];
              }else{
                $per_fin = fecha_dmy($value['fecha_fin']);
                $tipo_contrato = $value['tipo'];

              }

            }

          }else{
            $year = (!empty(date('Y',strtotime($value['fecha_fin'])))) ? date('Y',strtotime($value['fecha_fin'])) : date('Y');
            $year2 = (!empty(date('Y',strtotime($value['fecha_ini'])))) ? date('Y',strtotime($value['fecha_ini'])) : date('Y');
            if($value['tipo'] == "contrato"){
              if(date('Y', strtotime($value['fecha_ini']))  == $empleado['anio_des']){
                $per_ini = fecha_dmy($value['fecha_ini']);
                //$dias_proporcionales = $empleado['dia_asi'];
              }
            }else{
              //NO FUNCIONA CUANDO ES BAJA $dias_proporcionales = $empleado['dia_asi'];
              $per_ini = '01-01-'.$empleado['anio_des'];
            }

            //cuando es baja del empleado
            if(($year == $empleado['anio_des'])){

              $per_fin = date('d-m-Y', strtotime($value['fecha_fin']."- 1 days"));

              $pi = date('Y-m-d', strtotime($per_ini));
              $pf = date('Y-m-d', strtotime($per_fin));
              $dias = $BOLETA->calular_dias_proporcional($pi,$value['fecha_fin']);
              $dias_proporcionales = $dias;
              $subtotal = $dias_proporcionales - $empleado['dia_goz'];
              $empleado_baja = 1;
              $tipo_contrato = $value['tipo'];

            }else{
              //$per_fin = date('d-m-Y');
              $pi = date('Y-m-d', strtotime($per_ini));
              $dias = $BOLETA->calular_dias_proporcional($pi,date('Y-m-d'));
              if((empty($value[0]['fecha_fin'])) && $empleado_baja == 0){

                $dias_proporcionales = $dias;
                $tipo_contrato = $value['tipo'];

                $per_fin = date('d-m').'-'.$empleado['anio_des'];

              }

              if($value['tipo'] == 'contrato'){
                $dias_proporcionales = $dias;
                //if($year2 == $empleado['anio_des']){ //obtener periodo final de empleados 031
                  $per_fin = date('d-m').'-'.$empleado['anio_des'];
                //}
              }

              $subtotal = floatval($dias_proporcionales) - floatval($empleado['dia_goz']);


            }
          }


        }
      }
      $diapen = ($dias_proporcionales - $empleado['dia_goz']) + $diapen;
      $diapen1 = strval($diapen);
      $data[] = array(
        'id_persona' => $id_persona,
        'nombre' => $empleado['nombre'],
        'p_nominal' => $pus_n,//(!empty($empleado['p_nominal'])) ? $empleado['p_nominal'] : '',
        'dir_general' => $dir_n,//$empleado['dir_general'],
        'fecha_ingreso' => $f_fin, //$empleado['fecha_ingreso'],
        'dia_id' => $empleado['dia_id'],
        'dia_asi' => strval($dias_proporcionales),
        'dia_goz' => strval($empleado['dia_goz']),
        'anio_des' => $empleado['anio_des'],
        'dhasi' => $empleado['dia_asi'],
        'dhgoz' => $empleado['dia_goz'],
        'dhdiff' => $subtotal,
        'diapen' => $diapen1,
        'per_ini'=>$per_ini,
        'per_fin'=>$per_fin,
        'tipo_contrato'=>$tipo_contrato,
        'dias_proporcionales'=>$dias_proporcionales,
        'arreglo_mov'=>$arregloMovimientos,
        'anio_des'=>$empleado['anio_des']
      );
    }
    // $results = array(
    //   "sEcho" => 1,
    //   "iTotalRecords" => count($data),
    //   "iTotalDisplayRecords" => count($data),
    //   "aaData" => $data
    // );
    echo json_encode($data);
//  }
}
