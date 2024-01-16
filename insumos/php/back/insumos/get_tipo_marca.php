<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
  $bodega='';
  foreach($datos AS $d){
    $bodega = $d['id_bodega_insumo'];
  }
  if(!empty($_POST["tipo_id"])){
      // Fetch state data based on the specific country
      $sql = "SELECT id_bodega_insumo, id_sub_tipo_insumo, descripcion, descripcion_corta
              FROM inv_sub_tipo_insumo
              WHERE id_bodega_insumo=? AND id_tipo_insumo=?";
      $p = $pdo->prepare($sql);
      $p->execute(array($bodega,$_POST['tipo_id']));// 65 es el id de aplicaciones
      $tipos = $p->fetchAll();

      // Generate HTML of state options list
      if(count($tipos) > 0){
          echo '<option value="0">Seleccionar Subtipo</option>';
          foreach($tipos AS $tipo){
              echo '<option value="'.$tipo['id_sub_tipo_insumo'].'">'.$tipo['id_sub_tipo_insumo'].'/'.$tipo['descripcion'].'</option>';
          }
      }else{
          echo '<option value="0">Subtipo no disponible</option>';
      }
  }elseif(!empty($_POST["marca_id"])){
      // Fetch city data based on the specific state
      $sql = "SELECT id_bodega_insumo, id_marca, id_modelo, descripcion, descripcion_corta
              FROM inv_modelos
              WHERE id_bodega_insumo=? AND id_marca=?";
      $p = $pdo->prepare($sql);
      $p->execute(array($bodega,$_POST['marca_id']));// 65 es el id de aplicaciones
      $modelos = $p->fetchAll();

      // Generate HTML of state options list
      if(count($modelos) > 0){
          echo '<option value="0">Seleccionar Modelo</option>';
          foreach($modelos AS $modelo){
              echo '<option value="0'.$modelo['id_modelo'].'">'.$modelo['descripcion'].'</option>';
          }
      }else{
          echo '<option value="0">Modelo no disponible</option>';
      }
  }
  Database::disconnect_sqlsrv();





else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
