<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';

  $response = array();
  $id_documento='';
  $id_documento=$_POST['id_documento'];
  $clased = new vehiculos;

  $p = $clased->get_devolCuponesEnc($id_documento);
  $data = array();

  $cupones = array();
  $cupones = $clased->get_devolCuponesDet($id_documento);

  $cuponesImpresion = array();
  $data = array();
  $sub_array=array();

    foreach ($cupones as $c){
    // if($c['usado'] == 1){
      $sub_array = array(
        'id_documento'=>$c['id_documento'],
        'id_cupon'=>$c['id_cupon'],
        'cupon'=>$c['cupon'],
        'monto'=>$c['monto'],
        'usado'=>$c['usado'],
        'devuelto'=>$c['devuelto'],
        'usadoen'=>$c['usadoen'],
        'nombre'=>$c['nombre'],
        'placa'=>(!empty($c['placa'])) ? $c['placa'] : 'NO APLICA',
        'km'=>$c['km'],
        'id_tipo_uso'=>$c['id_tipo_uso'],

        'radio'=>$c['radio'],
        'v_flag'=>$c['id_cupon'],
        'cupon1'=>'cp1-'.$c['cupon'],
        'cupon2'=>'cp2-'.$c['cupon'],
        'checked1'=>($c['usado']==1)?true:false,
        'checked2'=>($c['devuelto']==1)?true:false,

        'id_vehiculo'=>$c['id_vehiculo'],
        'id_refer'=>$c['id_refer'],
        'id_departamento'=>$c['id_departamento'],
        'id_municipio'=>$c['id_municipio'],
        'referencia'=>$c['referencia'],
      );
    //}

    $cuponesImpresion[]=$sub_array;
  }

  $data = array(
    'id_documento'=>$p['id_documento'],
    'fecha'=>fecha_dmy($p['fecha']),
    'nro_documento'=>$p['nro_documento'],
    'estado'=>$p['estado'],
    'auto'=>$p['auto'],
    'opero'=>$p['opero'],
    'recibe'=>$p['recibe'],
    'id_estado'=>($p['id_estado'] == 4347)?true:false,
    'docto_estado'=>$p['id_estado'],
    'recibe'=>$p['recibe'],
    'total'=>number_format($p['total'],2,'.',','),
    'devuelto'=>number_format($p['devuelto'],2,'.',','),
    'utilizado'=>number_format($p['utilizado'],2,'.',','),
    'observa'=>$p['observa'],
    'fecha_entrega'=>fecha_dmy($p['fecha_entrega']),
    'fecha_procesado'=>fecha_dmy($p['fecha_procesado']),
    'cupones'=>$cuponesImpresion
  );

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;