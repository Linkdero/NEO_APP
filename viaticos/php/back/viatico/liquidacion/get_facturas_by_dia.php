<?php
include_once '../../../../../inc/functions.php';
include_once '../../functions.php';
date_default_timezone_set('America/Guatemala');

$dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
$dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

$clase = new viaticos;
$id_viatico =$_GET['id_viatico'];
$id_persona = $_GET['id_persona'];
$tipo = $_GET['tipo'];
$parametros = substr($id_persona, 1);
$fecha = (!empty($_GET['fecha'])) ? date('Y-m-d',strtotime($_GET['fecha'])) : NULL;
$fila = $_GET['fila'];
$total_filas = $_GET['filas'];
$formulario = $_GET['formulario'];
//echo $parametros;

$pdo = Database::connect_sqlsrv();
$sql55 = "SELECT TOP 1 porcentaje_real FROM vt_nombramiento_detalle
      WHERE vt_nombramiento=? AND id_empleado = ?";

$q55 = $pdo->prepare($sql55);
$q55->execute(array(
  $id_viatico,
  $parametros
));

$porAct = $q55->fetch();
$num=explode(".",$porAct['porcentaje_real']);


$valor = ($fila == $total_filas) ? false : true;


$data = array();
$tiempo = 0;
if($tipo == 2){
  //inicio
  for($x = 0; $x < 4; $x++){
    $tiempo ++;
    $tomar_en_cuenta = (($x == 0 || $x == 1 || $x == 2) && $num[1] == 0 && $fila == 0 ) ? false : true;

    if($fila == $total_filas && $x == 3 || $tomar_en_cuenta == false ){

    }else{
      $e = $clase->get_facturas_by_dia($id_viatico,$parametros,$fecha,$tiempo);
      if(is_numeric($e['id_empleado']) && $tiempo == is_numeric($e['factura_tiempo'])){
        $sub_array = array(
          'dia_id'=>$e['dia_id'],
          'factura_id'=>$e['factura_id'],
          'tiempo'=>$e['factura_tiempo'],
          'tiempo_t'=>retornaTipoViatico($e['factura_tiempo']),
          'tipo'=>$e['factura_tipo'],
          'nit'=>$e['factura_nit'],
          'nitt'=>$e['factura_nit'],
          'fecha'=>$e['factura_fecha'],
          'serie'=>$e['factura_serie'],
          'numero'=>$e['factura_numero'],
          'monto'=>$e['factura_monto'],
          'descuento'=>$e['factura_descuento'],
          'propina'=>$e['factura_propina'],
          'proveedor'=>(!empty($e['lugarId'])) ? $e['lugarId'] : 0,//$e['proveedor'],
          'concepto'=>(retornaConceptoFactura($e['factura_tiempo']) == 1) ? 'ALIMENTACION' : 'HOSPEDAJE',
          'bln_confirma'=>($e['bln_confirma'] == 1) ? true : false ,
          'guardado'=>true,
          'deshabilitado'=>($formulario == 0) ? false : true, //verificar si ya fue liquidado (si ya tiene formulario de liquidación) el usuario para ya no modificar
          'dia_observaciones_al'=>$e['dia_observaciones_al'],
          'dia_observaciones_hos'=>$e['dia_observaciones_hos'],
          'motivo_gastos'=>'',
          'error'=>($e['flag_error'] == 1) ? true : false,
          'lugar_id'=>$e['lugarId'],
          'lugar_nit'=>$e['lugarNit'],
          'lugar_nombre'=>$e['lugarNombre'],
        );
        $data[] = $sub_array;
      }else{
        $sub_array = array(
          'dia_id'=>'',
          'factura_id'=>'',
          'tiempo'=>$tiempo,
          'tiempo_t'=>retornaTipoViatico($tiempo),
          'tipo'=>retornaConceptoFactura($tiempo),
          'nit'=>'',
          'nitt'=>'',
          'fecha'=>'',
          'serie'=>'',
          'numero'=>'',
          'monto'=>'',
          'descuento'=>'',
          'propina'=>number_format(0,2,'.',','),
          'proveedor'=>0,
          'concepto'=>(retornaConceptoFactura($tiempo) == 1) ? 'ALIMENTACION' : 'HOSPEDAJE',
          'bln_confirma'=>true,
          'guardado'=>false,
          'deshabilitado'=>false,
          'dia_observaciones_al'=>'',
          'dia_observaciones_hos'=>'',
          'motivo_gastos'=>'',
          'error'=>false,
          'lugar_id'=>'',
          'lugar_nit'=>'',
          'lugar_nombre'=>'',

        );
        $data[] = $sub_array;
      }
    }
  }

  //fin
}else if($tipo == 3){
  //inicio
  $facturas = $clase->getFacturasGastos($id_viatico,$parametros);

  foreach ($facturas as $key => $f) {
    // code...
    if(is_numeric($f['id_empleado'])){
      $sub_array = array(
        'dia_id'=>$f['dia_id'],
        'factura_id'=>$f['factura_id'],
        'tiempo'=>5,//$f['factura_tiempo'],
        'tiempo_t'=>'',//retornaTipoViatico($f['factura_tiempo']),
        'tipo'=>$f['factura_tipo'],
        'nit'=>$f['factura_nit'],
        'nitt'=>$f['factura_nit'],
        'fecha'=>$f['factura_fecha'],
        'serie'=>$f['factura_serie'],
        'numero'=>$f['factura_numero'],
        'monto'=>$f['factura_monto'],
        'descuento'=>$f['factura_descuento'],
        'propina'=>$f['factura_propina'],
        'proveedor'=>(!empty($f['lugarId'])) ? $f['lugarId'] : 0,
        'concepto'=>'GASTOS CONEXOS',//(retornaConceptoFactura($f['factura_tiempo']) == 1) ? 'ALIMENTACION' : 'HOSPEDAJE',
        'bln_confirma'=>($f['bln_confirma'] == 1) ? true : false ,
        'guardado'=>true,
        'deshabilitado'=>($formulario == 0) ? false : true, //verificar si ya fue liquidado (si ya tiene formulario de liquidación) el usuario para ya no modificar
        'dia_observaciones_al'=>$f['dia_observaciones_al'],
        'dia_observaciones_hos'=>$f['dia_observaciones_hos'],
        'motivo_gastos'=>$f['motivo_gastos'],
        'error'=>'',
        'lugar_id'=>$f['lugarId'],
        'lugar_nit'=>$f['lugarNit'],
        'lugar_nombre'=>$f['lugarNombre'],
      );
      $data[] = $sub_array;
    }
  }
  //fin
}else if ($tipo == 4){
  $facturas = $clase->getFacturasNoPresentadas($id_viatico,$parametros,$fecha);
    //inicio
    foreach ($facturas as $key => $f) {
      // code...
      if(is_numeric($f['id_empleado'])){
        $sub_array = array(
          'dia_id'=>$f['dia_id'],
          'factura_id'=>$f['factura_id'],
          'tiempo'=>5,//$f['factura_tiempo'],
          'tiempo_t'=>$f['factura_tiempo_desc'],//retornaTipoViatico($f['factura_tiempo']),
          'tipo'=>$f['factura_tipo'],
          'nit'=>$f['factura_nit'],
          'nitt'=>$f['factura_nit'],
          'fecha'=>$f['factura_fecha'],
          'serie'=>$f['factura_serie'],
          'numero'=>$f['factura_numero'],
          'monto'=>$f['factura_monto'],
          'descuento'=>$f['factura_descuento'],
          'propina'=>$f['factura_propina'],
          'proveedor'=>(!empty($f['lugarId'])) ? $f['lugarId'] : 0,
          'concepto'=>'GASTOS CONEXOS',//(retornaConceptoFactura($f['factura_tiempo']) == 1) ? 'ALIMENTACION' : 'HOSPEDAJE',
          'bln_confirma'=>($f['bln_confirma'] == 1) ? true : false ,
          'guardado'=>true,
          'deshabilitado'=>($formulario == 0) ? false : true, //verificar si ya fue liquidado (si ya tiene formulario de liquidación) el usuario para ya no modificar
          'dia_observaciones_al'=>$f['dia_observaciones_al'],
          'dia_observaciones_hos'=>$f['dia_observaciones_hos'],
          'motivo_gastos'=>$f['motivo_gastos'],
          'factura_aprobada'=>$f['factura_aprobada'],
          'error'=>($f['factura_aprobada'] == 1) ? true : false,
          'lugar_id'=>$f['lugarId'],
          'lugar_nit'=>$f['lugarNit'],
          'lugar_nombre'=>$f['lugarNombre'],
        );
        $data[] = $sub_array;
      }
    }
    //fin
}




echo json_encode($data);
?>
