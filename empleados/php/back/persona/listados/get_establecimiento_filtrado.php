<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();
  $filtro = $_GET['q'];
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT id_catalogo, id_item, id_status, descripcion FROM tbl_catalogo_detalle WHERE id_catalogo IN (?,?) AND id_status=? AND descripcion LIKE '%".$_GET['q']."%' ORDER BY id_item desc
          ";
  //if($e['id_de'])
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(12,13,1));
  $cursos = $q0->fetchAll();

  $data = array();

  foreach($cursos as $c){
     $data[] = ['id'=>$c['id_item'], 'text'=>$c['descripcion']];
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
