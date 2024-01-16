<?php
include_once '../../../../../inc/functions.php';

sec_session_start();
$u = usuarioPrivilegiado();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../../../../../empleados/php/back/functions.php';
    include_once '../../functions.php';

    $id_persona=$_SESSION['id_persona'];
    $clased = new documento;
    $listado = array();
    $data = array();
    $tipo = '';
    if($u->hasPrivilege(302)){
      $listado = $clased->get_actas_compras(date('Y'),$_SESSION['id_persona']);
    }

    foreach($listado as $l){
      $accion = '<div class="btn-group">
            <span class="btn btn-soft-info btn-sm" onclick="imprimirActa('.$l['acta_id'].')"><i class="fa fa-print"></i></span>
            <span class="btn btn-soft-info btn-sm" onclick=""><i class="fa fa-check"></i></span>
      </div>';
      $sub_array = array(
        'DT_RowId'=>$l['acta_id'],
        'acta_id'=>$l['acta_id'],
        'acta_fecha'=>$l['acta_fecha'],
        'acta_finalizacion'=>$l['acta_finalizacion'],
        'director'=>$l['director'],
        'jefe'=>$l['jefe'],
        'tecnico'=>$l['tecnico'],
        'pyr'=>$l['pyr'],
        'nit_proveedor'=>$l['nit_proveedor'],
        'Prov_nom'=>$l['Prov_nom'],
        'acta_monto'=>$l['acta_monto'],
        'acta_justificacion'=>$l['acta_justificacion'],
        'accion'=>$accion
      );
      $data[] = $sub_array;
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
