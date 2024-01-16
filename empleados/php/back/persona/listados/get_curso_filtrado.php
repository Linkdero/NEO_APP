<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();
  $filtro = $_GET['q'];
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT *
           FROM xxx_rrhh_persona_cursos
           WHERE nombre_curso LIKE '%".$_GET['q']."%' AND ISNULL(status,0) IN (0,1) ORDER BY id_curso desc
          ";
  //if($e['id_de'])
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array());
  $cursos = $q0->fetchAll();

  $data = array();

  foreach($cursos as $c){
     $data[] = ['id'=>$c['id_curso'], 'text'=>''.$c['id_curso'].' -- '.$c['nombre_tipo_curso'].' - '.$c['nombre_curso']];
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
