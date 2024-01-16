<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $parts = explode("-",$_POST['pk']);

  $vt_nombramiento=$parts['0'];
  $id_empleado=$parts['1'];
  $id_tipo_fecha=$_POST['name'];
  $fecha_nueva=date('Y-m-d', strtotime($_POST['value']));

  $s=viaticos::get_estado_by_id($vt_nombramiento);
  $yes='';
  if($s['id_status']==938 || $s['id_status']==939){
    $campo='';
    if($id_tipo_fecha==1){
      $campo='fecha_regreso';
    }else{
      $campo='fecha_salida';
    }

    $campo_up='';
    if($id_tipo_fecha==1){
      $campo_up='fecha_salida';
    }else{
      $campo_up='fecha_regreso';
    }
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT $campo FROM vt_nombramiento_detalle WHERE vt_nombramiento=? AND id_empleado=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($vt_nombramiento,$id_empleado));
    $valor_actual = $q->fetch();


    $fecha_actual=date('Y-m-d',strtotime($valor_actual[$campo]));

    if($id_tipo_fecha==1){
      if($fecha_nueva<=$fecha_actual){

        $sql0="UPDATE vt_nombramiento_detalle SET $campo_up=? WHERE vt_nombramiento=? AND id_empleado=?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array($fecha_nueva,$vt_nombramiento,$id_empleado));
        $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'valor_actual'=>fecha_dmy($valor_actual[$campo]),'msg'=>'Done','tipo'=>$id_tipo_fecha,'valor_nuevo'=>fecha_dmy($fecha_nueva));
      }else{
        $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'valor_actual'=>fecha_dmy($valor_actual[$campo]),'msg'=>'Error','tipo'=>$id_tipo_fecha,'valor_nuevo'=>fecha_dmy($fecha_nueva));
      }
    }else if($id_tipo_fecha==2){
      if($fecha_nueva>=$fecha_actual){
        $sql0="UPDATE vt_nombramiento_detalle SET $campo_up=? WHERE vt_nombramiento=? AND id_empleado=?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array($fecha_nueva,$vt_nombramiento,$id_empleado));
        $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'valor_actual'=>fecha_dmy($valor_actual[$campo]),'msg'=>'Done','tipo'=>$id_tipo_fecha,'valor_nuevo'=>fecha_dmy($fecha_nueva));
      }else{
        $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'valor_actual'=>fecha_dmy($valor_actual[$campo]),'msg'=>'Error','tipo'=>$id_tipo_fecha,'valor_nuevo'=>fecha_dmy($fecha_nueva));
      }
    }
    Database::disconnect_sqlsrv();
  }
  else{
    $yes = array('msg'=>'Error');
  }


  echo json_encode($yes);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
