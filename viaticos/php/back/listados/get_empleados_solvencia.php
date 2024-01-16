<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  date_default_timezone_set('America/Guatemala');
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';

  $data = array();
  $id_persona=$_SESSION['id_persona'];
  $clase = new empleado;
  $clasev = new viaticos;
  $e = $clase->get_empleado_by_id_ficha($id_persona);
  $direccion=$e['id_dirf'];
  if($e['id_subdireccion_funcional']==34){
    $direccion=207;
  }
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851) || usuarioPrivilegiado()->hasPrivilege(4)){
    $direccion = 0;
  }

  $solvencia = $clasev->get_empleados_solvencia($direccion);

  $x=1;
  foreach($solvencia as $s){

    $sub_array = array(
      'DT_RowId'=>$x,
      'vt_nombramiento' => $s['vt_nombramiento'],
      'id_persona' => $s['id_empleado'],
      'empleado'=> $s['nombre'],
      'nro_nombramiento'=>$s['nro_nombramiento']
      /*'empleado' => $s['empleado'],
      'correlativo'=>$s['correlativo']*/
    );
    $data[] = $sub_array;
    $x+=1;
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
