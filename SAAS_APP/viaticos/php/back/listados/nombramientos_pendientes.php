<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';



    $id_persona=$_SESSION['id_persona'];
    $tipo=2;
    $clase = new empleado;
    $clase1 = new viaticos;
    $e = $clase->get_empleado_by_id_ficha($id_persona);


    $solicitudes = array();
    if(usuarioPrivilegiado_acceso()->accesoModulo(7851) || usuarioPrivilegiado()->hasPrivilege(4)){
      $solicitudes = $clase1->get_all_solicitudes($tipo);
    }else{
      $solicitudes = $clase1->get_all_solicitudes_by_direccion($e['id_dirf'],$tipo);
    }


    $data = array();
    foreach ($solicitudes as $s){

        $sub_array = array(
          'DT_RowId'=>$s['vt_nombramiento'],
          'nombramiento' => $s['vt_nombramiento'],
          'fecha' => fecha_dmy($s['fecha']),
          'direccion_solicitante' => $s['direccion'],
          'destino'=>$s['pais'].', <br>'.$s['departamento'].', <br>'.$s['municipio'],
          'motivo'=>$s['motivo'],
          'fecha_ini'=>fecha_dmy($s['fecha_salida']),
          'fecha_fin'=>fecha_dmy($s['fecha_regreso']),
          'estado' => $s['estado'],
          'url'=>'viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='.$s['vt_nombramiento'],
          'progress'=>'',
          'personas'=>'',
          'accion'=>''

        );
        $data[] = $sub_array;


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
