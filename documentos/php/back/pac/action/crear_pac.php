<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  include_once '../../../../../empleados/php/back/functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];

  $id_nombre = (!empty($_POST['id_nombre'])) ? strtoupper($_POST['id_nombre']) : NULL;
  $id_unidad = (!empty($_POST['id_unidad'])) ? $_POST['id_unidad'] : NULL;
  $id_renglon = (!empty($_POST['id_renglon'])) ? $_POST['id_renglon'] : NULL;
  $id_descripcion = (!empty($_POST['id_descripcion'])) ? strtoupper($_POST['id_descripcion']) : NULL;
  $id_ejercicio_ant = (!empty($_POST['id_ejercicio_ant'])) ? $_POST['id_ejercicio_ant'] : NULL;
  $id_year_anterior = (!empty($_POST['id_year_anterior'])) ? $_POST['id_year_anterior'] : NULL;
  $id_descripcion_year = (!empty($_POST['id_descripcion_year'])) ? $_POST['id_descripcion_year'] : NULL;
  $meses = $_POST['months'];

  $clase = new empleado;
  $clased = new documento;

  $uni_cor = $clased->get_unidad_pos($id_unidad);
  $e = $clase->get_empleado_by_id_ficha($creador);
  $depto = ($e['id_dirf'] != $id_unidad ) ? $id_unidad : NULL;

  $correlativo_actual=1;
  $ejercicio_fiscal = 2024;
  $ca=$clased->genera_correlativo_pac($e['id_dirf'],$depto,$ejercicio_fiscal);
  if(!empty($ca['id'])){
    $correlativo_actual=$ca['id']+1;
  }


  $yes='';
  $pdo = Database::connect_sqlsrv();
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "INSERT INTO APP_POS.dbo.PAC_E (pac_correlativo,pac_nombre,pac_detalle,pac_ejercicio_fiscal,
              pac_ejercicio_ant,pac_ejercicio_ant_desc,pac_renglon,pac_tipo,pac_programado,
              pac_creado_por,pac_creado_fecha,id_direccion,id_departamento,uni_cor,id_status
    )
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      $correlativo_actual,$id_nombre,$id_descripcion,$ejercicio_fiscal,$id_year_anterior,$id_descripcion_year,$id_renglon,
      1,1,$creador,date('Y-m-d H:i:s'),$e['id_dirf'],$depto,$uni_cor['Uni_cor'],1
    ));

    //$cg=$clased->get_correlativo_generado($creador);
    $cgid = $pdo->lastInsertId();

    foreach($meses AS $m){
      //if($m['checked']== 'true'){
        $sql2 = "INSERT INTO APP_POS.dbo.PAC_D (pac_id,pac_id_mes,cantidad,monto,id_status)
        values(?,?,?,?,?)";
        $q2 = $pdo->prepare($sql2);
        $cant = (!empty($m['cantidad'])) ? $m['cantidad'] : NULL;
        $mont = (!empty($m['monto'])) ? $m['monto'] : NULL;
        $q2->execute(array($cgid,$m['id_mes'],$cant,$mont,1));
      //}

    }

    $valor_anterior = array(

    );

    $valor_nuevo = array(
      'pac_id'=>$cgid,
      'campo'=>'',
      'fecha'=>date('Y-m-d H:i:s'),
      'valor'=>'Se agregó un nuevo Plan de Compras'
    );

    $log = "VALUES(325, 8017, 'APP_POS.dbo.PAC_E', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q = $pdo->prepare($sql);
    $q->execute(array());


    $yes = array('msg'=>'OK','id'=>$cgid);
    //echo json_encode($yes);
    $pdo->commit();

  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);

    }
  }

  echo json_encode($yes);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
