<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $vt_nombramiento=$_POST['pk'];
  $motivo_nuevo=$_POST['value'];

  $s=viaticos::get_estado_by_id($vt_nombramiento);
  $yes='';
  if($s['id_status']==932 || $s['id_status']==933){

    if(!empty($motivo_nuevo)){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql0="UPDATE vt_nombramiento SET motivo=? WHERE vt_nombramiento=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($motivo_nuevo,$vt_nombramiento));
      Database::disconnect_sqlsrv();
      $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'msg'=>'Done','valor_nuevo'=>$motivo_nuevo);
    }else{
      $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'msg'=>'Error','valor_nuevo'=>$motivo_nuevo);
    }

  }else{
    $yes = array('msg'=>'Error');
  }


  echo json_encode($yes);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
