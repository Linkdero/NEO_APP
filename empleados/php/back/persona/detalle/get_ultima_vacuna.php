<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  $id_persona = $_GET['id_persona'];
  $tipo_accion = $_GET['tipo_accion'];
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT TOP 1 id_persona, id_vacuna, id_vacuna_dosis, id_vacuna_tipo, fecha_vacunacion
          FROM rrhh_persona_vacuna_covid WHERE id_persona = ?
          ORDER BY id_vacuna DESC";
  $p = $pdo->prepare($sql);
  $p->execute(array($id_persona));
  $v = $p->fetch();
  Database::disconnect_sqlsrv();

  $siguiente_dosis = 1;
  if($v['id_vacuna_dosis'] == 1){
    $siguiente_dosis = 2;
  }else if($v['id_vacuna_dosis'] == 2){
    $siguiente_dosis = 3;
  }

  $validaciontdosis = false;
  if($v['id_vacuna_dosis'] == 3 && $tipo_accion == 2){
    $validaciontdosis = true;
  }
  $validaciontvacuna = true;
  if($siguiente_dosis == 2){
    //solo la segunda dosis tiene que ser igual
    $validaciontvacuna = false;
  }

  $data = array(
    'id_dosis'=>$v['id_vacuna_dosis'],
    'id_tipo_vacuna'=>($v['id_vacuna_dosis'] == 1 || $v['id_vacuna_dosis'] == 2) ? $v['id_vacuna_tipo'] : 0,
    'id_fecha_vacuna'=>date('Y-m-d', strtotime($v['fecha_vacunacion'])),
    'tipo_dosis'=>($v['id_vacuna_dosis'] == 1) ? 'Primera Dosis' : 'Segunda Dosis',
    //'tipo_vacuna'=>$v['tipo_vacuna'],
    'fecha_vacuna'=>fecha_dmy($v['fecha_vacunacion']),
    'validacion'=>($v['id_vacuna_dosis'] == 1 && $tipo_accion == 2) ? false : true,
    'validaciontdosis'=>$validaciontdosis,
    'validaciontvacuna'=>$validaciontvacuna,
    'siguiente_dosis'=>$siguiente_dosis//($v['id_vacuna_dosis'] == 1) ? 2 : '',
  );

  echo json_encode($data);
else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


  ?>
