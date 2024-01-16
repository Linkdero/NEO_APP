<?php

include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true){
  date_default_timezone_set('America/Guatemala');
  include_once '../functions.php';
  
    $nro_vale = $_POST['nro_vale'];
    $despacha = $_POST['id_despacha'];
    $recibe = $_POST['id_recibe'];
    $bomba = $_POST['id_bomba'];
    $cant_autoriza = $_POST['cant_autor'];
    $galones = $_POST['cant_galones'];
    $kilom = $_POST['km_actual'];
    $descripcion = $_POST['observa'];
    $id_tipo = $_POST['id_tipo_combustible'];

    $pdo = Database::connect_sqlsrv();

    try{
        $pdo->beginTransaction();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        // id_bomba_combustible
        $sql0 = "SELECT c.id_bomba_despacho, rtrim(i.numero_serie)+' / '+RTRIM(p.descripcion) descripcion, c.id_bomba_combustible, id_tipo_combustible
        from dayf_combustible_corte_bombas c
        left outer join inv_producto_insumo_detalle i on c.id_bomba_despacho = i.id_prod_ins_detalle
        left outer join inv_producto_insumo p on c.id_bomba_combustible = p.id_producto_insumo
        where c.id_corte = (SELECT top 1 id_corte from dayf_combustible_corte order by id_corte desc) and c.id_tipo_combustible = ?
        ORDER BY c.id_corte_bomba " ;
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array($id_tipo));
        $id_principal = $q0->fetch();        
        $principal = $id_principal['id_bomba_combustible'];

        // obtener numero de inventario
        $sql1 = "SELECT inv_id_doc_insumo from dayf_combustibles WHERE nro_vale = ?";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array($nro_vale));
        $nro_inventa = $q1->fetch();

        $sql2 = "UPDATE dayf_combustibles set 
                    id_estado = 1148, 
                    fecha_despacho = getdate(),
                    km_actual = ?,
                    cant_galones = ?,
                    id_persona_despacha_gasolina = ?,
                    id_persona_recibe_gasolina = ?,
                    id_bomba_principal = ?,
                    id_bomba_despacho = ?
                    where nro_vale = ? ";

        // echo $galones.'-'.$cant_autoriza.'-'.$principal.'-'.$id_tipo;

        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($kilom,$galones,$despacha,$recibe,$principal,$bomba,$nro_vale ));

        $sql3 = "UPDATE inv_movimiento_encabezado set id_estado_documento=4347 where id_doc_insumo = ? ";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array($nro_inventa['inv_id_doc_insumo']));

        $sql4 = "UPDATE inv_producto_insumo set existencia = existencia - ? where id_producto_insumo = ? ";
        $q4 = $pdo->prepare($sql4);
        $q4->execute(array($galones,$principal));

        $sql5 = "UPDATE inv_producto_insumo set reservado = reservado - ?  where id_producto_insumo = ? ";
        $q5 = $pdo->prepare($sql5);
        $q5->execute(array($cant_autoriza,$id_tipo));

        $response = array(
            "status" => true,
            "msg" => "Ok"
        );

        echo json_encode($response);
        $pdo->commit();

    }catch (PDOException $e){
        echo $e;
        try{ $pdo->rollBack();}catch(Exception $e2){
            echo $e2;
        }
    }

    Database::disconnect_sqlsrv();
    
}else{
    echo "<script type='text/javascript'> window.location='principal'; </script>";
}
  
?>
  