<?php

function productos_error_notificaciones(){
    $pdo = Database::connect();
    $sql="SELECT prod.prod_id, prod.prod_status, prod.renglon_id, prod.prod_cod,prod_nm, ie_tipo AS tipo,ie_fac_id,ie_req_id, ie_prod,ie_ing_id,ie_ing_cant, ie_egr_cant, ie_existencia, ie_fecha, ing_fecha, egr_fecha
            FROM alm_producto AS prod
            LEFT JOIN (SELECT 'ing' AS ie_tipo, T1.prod_id AS ie_prod,fac_id AS ie_fac_id, ing_ing_id AS ie_ing_id,ing_cant AS ie_ing_cant, null as ie_req_id, 0 AS ie_egr_cant,
                       0 AS ie_existencia, 0 AS ie_egr_id, ing_fecha AS ie_fecha, ing_fecha as ing_fecha, NULL as egr_fecha
                       FROM alm_producto AS T1
                       LEFT JOIN (SELECT T2.ing_id AS ing_ing_id,T2.fac_id AS fac_id, T2.ing_cant as ing_cant,T2.prod_id As ing_prod, fac_fecha AS ing_fecha
                                  FROM alm_ingreso AS T2
                                  LEFT JOIN alm_factura AS T3 ON T2.fac_id = T3.fac_id
                                  WHERE T2.ing_status=1 AND T3.fac_status=1 order by T3.fac_fecha) AS ing on T1.prod_id = ing.ing_prod
                       GROUP BY ie_ing_id
                       UNION
                       SELECT 'egr' AS ie_tipo, T1.prod_id AS ie_prod,NULL AS ie_fac_id, ing_ing_id AS ie_ing_id,ing_cant AS ie_ing_cant,req_id AS ie_req_id, egr_cant AS ie_egr_cant,
                       (ing_cant-(COALESCE(egr_cant,0))) AS ie_existencia,  egr_egr_id AS ie_egr_id, egr_fecha AS ie_fecha, ing_fecha as ing_fecha, egr_fecha as egr_fecha
                       FROM alm_producto AS T1
                       LEFT JOIN (SELECT T2.ing_id AS ing_ing_id,T3.fac_id AS ing_fac_id, T2.ing_cant as ing_cant,T2.prod_id As ing_prod, fac_fecha AS ing_fecha
                                  FROM alm_ingreso AS T2
                                  LEFT JOIN alm_factura AS T3 ON T2.fac_id = T3.fac_id
                                  WHERE T2.ing_status=1 AND T3.fac_status=1 order by T3.fac_fecha) AS ing on T1.prod_id = ing.ing_prod
                       LEFT JOIN (SELECT T4.egr_cant AS egr_cant,T4.req_id AS req_id, T4.prod_id AS egr_prod,T4.ing_id AS egr_ing_id, T4.egr_id AS egr_egr_id, req_fecha AS egr_fecha
                                  FROM alm_egreso AS T4
                                  LEFT JOIN alm_requisicion AS T5 ON T4.req_id = T5.req_id
                                  WHERE T4.egr_status = 1 AND T5.req_status = 1 order by T5.req_fecha) AS egr on egr_ing_id = ing_ing_id
                        GROUP BY ie_egr_id
                        ) AS movimiento on ie_prod = prod.prod_id
            WHERE ie_fecha IS NOT NULL 
            GROUP BY concat(ie_ing_id,ie_egr_id)
            ORDER BY prod_id,ie_fecha asc, tipo desc, ie_ing_id,ie_egr_id asc";
    $e = $pdo->prepare($sql);
    $e->execute();
    $producto_movimientos = $e->fetchAll();
    Database::disconnect();
    $productos_error = array();
    $control = 0;
    $problema = 0;
    $existencia = 0;
    $producto_id_control = 0;
    $renglon = 1;
    $codigo = 2;
    $producto = 'prueba';
    foreach ($producto_movimientos as $producto_movimiento){
        if ($control == 0){
            $producto_id_control = $producto_movimiento['prod_id'];
            $renglon = $producto_movimiento['renglon_id'];
            $codigo = $producto_movimiento['prod_cod'];
            $producto = $producto_movimiento['prod_nm'];
            $control = 1;
        }
        $producto_id = $producto_movimiento['prod_id'];
        if($producto_id_control == $producto_id){
            if ($producto_movimiento['tipo'] == 'ing') {
                $existencia = $existencia + $producto_movimiento['ie_ing_cant'];
            }else if ($producto_movimiento['tipo'] == 'egr') {
                $existencia = $existencia - $producto_movimiento['ie_egr_cant'];
                if ($producto_movimiento['ing_fecha'] > $producto_movimiento['egr_fecha']){$problema++;}
            }
            if ($existencia < 0){$problema++;}
        }else{
            $productos_error[$producto_id_control] = ['error' => $problema,'renglon' => $renglon, 'codigo' => $codigo, 'producto' => $producto];
            $producto_id_control = $producto_id;
            $renglon = $producto_movimiento['renglon_id'];
            $codigo = $producto_movimiento['prod_cod'];
            $producto = $producto_movimiento['prod_nm'];
            $problema = 0;
            $existencia = 0;
            if ($producto_movimiento['tipo'] == 'ing') {
                $existencia = $existencia + $producto_movimiento['ie_ing_cant'];
            }else if ($producto_movimiento['tipo'] == 'egr') {
                $existencia = $existencia - $producto_movimiento['ie_egr_cant'];
                if ($producto_movimiento['ing_fecha'] > $producto_movimiento['egr_fecha']){$problema++;}
            }
            if ($existencia < 0){$problema++;}

        }
    }
    return $productos_error;
}

function producto_info_notificaciones($id){
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT T1.prod_id,T1.prod_status,renglon_id,prod_cod,prod_nm,prod_desc,prod_min,prod_max,T1.med_id,med_nm,ing_ing_id,sum(ing_cant),sum(ing_costo),sum(egr_cant), sum(ing_cant-COALESCE(egr_cant,0)) AS existencia, sum((ing_costo/ing_cant)*(ing_cant-COALESCE(egr_cant,0))) AS existencia_costo
            FROM alm_producto AS T1 
            LEFT JOIN (SELECT T2.ing_id AS ing_ing_id,T2.ing_cant as ing_cant,T2.prod_id As ing_prod ,(ing_costo - ing_descuento - (ing_costo*porcentaje_descuento)) AS ing_costo
                       FROM alm_ingreso AS T2 
                       LEFT JOIN (SELECT T1.fac_id,fac_status, coalesce(sum(T3.ing_costo),0) as factura_subtotal, coalesce(fac_descuento,0) AS fac_descuento, coalesce((sum(T3.ing_costo-T3.ing_descuento) - T1.fac_descuento),0) AS factura_total, coalesce((T1.fac_descuento/sum(T3.ing_costo)),0) AS porcentaje_descuento
                                    FROM alm_factura AS T1
                                    LEFT JOIN alm_ingreso AS T3 on T1.fac_id = T3.fac_id and ing_status = 1
                                    GROUP BY T1.fac_id) AS T3 ON T2.fac_id = T3.fac_id 
                       WHERE T2.ing_status=1 AND T3.fac_status=1) AS ing on T1.prod_id = ing.ing_prod 
            LEFT JOIN (SELECT sum(T4.egr_cant) AS egr_cant,T4.prod_id AS egr_prod,T4.ing_id AS egr_ing_id 
                       FROM alm_egreso AS T4 
                       LEFT JOIN alm_requisicion AS T5 ON T4.req_id = T5.req_id 
                       WHERE T4.egr_status = 1 AND T5.req_status not LIKE 0 group by T4.ing_id ) AS egr on egr_ing_id = ing_ing_id
            LEFT JOIN alm_medida As T6 on T1.med_id=T6.med_id 
            WHERE prod_id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($id));
    $producto = $q->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();
    return $producto;
}