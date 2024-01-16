
<?php
include_once '../../../../inc/functions.php';
include_once '../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();
  $id_nombramiento=$_POST['nombramiento'];
  $dia=$_POST['dia'];
  $mes=$_POST['mes'];
  $year=$_POST['year'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql0 = "EXEC sp_sel_valida_exterior ?";
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array($id_nombramiento));
  $pais = $q0->fetch();

  $sql='';
  if($pais['id_pais']=='GT')
  {
    $sql = "EXEC sp_sel_datos_para_liquidacion_fecha_anterior @correlativo=?, @dia=?, @mes=?,@anio=?";
  }else{
    $sql = "EXEC sp_sel_datos_para_liquidacion_exterior_fecha_anterior @correlativo=?, @dia=?, @mes=?,@anio=?";
  }



  $p = $pdo->prepare($sql);
  $p->execute(array($id_nombramiento,$dia,$mes,$year));
  $empleados = $p->fetchAll();

  $data = array();
  if($pais['id_pais']=='GT'){
    foreach($empleados as $e){
      $resta=$e['total_real']-$e['monto_asignado']; // se agregó total reintegro
      if($resta<0){
        $resta=$resta*-1;
      }

      $resta=number_format((($resta)),2,'.',',');

      $sub_array = array(
        'formulario'=>number_format($e['nro_frm_vt_liq'], 0, "", ","),
        //'id_empleado'=>$e['id_empleado'],
        //'tipo_contrato'=>$e['tipo_contrato'],
        'monto_num'=>number_format($e['total_real'],2,'.',','),
        'monto_real'=>number_format(($e['total_real']+$e['totalReint']),2,'.',','),
        'monto_anticipo'=>($e['bln_anticipo']==1)?number_format($e['monto_asignado'], 2, ".", ","):'0.00',
        'moneda'=>$e['moneda'],
        'monto_letras'=>$e['monto_en_letras'],
        'cuota'=>$e['cuota'],
        'total_real'=>number_format($e['total_real'], 2, ".", ","),
        'total_real2'=>number_format($e['total_real2'],2,".",","),
        'porcentaje_proyectado'=>number_format($e['porcentaje_proyectado'],2,".",""),
        'porcentaje_real'=>number_format($e['porcentaje_real'],2,".",""),
        'justificacion'=>$e['justificacion'],
        'monto_descuento_anticipo'=>$e['monto_descuento_anticipo'],
        'otros_gastos'=>number_format($e['otros_gastos'],2,".",","),
        'total'=>$e['total'],
        'justificacion_hospedaje'=>$e['justifica_hospedaje'],
        'tipo_comision'=>$e['tipo_comision'],

        'destino'=>$e['lugar'],


        'num_dias'=>$e['dias_nombramiento'],
        'num_dias_detalle'=>$e['dias_detalle'],
        //'nombramiento'=>$e['nro_nombramiento'],
        'numero_viatico_anticipo'=>number_format($e['numero_viatico_anticipo'],0,'.',','),
        'hoy'=>'Guatemala '.$e['hoy'],

        //'fecha_solicitud'=>$e['fecha'],
        'emp'=>$e['nombre'],
        'cargo'=>$e['plaza'],
        'partida'=>$e['partida'],
        'sueldo'=>number_format($e['sueldo'],2,".",","),
        'monto_asignado_sustituido'=>$e['monto_asignado_sustituido'],
        'reng_sustituye'=>$e['reng_sustituye'],
        'resolucion'=>$e['resolucion'],
        'hospedaje'=>$e['hospedaje'],
        'hospedaje2'=>$e['hospedaje2'],
        'reintegro_alimentacion'=>$e['reintegro_alimentacion'],
        'total_reintegro_'=>($e['totalReint']>0)?number_format($e['totalReint'], 2, ".",","):'',
        'total_reintegro'=>($e['bln_anticipo']==1)?($e['total_real']>0 && $e['total_real']<$e['monto_asignado'])?($resta>0)?''.$resta.'':''.$resta.'' :'':'',
        'a_favor'=>($e['bln_anticipo']==1)?($e['total_real']>0 && $e['total_real']>$e['monto_asignado'])?($resta>0)?''.$resta.'':''.$resta.'' :'0.00':number_format($e['total_real'],2,'.',','),
        //'total_reintegro'=>($e['total_real']>0 && $e['total_real']==$e['monto_asignado'] || $e['total_real']>0 && $e['total_real']>$e['monto_asignado'])?($resta>0)?''.number_format($resta,2,".",",").'':''.number_format($resta,2,".",",").'' :'0.00' ,
        //'a_favor'=>($e['total_real']>0 && $e['total_real']==$e['monto_asignado'] || $e['total_real']>0 && $e['total_real']>$e['monto_asignado'])?($resta>0)?''.number_format($resta,2,".",",").'':''.number_format($resta,2,".",",").'' :'0.00' ,
        'a_favor_'=>($e['total_real']>$e['monto_asignado'])?number_format(($e['total_real']-$e['monto_asignado'])+$e['totalReint'],2,".",","):number_format(($e['total_real']-$e['monto_asignado'])+$e['totalReint'],2,".",","),//number_format(($e['total']-$e['totalReint']),2,".",","),//'0.00',
        'total_'=>($e['total_real']>$e['monto_asignado'])?number_format(($e['total_real']),2,".",","):number_format(($e['total_real']),2,".",","),
        'reintegro_texto'=>/*($e['bln_anticipo']==1)?*/($e['totalReint']>0)?'Reintegro por alimentación y hospedaje según Acuerdos Gubernativos No. 106-2016 / 148-2016 / 35-2017':'',
        'utilizado'=>($e['bln_anticipo']==0)?'    NO UTILIZADO':''//:''
      );
      $data[]=$sub_array;
    }
  }else{
    foreach($empleados as $e){
      $sub_array = array(
        'formulario'=>number_format($e['nro_frm_vt_liq'], 0, "", ","),
        //'id_empleado'=>$e['id_empleado'],
        //'tipo_contrato'=>$e['tipo_contrato'],
        'monto_num'=>number_format($e['total_real'], 2, ".", ","),
        'monto_real'=>number_format(($e['total_real']+$e['totalReint']),2,'.',','),
        'monto_anticipo'=>number_format($e['monto_asignado'], 2, ".", ","),
        'moneda'=>$e['moneda'],
        'monto_letras'=>$e['monto_en_letras'],
        'cuota'=>$e['cuota'],
        'total_real'=>number_format($e['total_real'], 2, ".", ","),
        'total_real2'=>number_format($e['total_real2'],2,".",","),
        'porcentaje_proyectado'=>number_format($e['porcentaje_proyectado'],2,".",""),
        'porcentaje_real'=>number_format($e['porcentaje_real'],2,".",""),
        'justificacion'=>$e['justificacion'],
        'monto_descuento_anticipo'=>$e['monto_descuento_anticipo'],
        'otros_gastos'=>number_format($e['otros_gastos'],2,".",","),
        'total'=>$e['total'],
        'justificacion_hospedaje'=>$e['justifica_hospedaje'],
        'tipo_comision'=>$e['tipo_comision'],//(!empty($e['tipo_comision']))?$e['tipo_comision']:'',

        'destino'=>$e['lugar'],


        'num_dias'=>$e['dias_nombramiento'],
        'num_dias_detalle'=>$e['dias_detalle'],
        //'nombramiento'=>$e['nro_nombramiento'],
        'numero_viatico_anticipo'=>number_format($e['numero_viatico_anticipo'],0,'.',','),
        'hoy'=>'Guatemala '.$e['hoy'],

        //'fecha_solicitud'=>$e['fecha'],
        'emp'=>$e['nombre'],
        'cargo'=>$e['plaza'],
        'partida'=>$e['partida'],
        'sueldo'=>number_format($e['sueldo'],2,".",","),
        'monto_asignado_sustituido'=>$e['monto_asignado_sustituido'],
        'reng_sustituye'=>$e['reng_sustituye'],
        'resolucion'=>$e['resolucion'],
        'hospedaje'=>$e['hospedaje'],
        'hospedaje2'=>$e['hospedaje2'],
        'reintegro_alimentacion'=>$e['reintegro_alimentacion'],
        'total_reintegro_'=>($e['totalReint']>0)?number_format($e['totalReint'], 2, ".",","):'',
        'total_reintegro'=>($e['totalReint']>0)?number_format($e['totalReint'], 2, ".",","):'',
        'a_favor'=>($e['total_real']>$e['monto_asignado'])?number_format((($e['total_real']-$e['monto_asignado'])),2,".",","):number_format($e['total_real'],2,".",","),//number_format(($e['total_real']*$e['tipo_cambio']), 2, ".", ","),
        'total_'=>($e['total_real']>$e['monto_asignado'])?number_format((($e['total_real']-$e['monto_asignado'])+$e['monto_asignado']),2,".",","):number_format($e['total_real'],2,".",","),
        'reintegro_texto'=>($e['totalReint']>0)?'Reintegro por alimentación y hospedaje según Acuerdos Gubernativos No. 148-2016 / 106-2016':'',
        'utilizado'=>($e['bln_anticipo']==0)?'    NO UTILIZADO':''//:''
      );
      $data[]=$sub_array;
    }
  }


      $output = array(
        "data"    => $data
      );

echo json_encode($output);




else:
header("Location: ../index.php");
endif;

?>
