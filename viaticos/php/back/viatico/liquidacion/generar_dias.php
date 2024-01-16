<?php
include_once '../../../../../inc/functions.php';
include_once '../../functions.php';
date_default_timezone_set('America/Guatemala');

$dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
$dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

$clase = new viaticos;
$id_viatico =$_GET['id_viatico'];
$id_persona = $_GET['id_persona'];
$parametros = substr($id_persona, 1);
//echo $parametros;

$e = $clase->get_empleado_datos_por_nombramiento($id_viatico,$parametros);
$fecha1 = strtotime($e['fecha_salida_saas']);
$fecha2 = strtotime($e['fecha_regreso_saas']);

$monto_diario = ($e['monto_asignado'] * $e['tipo_cambio']) / $e['porcentaje_proyectado'];
$monto_final = ($e['id_pais'] == 'GT') ? ($monto_diario / 2) : $monto_diario;
//echo $fecha1. ' || '.$fecha2;
$dias = array();
$filas = 0;
for($fecha1;$fecha1<=$fecha2;$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1))){
  $filas ++;
}

$fecha1 = strtotime($e['fecha_salida_saas']);
$fecha2 = strtotime($e['fecha_regreso_saas']);
$fila = 0;

$g = $clase->get_totales_por_dia_tipo_factura($id_viatico,$parametros,NULL,3);

$sub_array2 = array(
  'fecha'=>'',//date('d-m-Y',$fecha1),
  'dia'=>'',//str_replace($dias_EN, $dias_ES, $dia),
  'valor_di'=>'',
  'porcentaje'=>'',//$e['porcentaje_real'],
  //'monto_diario'=>$monto_diario,
  'monto_alimentacion'=>'',//($ma < 0) ? $ma * -1 : $ma,
  'monto_hospedaje'=>'',//($mh < 0) ? $mh * -1 : $mh,
  'fila'=>'',
  'filas'=>'',
  'monto_diario_a'=>$g['gastado'],//$monto_final,
  'monto_diario_h'=>number_format($g['facturas'],0,'.',','),//($fila == $filas) ? 0 : $monto_final,
  'validacionfila'=>'',//($fila == $filas) ? 'ERROR' : 'OK'
  'tipo'=>'g'
);



for($fecha1;$fecha1<=$fecha2;$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1))){
  $fila ++;
  $m1 = $clase->get_totales_por_dia_tipo_factura($id_viatico,$parametros,date('Y-m-d',$fecha1),1);
  $m2 = $clase->get_totales_por_dia_tipo_factura($id_viatico,$parametros,date('Y-m-d',$fecha1),2);
  $ma = floatval($m1['gastado'] - $m1['descuento'] - $m1['propina']);
  $mh = floatval($m2['gastado'] - $m2['descuento'] - $m2['propina']);
  $dia = date('l',$fecha1);

  $tiempos = $clase->getTiemposDeComidaRealizados($id_viatico,$parametros,date('Y-m-d',$fecha1),1);
  $comidas = '';
  foreach($tiempos AS $t){
    $comidas .= $t['factura_tiempo'];
  }

  $facturas = $clase->getFacturasNoPresentadas($id_viatico,$parametros,date('Y-m-d',$fecha1));
  $totalAprobado = 0;
  //inicio
  foreach ($facturas as $key => $f) {
    // code...
    if(is_numeric($f['id_empleado'])){
      if($f['factura_aprobada'] == 1){
        $totalAprobado += $f['factura_monto'];
      }

    }
  }


  $monto = 0;
  $montoa = ($ma < 0) ? $ma * -1 : $ma;
  $montosDeLey = $m1['gastado_montos'];

  if($comidas == '1'){
    $monto = ($montoa > $m1['gastado_montos']) ? $m1['gastado_montos'] + $totalAprobado : $montoa;
  }else if($comidas == '12'){
    $monto = ($montoa > $m1['gastado_montos']) ? $m1['gastado_montos'] + $totalAprobado : $montoa;
  }else if($comidas == '2'){
    $monto = ($montoa > $m1['gastado_montos']) ? $m1['gastado_montos'] + $totalAprobado : $montoa;
  }else if($comidas == '123'){
    $monto = ($montoa > $m1['gastado_montos']) ? $m1['gastado_montos'] + $totalAprobado : $montoa;
  }else if($comidas == '13'){
    $monto = ($montoa > $m1['gastado_montos']) ? $m1['gastado_montos'] + $totalAprobado : $montoa;
  }else if($comidas == '23'){
    $monto = ($montoa > $m1['gastado_montos']) ? $m1['gastado_montos'] + $totalAprobado : $montoa;
  }else if($comidas == '3'){
    $monto = ($montoa > $m1['gastado_montos']) ? $m1['gastado_montos'] + $totalAprobado : $montoa;
  }

  $montoh = ($mh < 0) ? $mh * -1 : $mh;
  $montohf = ($montoh > $m2['gastado_montos']) ? $m2['gastado_montos'] : $montoh;

  $montoParaRestar = ($monto > $montoa) ? $montoa : $monto;

  if(!empty($comidas)){
    if($monto > $montoa){
      $monto = $montoa;
    }else{
      $monto = $monto;
    }
  }
  //$monto = (!empty($comidas) && ($monto > $montoa)) ? $montoa : $monto;

  $num=explode(".",$e['porcentaje_real']);
  $porcentajeNuevo= ($num['1'] == 0) ? $e['porcentaje_real'] + 0.5 : $e['porcentaje_real'];

  $tomar_en_cuenta = ($num['1'] == 0 && $fila == 1) ? false : true;

  $sub_array = array(
    'fecha'=>date('d-m-Y',$fecha1),
    'dia'=>str_replace($dias_EN, $dias_ES, $dia),
    'valor_di'=>'',
    'porcentaje_real'=>$e['porcentaje_real'],
    'porcentaje'=>$porcentajeNuevo,//number_format($e['porcentaje_real'],2,'.',','),
    //'monto_diario'=>$monto_diario,
    'monto_alimentacion'=>number_format($montoa,2,'.',','),
    'monto_hospedaje'=>number_format($montoh,2,'.',','),
    'monto_alimentacion_real'=>number_format($monto,2,'.',','),
    'monto_hospedaje_real'=>number_format($montohf,2,'.',','),
    'montoalirestar'=>number_format($montoParaRestar,2,'.',','),
    'fila'=>$fila,
    'filas'=>$filas,
    'monto_diario_a'=>number_format($monto_final,2,'.',','),
    'monto_diario_h'=>($fila == $filas) ? 0 : $monto_final,
    'validacionfila'=>($fila == $filas) ? 'ERROR' : 'OK',
    'tipo'=>'v',
    'tomar_en_cuenta'=>$tomar_en_cuenta
  );

  $dias[]=$sub_array;




}
$dias[]=$sub_array2;
echo json_encode($dias);
?>
