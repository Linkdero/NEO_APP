<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../../functions.php';


  $response = array();
  $filtro = $_GET['q'];
  echo json_encode($filtro);
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT d.Ped_tra, REPLACE(STR(d.Ped_num, 5), SPACE(1), '0') AS ped_num, d.Ped_fec, e.descripcion AS estado,
                e.ped_tipo_seguimiento_id,
                e.ped_tipo_seguimiento_id,
                i.Dir_com AS direccion,
                e.ped_observaciones,
                d.Ped_obs,
        ISNULL(d.Ped_justificacion,0) AS Ped_justificacion
        FROM APP_POS.dbo.PEDIDO_E d
        INNER JOIN (
          SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha, T.descripcion, T.ped_observaciones
          FROM
          (
            SELECT a.*, b.descripcion, ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk
            FROM docto_pedido_seguimiento_bitacora a
            INNER JOIN tbl_catalogo_detalle b ON a.ped_tipo_seguimiento_id = b.id_item
            WHERE a.ped_tipo_seguimiento_id IN (8146,8147,8148,8164)
          ) T
          WHERE T.rnk = 1
        ) AS e ON d.Ped_tra = e.ped_tra
        INNER JOIN (
          SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha
          FROM
          (
            SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk FROM docto_pedido_seguimiento_bitacora a
            WHERE a.ped_tipo_seguimiento_id IN (8164)
          ) T
          WHERE T.rnk = 1
        ) AS f ON d.Ped_tra = f.ped_tra
        LEFT JOIN (SELECT T.ped_tra, T.ped_tipo_seguimiento_id, T.ped_seguimiento_fecha
          FROM
          (
            SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY ped_tra ORDER BY ped_seguimiento_fecha DESC) AS rnk FROM docto_pedido_seguimiento_bitacora a
            WHERE a.ped_tipo_seguimiento_id IN (8146)
          ) T
          WHERE T.rnk = 1
        ) AS g ON d.Ped_tra = g.ped_tra
        INNER JOIN APP_POS.dbo.UNIDAD h ON d.Uni_cor=h.Uni_cor
        INNER JOIN APP_POS.dbo.DIRECCION i ON h.Dir_cor = i.Dir_cor
        --WHERE CONVERT(varchar, f.ped_seguimiento_fecha, 23) BETWEEN ? AND ?
        WHERE YEAR(f.ped_seguimiento_fecha) = ? AND e.ped_tipo_seguimiento_id = ? AND ISNULL(d.Ped_justificacion,0) = 0
        GROUP BY d.Ped_tra,d.Ped_num, d.Ped_fec,e.ped_tipo_seguimiento_id,e.descripcion,
        f.ped_seguimiento_fecha,g.ped_seguimiento_fecha,e.ped_observaciones,
        i.Dir_com,d.Ped_obs,d.Ped_justificacion
        ORDER BY f.ped_seguimiento_fecha DESC
          ";
  //if($e['id_de'])
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(date('Y'),8148));
  $pedidos = $q0->fetchAll();

  $data = array();

  foreach($pedidos as $p){

     $data[] = ['id'=>$p['Ped_tra'], 'text'=>$p['ped_num'].' * '.$p['ped_num']];
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
