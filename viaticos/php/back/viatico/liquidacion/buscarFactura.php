<?php
include_once '../../../../../inc/functions.php';
include_once '../../../../../inc/Database.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $serie = $_POST['nro_serie'];
  $numero = $_POST['nro_factura'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT a.vt_nombramiento,a.dia_id, a.id_empleado, a.fecha, b.factura_tiempo,b.factura_id,
          b.factura_tipo,b.factura_nit,b.factura_serie,b.factura_numero,b.factura_monto,b.factura_descuento,b.factura_propina,
          b.proveedor,b.bln_confirma, a.dia_observaciones_al, a.dia_observaciones_hos, b.factura_fecha, ISNULL(b.flag_error,0) AS flag_error,
          b.lugarId, c.lugarNit, c.lugarNombre, ISNULL(d.primer_nombre,'')+' '+ISNULL(d.segundo_nombre,'')+' '+ISNULL(d.tercer_nombre,'')+' '+ISNULL(d.primer_apellido,'')+' '+ISNULL(d.segundo_nombre,'')+' '+ISNULL(d.tercer_apellido,'') AS empleado,
          f.descripcion AS direccion


          FROM vt_nombramiento_dia_comision a
          LEFT JOIN vt_nombramiento_factura b ON b.dia_id = a.dia_id
          LEFT JOIN vt_nombramiento_lugar c ON b.lugarId = c.lugarId
          INNER JOIN rrhh_persona d ON a.id_empleado = d.id_persona
          INNER JOIN vt_nombramiento e ON a.vt_nombramiento = e.vt_nombramiento
          INNER JOIN rrhh_direcciones f ON e.id_rrhh_direccion = f.id_direccion
          WHERE b.factura_serie = ? AND b.factura_numero = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($serie, $numero,1));
    $f = $stmt->fetch();
    Database::disconnect_sqlsrv();


    $data = array();

    if(!empty($f['factura_serie'])){
      $data = array(
        'msg'=>'OK',
        'id_empleado'=>$f['id_empleado'],
        'factura_tiempo'=>$f['factura_tiempo'],
        'factura_serie'=>$f['factura_serie'],
        'factura_numero'=>$f['factura_numero'],
        'factura_monto'=>number_format($f['factura_monto'],2,'.',','),
        'factura_propina'=>number_format($f['factura_propina'],2,'.',','),
        'lugarNit'=>$f['lugarNit'],
        'lugarNombre'=>$f['lugarNombre'],
        'empleado'=>$f['empleado'],
        'vt_nombramiento'=>$f['vt_nombramiento'],
        'direccion'=>$f['direccion'],
        'fecha'=>fecha_dmy($f['fecha']),
        'tiempo_t'=>retornaTipoViatico($f['factura_tiempo']),
        'concepto'=>(retornaConceptoFactura($f['factura_tiempo']) == 1) ? 'ALIMENTACION' : 'HOSPEDAJE',
      );
    }else{
      $data = array(
        'msg'=>'ERROR',
      );
    }

    echo json_encode($data);

else:
    header("Location: ../index.php");
endif;

?>
