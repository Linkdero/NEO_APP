<?php
include_once '../../../../inc/functions.php';
//sec_session_start();
//if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $nombramiento=$_GET['vt_nombramiento'];
    $id_persona=$_GET['id_persona'];

    $clase= new viaticos();

    $horas = $clase->get_items(37);

    $empleado = $clase->get_empleado_datos_por_nombramiento($nombramiento,$id_persona);
    $form_constancia='';
    if($empleado['id_pais']=='GT'){
      if($empleado['nro_frm_vt_cons']==0){
        $form_constancia='No se le ha generado constancia nacional';
      }else{
        $form_constancia=$empleado['nro_frm_vt_cons'];
      }
    }else{
      if($empleado['nro_frm_vt_ext']==0){
        $form_constancia='No se le ha generado constancia exterior';
      }else{
        $form_constancia=$empleado['nro_frm_vt_ext'];
      }
    }

    $response = array(
    	/*'DT_RowId'=>$s['vt_nombramiento'],*/
    	'nombramiento' => $empleado['nombramiento'],
      'id_persona'=> $empleado['id_empleado'],
      'empleado'=>$empleado['primer_nombre'].' '.$empleado['segundo_nombre'].' '.$empleado['tercer_nombre'].' '.$empleado['primer_apellido'].' '.$empleado['segundo_apellido'].' '.$empleado['tercer_apellido'],
      'monto_asignado'=>($empleado['id_pais']=='GT')?'Q '.number_format($empleado['monto_asignado'], 2, ".", ","):'$ '.number_format($empleado['monto_asignado'], 2, ".", ",").' X '.number_format($empleado['tipo_cambio'], 2, ".", ",").' = Q '. number_format(($empleado['monto_asignado']*$empleado['tipo_cambio']), 2, ".", ","),
      'form_anticipo_letras'=>($empleado['nro_frm_vt_ant']==0)?'No se le ha dado anticipo':$empleado['nro_frm_vt_ant'],
      'form_constancia_letras'=>$form_constancia,
      'form_liquidacion_letras'=>($empleado['nro_frm_vt_liq']==0)?'No se le ha generado liquidación':$empleado['nro_frm_vt_liq'],
      'form_anticipo'=>$empleado['nro_frm_vt_ant'],
      'form_constancia'=>($empleado['id_pais']=='GT')?$empleado['nro_frm_vt_cons']:$empleado['nro_frm_vt_ext'],
      'form_liquidacion'=>$empleado['nro_frm_vt_liq'],
      'porcentaje_proyectado'=>number_format($empleado['porcentaje_proyectado'], 2, ".", ","),
      'id_pais'=>$empleado['id_pais'],
      'fecha_salida_saas'=>fecha_dmy($empleado['fecha_salida_saas']),
      'fecha_llegada_lugar'=>fecha_dmy($empleado['fecha_llegada_lugar']),
      'fecha_salida_lugar'=>fecha_dmy($empleado['fecha_salida_lugar']),
      'fecha_regreso_saas'=>fecha_dmy($empleado['fecha_regreso_saas']),
      'porcentaje_real'=>number_format($empleado['porcentaje_real'], 2, ".", ","),
      'bln_confirma'=>$empleado['bln_confirma'],

      'd_salida_saas'=>date('d',strtotime($empleado['fecha_salida_saas'])),
      'm_salida_saas'=>get_nombre_mes_corto(date('m',strtotime($empleado['fecha_salida_saas']))),
      'y_salida_saas'=>date('Y',strtotime($empleado['fecha_salida_saas'])),
      'h_salida_saas'=>$empleado['h_salida_saas'],

      'd_llegada_lugar'=>date('d',strtotime($empleado['fecha_llegada_lugar'])),
      'm_llegada_lugar'=>get_nombre_mes_corto(date('m',strtotime($empleado['fecha_llegada_lugar']))),
      'y_llegada_lugar'=>date('Y',strtotime($empleado['fecha_llegada_lugar'])),
      'h_llegada_lugar'=>$empleado['h_llegada_lugar'],

      'd_salida_lugar'=>date('d',strtotime($empleado['fecha_salida_lugar'])),
      'm_salida_lugar'=>get_nombre_mes_corto(date('m',strtotime($empleado['fecha_salida_lugar']))),
      'y_salida_lugar'=>date('Y',strtotime($empleado['fecha_salida_lugar'])),
      'h_salida_lugar'=>$empleado['h_salida_lugar'],

      'd_regreso_saas'=>date('d',strtotime($empleado['fecha_regreso_saas'])),
      'm_regreso_saas'=>get_nombre_mes_corto(date('m',strtotime($empleado['fecha_regreso_saas']))),
      'y_regreso_saas'=>date('Y',strtotime($empleado['fecha_regreso_saas'])),
      'h_regreso_saas'=>$empleado['h_regreso_saas'],

      'cant_hospedaje'=>number_format($empleado['hospedaje'], 2, ".", ","),
      'cant_alimentacion'=>number_format($empleado['reintegro_alimentacion'], 2, ".", ","),
      'cant_otros_gastos'=>number_format($empleado['otros_gastos'], 2, ".", ",")




    );

    echo json_encode($response);


/*else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
*/

?>
