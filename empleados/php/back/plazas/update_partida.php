<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  //include_once '../../../../empleados/php/back/functions.php';

  date_default_timezone_set('America/Guatemala');

  $id_plaza=$_POST['pk'];
  $valor=$_POST['value'];//date('Y-m-d H:i:s',strtotime());


  $yes='';
  if(!empty($valor)){

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql2="SELECT partida_presupuestaria FROM rrhh_plaza WHERE id_plaza=?";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array($id_plaza));
    $partida = $q2->fetch();

    $sql0="UPDATE rrhh_plaza SET partida_presupuestaria=? WHERE id_plaza=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($valor,$id_plaza));


    $compu_cliente= gethostbyaddr($_SERVER['REMOTE_ADDR']);
    //gethostbyaddr($_SERVER['REMOTE_ADDR']);

    $valor_anterior = array(
    'id_plaza'=>$id_plaza,
    'partida'=>$partida['partida_presupuestaria']
  );

  $valor_nuevo = array(
    'id_plaza'=>$id_plaza,
    'partida'=>$valor,
    'fecha'=>date('Y-m-d H:i:s'),
    'equipo'=>$compu_cliente
  );

  $log = "VALUES(5, 1121, 'vt_nombramiento', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
  $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
  $q = $pdo->prepare($sql);
  $q->execute(array());

  Database::disconnect_sqlsrv();

  $yes = array('success'=>true,'id_plaza' => $id_plaza,'msg'=>'Done','valor_nuevo'=>$valor);


  }else{
    $yes = array('msg'=>'Error');
  }


  echo json_encode($yes);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
