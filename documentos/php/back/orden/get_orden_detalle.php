<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $clased = new documento;
  $id_pago = $_GET['id_pago'];

  $orden = $clased->getOrdenDetalleById($id_pago);
  $facturas = $clased->getFacturasByOrden($id_pago);

  $invoices = "";
  foreach ($facturas AS $f) {
    // code...
    $invoices .=",".$f['idFactura'];
  }


  $yes='';

  $data = array();
  $tipo_pago = $clased->retornaTipoOrden($orden['id_tipo_pago']);
  $data = array(
    'id_pago'=>$orden['id_pago'],
    'id_tipo_pago'=>$orden['id_tipo_pago'],
    'tipo_pago'=>$tipo_pago['tipo_pago'],
    'nro_registro'=>$orden['nro_registro'],
    'id_year'=>$orden['id_year'],
    'creado_en'=>$orden['creado_en'],
    'creado_por'=>$orden['creado_por'],
    'cur_compromiso'=>(!empty($orden['cur_compromiso'])) ? $orden['cur_compromiso'] : 'No se ha registro',
    'cur_compromiso_r'=>(!empty($orden['cur_compromiso_r'])) ? $orden['cur_compromiso_r'] : 'No se ha registro',
    'cur_compromiso_fecha'=>$orden['cur_compromiso_fecha'],
    'cur_devengado'=>(!empty($orden['cur_devengado'])) ? $orden['cur_devengado'] : 'No se ha registro',
    'cur_devengado_r'=>(!empty($orden['cur_devengado_r'])) ? $orden['cur_devengado_r'] : 'No se ha registro',
    'cur_devengado_fecha'=>$orden['cur_devengado_fecha'],
    'id_status'=>$orden['id_status'],
    'id_bitacora'=>$orden['id_bitacora'],
    'monto'=>$orden['monto'],
    'facturas'=>"".$invoices."",
    'visible'=>true
  );

  echo json_encode($data);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
