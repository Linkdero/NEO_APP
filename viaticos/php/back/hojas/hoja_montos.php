<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $nombramiento=$_POST['vt_nombramiento'];
    $empleados = array();
    $clase1 = new viaticos;
    $empleados = $clase1->get_empleados_complemento($nombramiento,0);

    $data = array();
    $total_proyectado=0;

    foreach ($empleados as $e){
      if($e['bln_confirma']==1){
        $anticipo=($e['bln_anticipo']==0)?'*':'';
        $total_proyectado+=($e['bln_anticipo']==1)?($e['id_pais']=='GT')?$e['monto_asignado']:($e['monto_asignado']*$e['tipo_cambio']):0;
        $emp=$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'];//.$sustituye;
        $sub_array = array(
          'vt_nombramiento'=>$e['nombramiento'],
          'nombramiento'=>$e['nombramiento_direccion'],
          'reng_num'=>$e['reng_num'],
          'id_persona'=>$e['id_empleado'],
          'bln_confirma'=>$e['bln_confirma'],
          'bln_anticipo'=>$e['bln_anticipo'],

          'empleado'=>$emp,//($e['bln_confirma']==0)?'<i class="fa fa-times-circle text-danger"> </i> '.$emp:'<i class="fa fa-check-circle text-info"> </i> '.$emp.' '.$bln_cheque,

          'm_p'=>($e['id_pais']=='GT')?$anticipo.''.number_format($e['monto_asignado'], 2, ".", ","):' '.number_format(($e['monto_asignado']*$e['tipo_cambio']), 2, ".", ","),
          //'m_r'=>($e['id_pais']=='GT')?''.number_format(($e['monto_real']), 2, ".", ","):'* '.number_format($e['tipo_cambio'], 2, ".", ",").' = Q '.number_format(($e['monto_real']*$e['tipo_cambio']), 2, ".", ","),

          'total_proyectado'=>''.number_format($total_proyectado,2,'.',',').'',

          'total_gastado_letras'=>NumeroALetras::convertir($total_proyectado)
        );
        $data[] = $sub_array;
          //$data[]=$e;

      }

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
