<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true){
  date_default_timezone_set('America/Guatemala');
  include_once '../functions.php';

        $fecha = date('Y-m-d H:i:s');
        //$entrega = $_POST['id_entrega'];
        $tipo_accion = $_POST['tipo_accion'];
        $entrega = $_SESSION['id_persona'];
        $destino = (!empty($_POST['id_destino_c']))?$_POST['id_destino_c']:null;
        $placa_ex = '';
        $id_vale = (!empty($_POST['id_vale']))?$_POST['id_vale']:NULL;
        $vehiculo = (!empty($_POST['id_vehiculo_']))?$_POST['id_vehiculo_']:NULL;
        $txt_caracter = (!empty($_POST['txt_caracter_']))?$_POST['txt_caracter_']:NULL;;
        $conductor = (!empty($_POST['id_conductor_']))?$_POST['id_conductor_']:null;
        $evento = 1116; // $_POST['id_evento'];
        
        $combustible = (!empty($_POST['id_combustible']))?$_POST['id_combustible']:null;
        
        $bodega = 4107;
        $id_estado = 1150;
        $id_doc = 0;
        $refer = '';
        $placa_ex = 0;
        $tlleno=(!empty($_POST['chk_Tanque_']))?0:1;

        // despacho
        $nro_vale = (!empty($_POST['nro_vale'])) ? $_POST['nro_vale'] : NULL;
        $despacha = (!empty($_POST['id_despacho_'])) ? $_POST['id_despacho_'] : NULL;
        $recibe = (!empty($_POST['id_recibe_'])) ? $_POST['id_recibe_'] : null;
        $bomba = (!empty($_POST['id_bomba'])) ? $_POST['id_bomba'] : null;
        $cant_autoriza = (!empty($_POST['cant_autor'])) ? $_POST['cant_autor'] : $_POST['cant_autor'];
        $kilom = (!empty($_POST['id_kmactual'])) ? $_POST['id_kmactual'] : null;
        $id_tipo = (!empty($_POST['id_tipo_combustible'])) ? $_POST['id_tipo_combustible'] : null;
        //fin despacho

        $galones = $_POST['id_galones_'];
        $descripcion = $_POST['observa'];

        $clase = new vehiculos;
        $corte_comb = $clase->genera_corte();
        $corte = array();
        $corte = $clase->get_corte_combustible();

        $pdo = Database::connect_sqlsrv();

        $message = '';
        $respuesta = '';
        try{
            $pdo->beginTransaction();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if($tipo_accion == 1){
                //crear
/*                 // id_bomba_combustible
                $sql0 = "SELECT c.id_bomba_despacho, rtrim(i.numero_serie)+' / '+RTRIM(p.descripcion) descripcion, c.id_bomba_combustible, id_tipo_combustible
                from dayf_combustible_corte_bombas c
                left outer join inv_producto_insumo_detalle i on c.id_bomba_despacho = i.id_prod_ins_detalle
                left outer join inv_producto_insumo p on c.id_bomba_combustible = p.id_producto_insumo
                where c.id_corte = (SELECT top 1 id_corte from dayf_combustible_corte order by id_corte desc) and c.id_tipo_combustible = ?
                ORDER BY c.id_corte_bomba " ;
                $q0 = $pdo->prepare($sql0);
                $q0->execute(array($combustible));
                $id_principal = $q0->fetch();        
                $principal = $id_principal['id_bomba_combustible']; */

                //ACTUALIZA CORRELATIVO
                $sql0= "UPDATE tbl_correlativo_detalle set v_actual=v_actual+1 where id_correlativo=1140 ";
                $q0 = $pdo->prepare($sql0);
                $q0->execute();

                $correl = array();
                $correl = $clase->get_correla();

                $sql1 = "INSERT INTO inv_movimiento_encabezado (
                id_bodega_insumo,
                fecha,
                descripcion,
                id_persona_entrega,
                id_status,
                id_estado_documento,
                id_tipo_movimiento,
                flag_automatico,
                id_documento_ref_tipo,
                id_documento_ref )
                VALUES (?,?,?,?,?,?,?,?,?,?)";
                $stmt = $pdo->prepare($sql1);
                $stmt->execute(array(
                    $bodega,
                    $fecha,
                    $descripcion,
                    $entrega, 
                    1,
                    4348,
                    2,
                    1,
                    4372,
                    0 ));

                // id_documento
                $sql2 = "SELECT MAX(id_doc_insumo) AS id FROM inv_movimiento_encabezado " ;
                $q2 = $pdo->prepare($sql2);
                $q2->execute();
                $id_doc = $q2->fetch();

                // id_direccion
                $sql3 = "SELECT id_persona, id_secre, id_subsecre, id_direc from saas_app.dbo.xxx_rrhh_ficha  
                    WHERE id_persona = ?";
                $q3 = $pdo->prepare($sql3);
                $q3->execute(array($conductor));
                $id_dir = $q3->fetch();

                //persona asignada
                if(!empty($id_dir['id_direc'])){
                    $sql4 = "INSERT INTO inv_movimiento_persona_asignada
                    (id_bodega_insumo,
                    id_doc_insumo,
                    flag_firmante,
                    id_persona,
                    id_persona_secretaria_recibe,
                    id_persona_subsecretaria_recibe,
                    id_persona_direccion_recibe,
                    id_persona_diferente )
                    VALUES (?,?,?,?,?,?,?,?)";
                    $q4 = $pdo->prepare($sql4);
                    $q4->execute(array($bodega,$id_doc['id'],True,$conductor,$id_dir['id_secre'],$id_dir['id_subsecre'],$id_dir['id_direc'],0));
                }elseif(!empty($id_dir['id_subsecre'])){
                    $sql4 = "INSERT INTO inv_movimiento_persona_asignada
                    (id_bodega_insumo,
                    id_doc_insumo,
                    flag_firmante,
                    id_persona,
                    id_persona_secretaria_recibe,
                    id_persona_subsecretaria_recibe,
                    id_persona_diferente )
                    VALUES (?,?,?,?,?,?,?)";
                    $q4 = $pdo->prepare($sql4);
                    $q4->execute(array($bodega,$id_doc['id'],True,$conductor,$id_dir['id_secre'],$id_dir['id_subsecre'],0));
                }else{
                    $sql4 = "INSERT INTO inv_movimiento_persona_asignada
                    (id_bodega_insumo,
                    id_doc_insumo,
                    flag_firmante,
                    id_persona,
                    id_persona_secretaria_recibe,
                    id_persona_diferente )
                    VALUES (?,?,?,?,?,?)";
                    $q4 = $pdo->prepare($sql4);
                    $q4->execute(array($bodega,$id_doc['id'],True,$conductor,$id_dir['id_secre'],0));
                }

                //echo $bodega.' | '.$id_doc['id'].' | '.True.' | '.$conductor.' | '.$id_dir['id_secre'].' | '.$id_dir['id_subsecre'],$id_dir['id_direc'],0
                //movimiento detalle
            
                $sql5= "INSERT INTO inv_movimiento_detalle (
                            id_bodega_insumo,
                            id_doc_insumo,
                            id_prod_insumo,
                            cantidad_entregada,
                            id_movimiento_ref )
                        VALUES (?,?,?,?,?)";

                $q5 = $pdo->prepare($sql5);
                $q5->execute(array($bodega,$id_doc['id'],$combustible,$galones,4372));

                // id_vehiculo_asignacion
                $sql6 = "SELECT TOP 1 id_vehiculo_asignacion id_asigna FROM dayf_vehiculo_asignacion
                    WHERE id_vehiculo = ? ORDER BY id_vehiculo_asignacion DESC";
                $q6 = $pdo->prepare($sql6);
                $q6->execute(array($vehiculo));
                $asigna = $q6->fetch();

                // id persona autoriza
                $sql7 = "SELECT id_ref_catalogo id_autoriza, descripcion nombre 
                    from tbl_catalogo_detalle where id_item = 7940 ";
                $q7 = $pdo->prepare($sql7);
                $q7->execute(array($vehiculo));
                $autoriza = $q7->fetch();

                if(!empty($vehiculo)){  // si es un vehiculo
                    if($destino == 1144) { // vehiculos (propios)
                        $sql8 = "INSERT INTO dayf_combustibles (
                            id_corte, 
                            id_estado, 
                            nro_vale, 
                            nro_referencia, 
                            id_tipo_uso,
            
                            id_vehiculo, 
                            id_vehiculo_asignacion, 
                            id_evento, 
                            fecha_entrega, 
                            cant_galones_autorizados, 
                            flag_tanque_lleno, 
                            id_persona_recibe_vale, 
                            id_persona_entrega_vale, 
                            id_persona_autoriza_vale, 
                            id_tipo_combustible, 
                            inv_bodega_insumo, 
                            inv_id_doc_insumo, 
                            id_auditoria
                            )
                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                        $stmt = $pdo->prepare($sql8);
                        $stmt->execute(array(
                            $corte['id_corte'],
                            $id_estado,
                            $correl['correl'], 
                            $txt_caracter, 
                            $destino,
            
                            $vehiculo, 
                            $asigna['id_asigna'],
                            $evento, 
                            $fecha ,
                            $galones, 
                            $tlleno,
                            $conductor,
                            $entrega,
                            $autoriza['id_autoriza'],
                            $combustible, 
                            $bodega, 
                            $id_doc['id'],
                            0 ));
                    }else if ($destino == 1147){  // vehiculos arrendados
                        $sql8 = "INSERT INTO dayf_combustibles (
                            id_corte, 
                            id_estado, 
                            nro_vale, 
                            nro_referencia, 
                            id_tipo_uso,
            
                            id_vehiculo_externo, 
                            id_vehiculo_asignacion, 
                            id_evento, 
                            fecha_entrega, 
                            cant_galones_autorizados, 
                            flag_tanque_lleno, 
                            id_persona_recibe_vale, 
                            id_persona_entrega_vale, 
                            id_persona_autoriza_vale, 
                            id_tipo_combustible, 
                            inv_bodega_insumo, 
                            inv_id_doc_insumo, 
                            id_auditoria
                            )
                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                        $stmt = $pdo->prepare($sql8);
                        $stmt->execute(array(
                            $corte['id_corte'],
                            $id_estado,
                            $correl['correl'], 
                            $txt_caracter, 
                            $destino,
            
                            $vehiculo, 
                            $asigna['id_asigna'],
                            $evento, 
                            $fecha ,
                            $galones, 
                            $tlleno,
                            $conductor,
                            $entrega,
                            $autoriza['id_autoriza'],
                            $combustible, 
                            $bodega, 
                            $id_doc['id'],
                            0 ));
                    }
                    
                    }else{  //  si no es un vehiculo 
                    $sql8 = "INSERT INTO dayf_combustibles (
                        id_corte, 
                        id_estado, 
                        nro_vale, 
                        nro_referencia, 
                        id_tipo_uso,
                    
                        id_evento, 
                        fecha_entrega, 
                        cant_galones_autorizados, 
                        flag_tanque_lleno, 
                        id_persona_recibe_vale, 
                        id_persona_entrega_vale, 
                        id_persona_autoriza_vale, 
                        id_tipo_combustible, 
                        inv_bodega_insumo, 
                        inv_id_doc_insumo, 
                        id_auditoria )
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $stmt = $pdo->prepare($sql8);
                    $stmt->execute(array(
                        $corte['id_corte'],
                        $id_estado,
                        $correl['correl'], 
                        $txt_caracter, 
                        $destino,
                        /*$vehiculo, 
                        $asigna['id_asigna'],*/
                        $evento, 
                        $fecha ,
                        $galones, 
                        $tlleno,
                        $conductor,
                        $entrega,
                        $autoriza['id_autoriza'],
                        $combustible, 
                        $bodega, 
                        $id_doc['id'],
                        0 ));
                }

                // id_documento
                $sql = "SELECT MAX(id_combustible) AS id FROM dayf_combustibles WHERE nro_vale = ? ";
                $q9 = $pdo->prepare($sql);
                $q9->execute(array($correl['correl']));
                $id_doc_comb = $q9->fetch();

                //ACTUALIZA ENCABEZADO CON id COMBUSTIBLE
                $sql= "UPDATE inv_movimiento_encabezado set id_documento_ref = ? where id_doc_insumo = ? ";
                $q10 = $pdo->prepare($sql);
                $q10->execute(array($id_doc_comb['id'],$id_doc['id']));

                //ACTUALIZA DETALLE CON id COMBUSTIBLE
                $sql= "UPDATE inv_movimiento_detalle set id_doc_insumo_ref = ? where id_doc_insumo = ? ";
                $q11 = $pdo->prepare($sql);
                $q11->execute(array($id_doc_comb['id'],$id_doc['id']));

                echo $combustible;

                //ACTUALIZA EXISTENCIAS
                $sql12 = "UPDATE inv_producto_insumo set reservado = reservado + ?  where id_producto_insumo = ? ";
                $q12 = $pdo->prepare($sql12);
                $q12->execute(array($galones,$combustible));


            }else if($tipo_accion == 2){
                //anular

                // obtener numero de inventario
                $sql1 = "SELECT inv_id_doc_insumo from dayf_combustibles WHERE nro_vale = ?";
                $q1 = $pdo->prepare($sql1);
                $q1->execute(array($nro_vale));
                $nro_inventa = $q1->fetch();

                $sql2 = "UPDATE dayf_combustibles set id_estado=1149 where nro_vale = ? ";
                $q2 = $pdo->prepare($sql2);
                if($q2->execute(array($nro_vale))){

                    $sql3 = "UPDATE inv_movimiento_encabezado set id_status=0 where id_doc_insumo = ? ";
                    $q3 = $pdo->prepare($sql3);
                    if($q3->execute(array($nro_inventa['inv_id_doc_insumo']))){
                        $response = array(
                            "status" => true,
                            "msg" => "Ok"
                        );
                    }else{
                        $response = array(
                            "status" => false,
                            "msg" => "Error"
                        );
                    }
                }else{
                    $response = array(
                        "status" => false,
                        "msg" => "Error"
                    );
                }
                echo json_encode($response);




            }else if($tipo_accion == 3){
                //despachar



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
            
                    // echo $principal;
            
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

                    $respuesta = 'Vale despachado';
            
            



            }

            $pdo->commit();

            $message = array('msg'=>'OK','message'=>$respuesta);
        }catch (PDOException $e){
             
            try{ $pdo->rollBack();}catch(Exception $e2){
                $message = array('msg'=>$e2);
            }
            $message = array('msg'=>$e);
        }

        echo json_encode($message);
        Database::disconnect_sqlsrv();
   
}else{
    echo "<script type='text/javascript'> window.location='principal'; </script>";
}
    
?>
              