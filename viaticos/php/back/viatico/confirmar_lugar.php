<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $vt_nombramiento=$_POST['vt_nombramiento'];
  $departamento=$_POST['departamento'];
  $municipio=$_POST['municipio'];
  $aldea=$_POST['aldea'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0="SELECT vt_nombramiento,id_departamento, id_municipio, id_aldea FROM vt_nombramiento WHERE vt_nombramiento=?";
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array($vt_nombramiento));
  $a= $q0->fetch();

  $valor_anterior = array(
    'vt_nombramiento'=>$a['vt_nombramiento'],
    'id_departamento'=>$a['id_departamento'],
    'id_municipio'=>$a['id_municipio'],
    'id_aldea'=>$a['id_aldea']
  );

  $valor_nuevo = array(
    'vt_nombramiento'=>$vt_nombramiento,
    'id_departamento'=>$departamento,
    'id_municipio'=>$municipio,
    'id_aldea'=>$aldea
  );

  $log = "VALUES(5, 1121, 'vt_nombramiento', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";

  /*$sql1="UPDATE vt_nombramiento SET id_departamento=?, id_municipio=?, id_aldea=?, descripcion_lugar=1 WHERE vt_nombramiento=?;
         INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;*/
  $sql1="UPDATE vt_nombramiento SET descripcion_lugar=? WHERE vt_nombramiento=?;
  INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
  $q1 = $pdo->prepare($sql1);
  $q1->execute(array(1,$vt_nombramiento));





else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
