<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $vt_nombramiento=$_POST['vt_nombramiento'];
  $pais=$_POST['pais_id'];
  $departamento=$_POST['dep_id'];
  $municipio=$_POST['muni_id'];
  $aldea=$_POST['ald_id'];
  $f_ini=$_POST['f_ini'];
  $f_fin=$_POST['f_fin'];
  $h_ini=$_POST['h_ini'];
  $h_fin=$_POST['h_fin'];

  if (is_numeric($departamento)) {
    $valor_anterior=array(
      'vt_nombramiento'=>$vt_nombramiento
    );

    $valor_nuevo = array(
      'vt_nombramiento'=>$vt_nombramiento,
      'id_pais'=>$pais,
      'id_departamento'=>$departamento,
      'id_municipio'=>$municipio,
      'id_aldea'=>(!empty($aldea))?$aldea:0,
      'f_ini'=>$f_ini,
      'h_ini'=>$h_ini,
      'f_fin'=>$f_fin,
      'h_fin'=>$h_fin,
      'tipo'=>'2'
    );
    $pdo = Database::connect_sqlsrv();
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $log = "VALUES(5, 1121, 'vt_nombramiento', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";

      $clase = new viaticos;
      $id_1=$clase->get_id_by_descripcion($h_ini);
      $id_2=$clase->get_id_by_descripcion($h_fin);
      $sql0="SELECT TOP 1 reng_num FROM vt_nombramiento_destino WHERE vt_nombramiento=? ORDER BY reng_num DESC";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($vt_nombramiento));
      $reng = $q0->fetch();
      $reng_num=0;
      if(!empty($reng['reng_num'])){
        $reng_num=$reng['reng_num']+1;
      }else{
        $reng_num=1;
      }

      $sql1="UPDATE vt_nombramiento SET descripcion_lugar=? WHERE vt_nombramiento=? ;
      INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
      $q1 = $pdo->prepare($sql1);
      $q1->execute(array(2,$vt_nombramiento));

      $sql2="INSERT INTO vt_nombramiento_destino (vt_nombramiento, reng_num, bln_confirma, id_pais, id_departamento, id_municipio, id_aldea, fecha_ini, fecha_fin, hora_ini, hora_fin, id_persona, fecha_ingreso,tipo_registro)
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array($vt_nombramiento,$reng_num,1,$pais,$departamento,$municipio,$aldea,date('Y-m-d', strtotime($f_ini)),date('Y-m-d', strtotime($f_fin)),$id_1['id_item'],$id_2['id_item'],$_SESSION['id_persona'],date('Y-m-d H:i:s'),2));

      echo 'OK';
      $pdo->commit();
    }catch (PDOException $e){
      echo $e;
      try{ $pdo->rollBack();}catch(Exception $e2){
        echo $e2;
      }
    }

  }else{
    echo 'Seleccione lugares';
  }









else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
