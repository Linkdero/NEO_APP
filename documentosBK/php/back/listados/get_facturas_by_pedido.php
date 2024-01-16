<?php
include_once '../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
$u2 = usuarioPrivilegiado_acceso();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';



    $id_persona=$_SESSION['id_persona'];

    $ped_tra='';
    if(isset($_GET['ped_tra'])){
      $ped_tra=$_GET['ped_tra'];
    }

    $clased = new documento;
    $facturas = array();
    $data = array();

    $facturas = $clased->get_facturas_by_pedido($ped_tra);


    foreach($facturas as $f){

      $tipo = 'No definido';
      if($f['tipo_pago']==1){
        $tipo = 'Cheque';
      }else if($f['tipo_pago']==2){
        $tipo = 'Acreditamiento';
      }
      $sub_array = array(
        'DT_RowId'=>$f['orden_compra_id'],
        'nro_orden'=>(!empty($f['nro_orden']))?$f['nro_orden']:'Sin número',
        'tipo'=>$tipo,
        'factura_fecha'=>$f['factura_fecha'],
        'factura_serie'=>$f['factura_serie'],
        'factura_num'=>$f['factura_num'],
        'proveedor'=>wordwrap($f['Prov_nom'], 20, '<br />'),
        'factura_total'=>$f['factura_total'],
        'ped_num'=>$f['Ped_num'],
        'nog'=>$f['nog'],
        'cur'=>$f['cur'],
        'cheque'=>$f['cheque'],
        'estado'=>'',
        'accion'=>'',
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
