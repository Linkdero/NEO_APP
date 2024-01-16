<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../../../../empleados/php/back/functions.php';

  $results = array();
  $documentos = documento::get_formularios_1h();
  $data = array();
  foreach ($documentos as $f){
    $sub_array = array(
      'Ent_id'=>$f['Ent_id'],
      'Env_tra'=>$f['Env_tra'],
      'Env_num'=>$f['Env_num'],
      'Ser_ser'=>$f['Ser_ser'],
      'Prov_id'=>$f['Prov_id'],
      'Prov_nom'=>$f['Prov_nom'],
      'Bod_id'=>$f['Bod_id'],
      'Env_tot'=>number_format($f['Env_tot'],2,'.',','),
      'Bod_nom'=>$f['Bod_nom'],
      'Tdoc_id'=>$f['Tdoc_id'],
      'Tdoc_mov'=>$f['Tdoc_mov'],
      'Fh_nro'=>$f['Fh_nro'],
      'Fh_ser'=>$f['Fh_ser'],
      'Fh_fec'=>date('d-m-Y', strtotime($f['Fh_fec'])),
      'Fh_prg'=>$f['Fh_prg'],
      'Fh_imp'=>$f['Fh_imp'],
      'direccion'=>$f['direccion'],
      'usu_id'=>$f['usu_id'],
      'Env_fec'=>$f['Env_fec'],
      'accion'=>'<span class="btn btn-soft-info btn-sm" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/formularios1h/formulario_detalle.php?env_tra='.$f['Env_tra'].'&formulario='.$f['Fh_nro'].'">
        <i class="fa fa-pen"></i>
      </span>'
    );
    $data[] = $sub_array;


  }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data
  );

  $prods = documento::get_productos_man();
  date_default_timezone_set('America/Guatemala');

  $yes = '';

  /*foreach($prods AS $p){
    //echo $p['Pro_idint'] .' | | '.$p['Bod_id'].' | | '.$p['Prd_exi'].' | | '.$p['Prd_cos'];
    if(!empty($p['TOTAL']) || empty($p['TOTAL']) > 0){
      //inicio
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try{
        $pdo->beginTransaction();

        $sql0 = "INSERT INTO APP_POS.dbo.ENVIO_E (Tdoc_id, Ser_ser, Env_Fec, Env_est, Ent_id, Bod_id, Usu_id, Env_fop, Env_Ppr,Env_obs)
        VALUES(?,?,?,?,?,?,?,?,?,?)";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array(
          '004','S',date('Y-m-d',strtotime('2022-02-06')), 'p', '00001','09','cristian.gonzalez', date('Y-m-d H:i:s',strtotime('2022-02-06 20:10:19')),0,'Salida reajuste'
        ));

        //$cg=$clased->get_correlativo_generado($creador);
        $cgid = $pdo->lastInsertId();

        $sql1 = "INSERT INTO APP_POS.dbo.ENVIO_D (Env_tra, Envd_ord, Tdoc_id, Ser_ser, Env_num, Ent_id, Dir_cor, Bod_id, Pro_idint, Envd_can, Envd_cos, Envd_iva,Tdoc_mov)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array($cgid,1,'004','S',1,'00001',24,'09',$p['Pro_idint'],$p['TOTAL'],$p['Prd_cos'],0,'S'));

        // ENTRADA
        /*$sql2 = "INSERT INTO APP_POS.dbo.ENVIO_E (Tdoc_id, Ser_ser, Env_Fec, Env_est, Ent_id, Bod_id, Usu_id, Env_fop, Env_Ppr,Env_obs)
        VALUES(?,?,?,?,?,?,?,?,?,?)";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array(
          '003','S',date('Y-m-d',strtotime('2022-02-07')), 'p', '00001','09','cristian.gonzalez', date('Y-m-d H:i:s',strtotime('2022-02-07 20:10:19')),0,'Entrada reajuste'
        ));
        $cgid = $pdo->lastInsertId();
        $sql3 = "INSERT INTO APP_POS.dbo.ENVIO_D (Env_tra, Envd_ord, Tdoc_id, Ser_ser, Env_num, Ent_id, Dir_cor, Bod_id, Pro_idint, Envd_can, Envd_cos, Envd_iva,Tdoc_mov)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array($cgid,1,'003','S',1,'00001',24,'09',$p['Pro_idint'],(!empty($p['Prd_exi']) ? $p['Prd_exi'] : 1),$p['Prd_cos'],0,'E'));

        $sql0 = "UPDATE APP_POS.dbo.PRODUCTO_DETALLE SET Prd_exi = ?
                 WHERE Pro_idint = ? AND Bod_id = ?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array(
          $p['Prd_exi'],$p['Pro_idint'],'09'
        ));

        //$cg=$clased->get_correlativo_generado($creador);
        /*$sql2 = "SELECT TOP 1 Env_tra FROM APP_POS.dbo.ENVIO_E WHERE Usu_id = ? AND Bod_id = ? ORDER BY Env_tra DESC";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array(
          'cristian.gonzalez','09'
        ));
        $env_tra = $q2->fetch();

        $sql3 = "INSERT INTO APP_POS.dbo.ENVIO_D (Env_tra, Envd_ord, Tdoc_id, Ser_ser, Env_num, Ent_id, Dir_cor, Bod_id, Pro_idint, Envd_can, Envd_cos, Envd_iva,Tdoc_mov)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array($env_tra['Env_tra'],1,'003','S',1,'00001',24,'09',$p['Pro_idint'],(!empty($p['Prd_exi']) ? $p['Prd_exi'] : 1),$p['Prd_cos'],0,'E'));*/

      /*  $yes = array('msg'=>'OK','id'=>$cgid);
        $pdo->commit();
        echo json_encode($yes);

      }catch (PDOException $e){

        $yes = array('msg'=>'ERROR','id'=>$e);
        //echo json_encode($yes);
        try{ $pdo->rollBack();}catch(Exception $e2){
          $yes = array('msg'=>'ERROR','id'=>$e2);

        }
        echo json_encode($yes);
      }
      Database::disconnect_sqlsrv();
        /*Database::disconnect_sqlsrv();
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try{
        $pdo->beginTransaction();
        // ENTRADA
        $sql2 = "INSERT INTO APP_POS.dbo.ENVIO_E (Tdoc_id, Ser_ser, Env_Fec, Env_est, Ent_id, Bod_id, Usu_id, Env_fop, Env_Ppr,Env_obs)
        VALUES(?,?,?,?,?,?,?,?,?,?)";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array(
          '003','S',date('Y-m-d',strtotime('2022-02-07')), 'p', '00001','09','cristian.gonzalez', date('Y-m-d H:i:s',strtotime('2022-02-07 20:10:19')),0,'Entrada reajuste'
        ));

        //$cg=$clased->get_correlativo_generado($creador);
        $cgid = $pdo->lastInsertId();

        $sql3 = "INSERT INTO APP_POS.dbo.ENVIO_D (Env_tra, Envd_ord, Tdoc_id, Ser_ser, Env_num, Ent_id, Dir_cor, Bod_id, Pro_idint, Envd_can, Envd_cos, Envd_iva,Tdoc_mov)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array($cgid,1,'003','S',1,'00001',24,'09',$p['Pro_idint'],(!empty($p['Prd_exi']) ? $p['Prd_exi'] : 1),$p['Prd_cos'],0,'E'));

        $yes = array('msg'=>'OK','id'=>$cgid);

        /*$sql0 = "UPDATE APP_POS.dbo.PRODUCTO_DETALLE SET Prd_exi = ?
                 WHERE Pro_idint = ? AND Bod_id = ?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array(
          $p['Prd_exi'],$p['Pro_idint'],'09'
        ));*/
        /*$pdo->commit();
        echo json_encode($yes);

      }catch (PDOException $e){

        $yes = array('msg'=>'ERROR','id'=>$e);
        //echo json_encode($yes);
        try{ $pdo->rollBack();}catch(Exception $e2){
          $yes = array('msg'=>'ERROR','id'=>$e2);

        }
        echo json_encode($yes);
      }
        Database::disconnect_sqlsrv();*/

      //fin
    //}


//  }


  echo json_encode($results);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
