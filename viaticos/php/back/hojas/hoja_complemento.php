<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $nombramiento=$_POST['vt_nombramiento'];
    $id_empleado = (!empty($_POST['id_empleado'])) ? $_POST['id_empleado'] : NULL;
    $empleados = array();
    $clase1 = new viaticos;
    $empleados = $clase1->get_empleados_complemento($nombramiento,$id_empleado);

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "EXEC sp_sel_valida_exterior ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($nombramiento));
    $pais = $q0->fetch();

    $data = array();
    $total_gastado=0;
    $total_proyectado=0;
    $total_reintegrado=0;
    $total_complemento=0;
    foreach ($empleados as $e){
      $validacion=$clase1->validar_sustitucion($e['bln_confirma'],$e['reng_num'],$nombramiento,$e['nro_frm_vt_ant']);
      //echo $validacion;
      if($e['bln_confirma']==1 || $validacion=='aa'){
        $accion='';
        $dep='';
        $lugar = $e['lugar'];
        if($e['descripcion_lugar']==1){
          $valor = $clase1->get_historial_viatico($nombramiento);
          $va = json_decode($valor['val_nuevo'], true);
          $keys = array_keys($va);

          $deptos=$clase1->get_departamentos($pais['id_pais']);
          $munis=$clase1->get_municipios($va['id_departamento']);

          foreach($munis["data"] as $m){
            $dep.=($va['id_municipio']==$m['id_municipio'])?$m['nombre']:'';
          }
          foreach($deptos["data"] as $d){
            $dep.=($va['id_departamento']==$d['id_departamento'])?'-'.$d['nombre']:'';
          }
          $lugar=$dep.'-'.$e['pais'];
        }
        //if($e['bln_confirma']==1){
          //$sustituye=($e['reng_sustituye']>0)?' -'.$e['id_empleado']:'';
          $emp=$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'];//.$sustituye;
          $resta=($e['bln_anticipo']==0)?$e['monto_real']:$e['monto_real']-$e['monto_asignado'];
          $resta_=($e['monto_real']-$e['monto_asignado']);
          $vc=($e['id_pais']=='GT')?$e['nro_frm_vt_cons']:$e['nro_frm_vt_ext'];
          $chk=($e['id_status']==938 || $e['id_status']==7959 || $e['id_status']==939)?1:0;
          $anticipo=($e['bln_anticipo']==0)?'*':'';

          $monto_real=($e['otros_gastos']>0)?$e['monto_real']:$e['monto_real']+$e['totalReint'];
          $total_reintegro='0.00';
          if($e['bln_anticipo']==1){
            $reesta=$resta_;
            if($monto_real<$e['monto_asignado']){
              $total_reintegro=$resta;
            }else{
              $result = ($e['porcentaje_real']<$e['porcentaje_proyectado'])?($e['otros_gastos']-floatval($reesta))-$e['totalReint']:'0';
              $total_reintegro=($e['otros_gastos']>0)?$result+$e['totalReint']:($monto_real<$e['monto_asignado'])?$e['totalReint']:$e['totalReint'];
            }
            if($monto_real>$e['monto_asignado'] && $e['totalReint']>0){
              if($resta_>$e['totalReint']){
                $total_reintegro=($e['porcentaje_real']<$e['porcentaje_proyectado'])?$result+$e['totalReint']:$e['totalReint'];
              }

            }
          }
          $operacion=(floatval($total_reintegro)<0)?''.(floatval($total_reintegro)*-1).'':floatval($total_reintegro);
          if($e['bln_anticipo']==1){

            if($e['monto_real']>0 && $monto_real>$e['monto_asignado']){

              if($resta_>0){
                $a_favor=($e['otros_gastos']>0)?$e['otros_gastos']:$resta_;
              }else{
                $a_favor=$resta_+$operacion;
              }
            }else{
              $a_favor='0.00';
            }
          }else{
            $a_favor=$e['monto_real'];
          }
          if($a_favor<0){
            $a_favor=$a_favor*-1;
          }
          $a_favor=$a_favor*$e['tipo_cambio'];
          $total_proyectado+=$e['monto_asignado']*$e['tipo_cambio'];
          $total_gastado+=$e['monto_real']*$e['tipo_cambio'];
          $tr=($total_reintegro<0)?(($total_reintegro*-1)*$e['tipo_cambio']):($total_reintegro*$e['tipo_cambio']);
          $total_reintegrado+=floatval($tr);
          $total_complemento+=floatval($a_favor);

          $letras = intval($total_gastado);
          $decimales = $total_gastado - $letras;
          $centavos = '00';
          if($decimales > 0){
            $centavos = $decimales * 100;
          }

          if(!empty($e['destino'])){
            $valores = $clase1->getDestinosByEmpleado($nombramiento,$e['id_empleado']);
            foreach($valores as $valor){

              $sub_array = array(
                'dep'=>$valor['municipio'].'-'.$valor['departamento'].'-'.$e['pais'],
                'f_ini'=>fecha_dmy($valor['fecha_ini']),
                'f_fin'=>fecha_dmy($valor['fecha_fin']),
                'h_ini'=>$valor['h_ini'],
                'h_fin'=>$valor['h_fin']
              );
              //$array_destinos[] = $sub_array;
              $lugar = $valor['municipio'].'-'.$valor['departamento'].'-'.$e['pais'];
            }
          }

          $letrasf = intval($a_favor);
          $decimalesf = $a_favor - $letras;
          $centavosf = '00';
          if($decimalesf > 0){
            $centavosf = $decimalesf * 100;
          }

          /*$destino = (!empty($e['destino'])) ? 2 : '';
          $dL = (!empty($e['descripcion_lugar'])) ? $e['descripcion_lugar'] : '';
          $descripcionlugar = (!empty($destino)) ? $destino : $dL;*/

          $bln_cheque=($e['bln_cheque']==1)?'<span class="stado_success" style="margin-left:0px"></span>':'';
            $sub_array = array(
              'nombramiento'=>$e['nombramiento_direccion'],
              'DT_RowId'=>$e['id_empleado'],
              'codigo'=>'',
              'reng_num'=>$e['reng_num'],
              'id_persona'=>$e['id_empleado'],
              'bln_confirma'=>$e['bln_confirma'],
              'foto'=>$e['id_empleado'],
              'bln_cheque'=>($e['bln_cheque']==1)?1:0,
              'empleado'=>$emp,//($e['bln_confirma']==0)?'<i class="fa fa-times-circle text-danger"> </i> '.$emp:'<i class="fa fa-check-circle text-info"> </i> '.$emp.' '.$bln_cheque,
              'asistencia'=>($e['bln_confirma']==1)?'CONFIRMADO':'NO CONFIRMADO',
              'va'=>$e['nro_frm_vt_ant'],
              'vc'=>$vc,
              'vl'=>($e['nro_frm_vt_liq']!=0)?$e['nro_frm_vt_liq']:'N/A',
              'p_p'=>number_format($e['porcentaje_proyectado'],'2','.',',').'%',
              'p_r'=>number_format($e['porcentaje_real'],'2','.',',').'%',
              'm_p'=>($e['id_pais']=='GT')?$anticipo.''.number_format($e['monto_asignado'], 2, ".", ","):' '.number_format($e['monto_asignado']*$e['tipo_cambio'], 2, ".", ","),
              'm_r'=>($e['id_pais']=='GT')?''.number_format(($e['monto_real']), 2, ".", ","):number_format(($e['monto_real']*$e['tipo_cambio']), 2, ".", ","),
              'complemento'=>number_format($a_favor,2,'.',','),//($e['id_pais']=='GT')?($e['monto_real']>0 || $e['monto_real']==$e['monto_asignado'])?($resta>0)?' '.number_format(($resta),2,'.',',').'':'0.00' :'0.00' : '0.00',
              'reintegro_'=>''.number_format($tr,2,'.',',').'',//($total_reintegro>0 || $e['otros_gastos']>0)?$total_reintegro:'0.00',
              'reintegro'=>''.number_format($tr,2,'.',',').'',//''.number_format($tr,2,'.',',').'',//($e['totalReint']>0)?number_format($e['totalReint'],2,'.',','):'0.00',
              //'reintegro'=>($e['id_pais']=='GT')?($e['monto_real']>$e['monto_asignado'] || $e['monto_real']==$e['monto_asignado'])?'<span class="text-success">+ '.number_format(($e['monto_real']-$e['monto_asignado']), 2, ".", ",").'</span>':($e['monto_real']>0)?'<span class="text-danger">'.number_format(($e['monto_real']-$e['monto_asignado']), 2, ".", ",").'</span>':'0.00':'0.00',
              'estado'=>'',//($e['nro_frm_vt_ant']>0)?'Anticipo entregado':($e['nro_frm_vt_cons']>0)?'Constancia nacional':($e['nro_frm_vt_ext']>0)?'Constancia extranjero':($e['nro_frm_vt_liq']==1)?'Liquidado':'NO',
              'cheque'=>($e['bln_cheque']==1)?'SI':'NO',
              'funcionario'=>$e['nombre_funcionario'],
              'dato'=>$chk,
              'bln_anticipo'=>$e['bln_anticipo'],
              'fecha'=>strtoupper(fechaCastellano($e['fecha'])),
              'lugar'=>$lugar,//$e['lugar'],
              'accion' => '',
              'total_gastado'=>''.number_format($total_gastado,2,'.',',').'',
              'total_proyectado'=>''.number_format($total_proyectado,2,'.',',').'',
              'total_reintegrado'=>''.number_format($total_reintegrado,2,'.',',').'',
              'total_complemento'=>''.number_format($total_complemento,2,'.',',').'',
              'total_gastado_letras'=>NumeroALetras::convertir($letras).' QUETZALES CON '.$centavos.'/100',
              'total_complemento_letras'=>NumeroALetras::convertir($letrasf).' QUETZALES CON '.$centavosf.'/100',
              'tipo_anticipo'=>$e['tipo_anticipo'],
              'fecha_liquidacion'=>fecha_dmy($e['fecha_liquidacion']),
              'total_recibido'=>number_format($a_favor-$tr,2,'.',',')
            );
            $data[] = $sub_array;
            //$data[]=$e;
          }
        //}

    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data
  );

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
