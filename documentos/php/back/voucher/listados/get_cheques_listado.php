<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  date_default_timezone_set('America/Guatemala');

  include_once '../../functions.php';

  $clased = new documento;

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  /*$sql0 = "SELECT a.chequeId,a.cajaId,a.chequeNum,a.chequeStatus,a.chequeUtilizadoEn,a.chequeUtilizadoPor,a.chequeTipoUso,
           b.factura_serie, factura_fecha, b.factura_num, b.factura_total, c.Prov_id, c.Prov_nom
           FROM ChequeDetalle a
           INNER JOIN docto_ped_pago b ON b.cheque_nro = a.chequeId
		       INNER JOIN APP_POS.dbo.PROVEEDOR c ON b.proveedor_id = c.Prov_id COLLATE Modern_Spanish_CI_AS
           WHERE a.chequeTipoUso = 1
           --ORDER BY a.chequeId ASC
           UNION
           SELECT a.chequeId,a.cajaId,a.chequeNum,a.chequeStatus,a.chequeUtilizadoEn,a.chequeUtilizadoPor,a.chequeTipoUso,
		   '' AS factura_serie, '' AS factura_fecha, '' AS factura_num, 0 AS factura_monto, '' AS prov_id, '' AS Prov_nom
                    FROM ChequeDetalle a
                    WHERE a.chequeStatus = 1
                    ORDER BY a.chequeId ASC
           ";*/

  $sql0 = "SELECT a.chequeId,a.cajaId,a.chequeNum,a.chequeStatus,a.chequeUtilizadoEn,a.chequeUtilizadoPor,a.chequeTipoUso,
           b.cheque_nro, b.facturas
           FROM ChequeDetalle a
           LEFT JOIN (
    			   SELECT a.cheque_nro, STRING_AGG(CONVERT(NVARCHAR(max), ISNULL(a.dato,'N/A')), '| ') AS facturas
    				FROM (
    					SELECT b.cheque_nro, ISNULL(b.factura_serie,'') + '; '+ CONVERT(VARCHAR(15), ISNULL(b.factura_fecha,'')) + '; ' + ISNULL(b.factura_num,'') + '; '+CONVERT(VARCHAR(20),b.factura_total) + '; '+ CONVERT(VARCHAR(15),c.Prov_id) COLLATE Modern_Spanish_CI_AS + '; '+ c.Prov_nom AS dato
    					FROM docto_ped_pago b
    					INNER JOIN APP_POS.dbo.PROVEEDOR c ON b.proveedor_id = c.Prov_id COLLATE Modern_Spanish_CI_AS
    					GROUP BY b.cheque_nro, b.factura_serie, b.factura_fecha, b.factura_num, b.factura_total, c.Prov_id, c.Prov_nom
    				) AS a
    				WHERE ISNULL(a.cheque_nro,0) > 0
    				GROUP BY a.cheque_nro
    			) b ON b.cheque_nro = a.chequeId";
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(
    1
  ));
  $cheques = $q0->fetchAll();

  $data = array();
  foreach ($cheques as $key => $c) {
    // code...
    $facts = '';
    $facturas = array();
    $invoices = '';
    if(!empty($c['facturas'])){
      $invoices .='<table class="table table-bordered" style="background-color:transparent">
        <thead>
          <th>Fecha</th>
          <th>Serie</th>
          <th>Número</th>
          <th>Monto</th>
          <th>Nit</th>
          <th>Proveedor</th>
        </thead>
        <tbody>
          ';
      $facts = explode("|", $c['facturas']);
      foreach ($facts as $key => $f) {
        // code...
        $fact = explode(';', $f);

        $sub_f = array(
          'serie'=>$fact[0],
          'fecha'=>$fact[1],
          'numero'=>$fact[2],
          'monto'=>$fact[3],
          'nit'=>$fact[4],
          'proveedor'=>$fact[5],

        );

        $facturas[] = $sub_f;
        $facturas[] = $sub_f;
        $invoices.='<tr><td>'.$fact[0].'</td><td>'.$fact[1].'</td><td>'.$fact[2].'</td><td>'.$fact[3].'</td><td>'.$fact[4].'</td><td>'.$fact[5].'</td></tr>';


      }
      $invoices.="
      </tbody>
      </table>";


      }


    $sub_array = array(
      'DT_RowId'=>$c['chequeId'],
      'chequeId'=>$c['chequeId'],
      'cajaId'=>$c['cajaId'],
      'chequeNum'=>'<h3><strong>'.$c['chequeNum'].'</strong></h3>',
      'chequeStatus'=>$c['chequeStatus'],
      'chequeUtilizadoEn'=>(!empty($c['chequeUtilizadoEn'])) ? $c['chequeUtilizadoEn'] : '',
      'chequeUtilizadoPor'=>(!empty($c['chequeUtilizadoPor'])) ? $c['chequeUtilizadoPor'] : '',
      'chequeTipoUso'=>(!empty($c['chequeTipoUso'])) ? $c['chequeTipoUso'] : '',
      'facturas'=>$invoices

      /*'factura_serie'=>(!empty($c['chequeUtilizadoPor'])) ? $c['factura_serie'] : '',
      'factura_fecha'=>(!empty($c['chequeUtilizadoPor'])) ? fecha_dmy($c['factura_fecha']) : '',
      'factura_num'=>(!empty($c['chequeUtilizadoPor'])) ? $c['factura_num'] : '',
      'factura_total'=>(!empty($c['chequeUtilizadoPor'])) ? $c['factura_total'] : '',
      'Prov_id'=>(!empty($c['chequeUtilizadoPor'])) ? $c['Prov_id'] : '',
      'Prov_nom'=>(!empty($c['chequeUtilizadoPor'])) ? $c['Prov_id']. ' - '.$c['Prov_nom'] : '',*/
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


  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
