<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $vt_nombramiento=$_POST['pk'];
  $id_tipo_fecha=$_POST['name'];
  $hora_nueva=$_POST['value'];

  $s=viaticos::get_estado_by_id($vt_nombramiento);
  $yes='';
  if($s['id_status']==932 || $s['id_status']==933){
    $campo='';
    if($id_tipo_fecha==1){
      $campo='hora_regreso';
    }else{
      $campo='hora_salida';
    }

    $campo_up='';
    if($id_tipo_fecha==1){
      $campo_up='hora_salida';
    }else{
      $campo_up='hora_regreso';
    }
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT $campo, fecha_salida, fecha_regreso FROM vt_nombramiento WHERE vt_nombramiento=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($vt_nombramiento));
    $valor_actual = $q->fetch();

    $fecha_i=date('Y-m-d', strtotime($valor_actual['fecha_salida']));
    $fecha_f=date('Y-m-d', strtotime($valor_actual['fecha_regreso']));

    $sql = "SELECT descripcion FROM tbl_catalogo_detalle WHERE id_item=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($hora_nueva));
    $hs = $q->fetch();
    $hora_string=$hs['descripcion'];

    if($fecha_i==$fecha_f){
      if($id_tipo_fecha==1){
        if($hora_nueva<$valor_actual[$campo]){
          $sql0="UPDATE vt_nombramiento SET $campo_up=? WHERE vt_nombramiento=?";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(array($hora_nueva,$vt_nombramiento));
          $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'valor_actual'=>$valor_actual[$campo],'msg'=>'Done','tipo'=>$id_tipo_fecha,'valor_nuevo'=>$hora_string);
        }else{
          $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'valor_actual'=>$valor_actual[$campo],'msg'=>'Error','tipo'=>$id_tipo_fecha,'valor_nuevo'=>$hora_string);
        }
      }else if($id_tipo_fecha==2){
        if($hora_nueva>$valor_actual[$campo]){
          $sql0="UPDATE vt_nombramiento SET $campo_up=? WHERE vt_nombramiento=?";
          $q0 = $pdo->prepare($sql0);
          $q0->execute(array($hora_nueva,$vt_nombramiento));
          $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'valor_actual'=>$valor_actual[$campo],'msg'=>'Done','tipo'=>$id_tipo_fecha,'valor_nuevo'=>$hora_string);
        }else{
          $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'valor_actual'=>$valor_actual[$campo],'msg'=>'Error','tipo'=>$id_tipo_fecha,'valor_nuevo'=>$hora_string);
        }
      }
    }else{
      $sql0="UPDATE vt_nombramiento SET $campo_up=? WHERE vt_nombramiento=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($hora_nueva,$vt_nombramiento));
      $yes = array('success'=>true,'vt_nombramiento' => $vt_nombramiento,'valor_actual'=>$valor_actual[$campo],'msg'=>'Done','tipo'=>$id_tipo_fecha,'valor_nuevo'=>$hora_string);
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
