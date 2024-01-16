<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $estados=array();
  $data=array();

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT id_catalogo, id_item, id_status, descripcion_corta, descripcion
          FROM tbl_catalogo_detalle WHERE id_catalogo=?";
  $p = $pdo->prepare($sql);
  $p->execute(array(125));// 65 es el id de aplicaciones
  $estados = $p->fetchAll();
  Database::disconnect_sqlsrv();

  $data = array();

  foreach ($estados as $e){
    $total=0;
    $sub_array = array(
      'id_status'=>$e['id_item'],
      'estado'=>$e['descripcion']
    );

    $data[]=$sub_array;
  }

echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
