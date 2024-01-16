<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $id_nombramiento=$_POST['id_nombramiento'];
  $id_empleado = $_POST['id_empleado'];

  $clase = new viaticos;

  $facturas = $clase->getFacturasParaRazonamiento($id_nombramiento,$id_empleado);
  $paginas = $clase->getPaginasParaRazonamiento($id_nombramiento,$id_empleado);

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT a.id_rrhh_direccion, b.descripcion AS direccion
          FROM vt_nombramiento a
          INNER JOIN rrhh_direcciones b ON a.id_rrhh_direccion = b.id_direccion
          WHERE a.vt_nombramiento = ?";

  $stmt = $pdo->prepare($sql);
  $stmt->execute(array($id_nombramiento));
  $dir = $stmt->fetch();
  Database::disconnect_sqlsrv();

  $data = array();
  $pages = array();
  $tipo = 1;


  $array_facturas = '';
  $totalFacturas = 0;
  $decimalpart = 0;

  foreach ($paginas as $key => $p) {
    // code...
    $array_facturas = array();
    $totalFacturas = 0;
    $decimalpart = 0;

    foreach ($facturas as $key => $f) {
      $sub_arrayf = array(
        'dia_id'=>$f['dia_id'],
        'id_pais'=>$f['id_pais'],
        'id_empleado'=>$f['id_empleado'],
        'fecha'=>'**'.fecha_dmy($f['fecha']).'**',
        'factura_tiempo'=>$f['factura_tiempo'],
        'factura_id'=>$f['factura_id'],
        'factura_tipo'=>$f['factura_tipo'],
        'factura_nit'=>$f['factura_nit'],
        'factura_serie'=>(!empty($f['factura_serie'])) ? '**'.$f['factura_serie'].'**' : '',
        'factura_numero'=>'**'.$f['factura_numero'].'**',
        'factura_monto'=>'**'.$f['factura_monto'].'**',
        'factura_descuento'=>$f['factura_descuento'],
        'factura_propina'=>$f['factura_propina'],
        'proveedor'=>'**'.$f['lugarNombre'].'**',
        'bln_confirma'=>$f['bln_confirma'],
        'flag_error'=>$f['flag_error'],
        'motivo_gastos'=>(!empty($f['motivo_gastos'])) ? '**'.$f['motivo_gastos'].'**' : ''
      );

      //$array_facturas = $sub_array;
      if($p['dia_id'] == $f['dia_id'] && $p['factura_tipo'] == $f['factura_tipo']){
        $array_facturas[] = $sub_arrayf;
        $totalFacturas += $f['factura_monto'];
      }

      $n = $totalFacturas;
      $parteEntera = floor($n);      // 1
      $fraction = $n - $parteEntera; // .25

    }

    $sub_array = array(
      'dia_id'=>$p['dia_id'],
      'factura_tipo'=>$p['factura_tipo'],
      'factura_concepto'=>(!empty($p['factura_concepto'])) ? '**'.$p['factura_concepto'].'**' : '',
      'facturas'=>$array_facturas,
      'monto_letras'=>NumeroALetras::convertir($parteEntera),
      'monto_decimales'=>(($fraction * 100) > 0) ? number_format($fraction * 100,0,'.',',')  : '00',
      'monto'=>number_format($totalFacturas,2,'.',','),
      'dia_observaciones_al'=>(!empty($p['dia_observaciones_al'])) ? strtoupper(($p['dia_observaciones_al'])): '',
      'dia_observaciones_hos'=>(!empty($p['dia_observaciones_hos'])) ? strtoupper(($p['dia_observaciones_hos'])) : '',
      'dir'=>$dir['direccion']
    );
    $pages[]=$sub_array;
  }


  $output = array(
    'data'=>$pages,
    //'facturas'=>$data
  );

  echo json_encode($output);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
