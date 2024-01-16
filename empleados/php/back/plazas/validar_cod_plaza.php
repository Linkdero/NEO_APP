<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){

  include_once '../functions_plaza.php';

  $id_cod_plaza = null;
  $tipo = null;

  if(!empty($_GET['id_cod_plaza'])){
    $id_cod_plaza = $_REQUEST['id_cod_plaza'];
  }

  if(!empty($_GET['tipo'])){
    $tipo = $_REQUEST['tipo'];
  }

  if($tipo == 1){
    $pdo = Database::connect_sqlsrv();
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql3 = "SELECT cod_plaza,partida_presupuestaria,id_status,descripcion,
              id_puesto,id_auditoria,existe,vacante
              FROM rrhh_plaza
              WHERE cod_plaza = ?
              ";
    $q3 = $pdo->prepare($sql3);
    $q3->execute(array($id_cod_plaza));

    $plaza = $q3->fetch();

    Database::disconnect_sqlsrv();

    if($plaza['cod_plaza'] == $id_cod_plaza){
      echo 'false';
    }else{
      echo 'true';
    }
  }else if($tipo == 2){
    echo 'true';
  }

}else{
  header("Location: ../index.php");  
}
