<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $nombramiento=$_GET['vt_nombramiento'];
    $empleados = array();
    $empleados = viaticos::get_empleados_asistieron($nombramiento);

    $data = array();
    $total_liquidado=0;
    $total_personas=0;
    $monto=0;
    $cuota=0;
    $complemento=0;
    $reintegro=0;

    foreach ($empleados as $e){
      $accion='';
      if($e['bln_confirma']==1){

        $resta=($e['bln_anticipo']==0)?number_format($e['monto_real'],2,".",','):number_format(($e['monto_real']-$e['monto_asignado']),2,'.',',');

        $cuota=(($e['monto_asignado']*$e['tipo_cambio']))/$e['porcentaje_proyectado'];
        $total_liquidado+=($e['monto_real']*$e['tipo_cambio']);
        $total_personas+=1;
        if ($e['id_pais']=='GT'){
          if($e['monto_real']>0 || $e['monto_real']==$e['monto_asignado']){
            if($resta>0){
              $complemento+=$resta;
            }else{
              //$reintegro+=$resta;
            }
          }
          $reintegro+=$e['totalReint'];
        }

      }
    }

  $results = array(
    'moneda'=>($e['id_pais']=='GT')?'Q':'$',
    "total_liquidado" => number_format($total_liquidado,2,'.',','),
    "cuota" => number_format($cuota,2,'.',','),
    "personas" => number_format($total_personas,0,'.',','),
    "complemento"=>number_format($complemento,2,'.',','),
    "reintegro"=>number_format($reintegro,2,'.',',')
  );

  echo json_encode($results);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
