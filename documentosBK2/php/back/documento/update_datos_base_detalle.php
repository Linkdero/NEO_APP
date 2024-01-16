<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  //include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $parametros=$_POST['pk'];
  $porciones = explode("-", $parametros);
  $item_id= $porciones[0]; // porción1
  $campo= $porciones[1]; // porción2
  $docto_id=$_POST['name'];
  $valor=$_POST['value'];//date('Y-m-d H:i:s',strtotime());


  $yes='';
  if(!empty($valor)){
    $campo_string='fecha';
    $value='';
    $value_='';
    if($campo==1){
      $campo_string='valor';
      $value=$valor;
      $value_=$valor;
    }else{
      $value=date('Y-m-d',strtotime($valor));
      $value_=$valor;
    }

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0="UPDATE docto_base_detalle SET $campo_string=? WHERE docto_id=? AND base_detalle_id=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($value,$docto_id,$item_id));
    Database::disconnect_sqlsrv();
    $yes = array('success'=>true,'docto_id' => $docto_id,'msg'=>'Done','valor_nuevo'=>$value_);

  }else{
    $yes = array('msg'=>'Error');
  }


  echo json_encode($yes);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
