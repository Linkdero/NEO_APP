<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  //include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $tipo_campo=$_POST['pk'];

  $docto_id=$_POST['name'];
  $valor=strtoupper($_POST['value']);//date('Y-m-d H:i:s',strtotime());


  $yes='';
  $campo_string='';
  if($tipo_campo==1){
    $campo_string='docto_titulo';
  }
  if($tipo_campo==2){
    $campo_string='docto_descripcion';
  }
  if($tipo_campo==3){
    $campo_string='docto_nombre';
  }
  if($tipo_campo==4){
    $campo_string='docto_temporalidad';
  }
  if($tipo_campo==5){
    $campo_string='docto_finalidad';
  }
  if($tipo_campo==6){
    $campo_string='docto_resultados';
  }
  if(!empty($valor)){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0="UPDATE docto_encabezado SET $campo_string=? WHERE docto_id=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(strtoupper($valor),$docto_id));
    Database::disconnect_sqlsrv();
    $yes = array('success'=>true,'docto_id' => $docto_id,'msg'=>'Done','valor_nuevo'=>$valor);

  }else{
    $yes = array('msg'=>'Error');
  }


  echo json_encode($yes);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
