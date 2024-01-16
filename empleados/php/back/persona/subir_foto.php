<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';

  $id_persona=$_POST['id_persona'];
  $fotografia=base64_decode(str_replace('data:image/jpeg;base64', '', $_POST['fotografia']));

  //$datos = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
//$image = $_FILES['imagen']['tmp_name'];


      //echo base64_decode($fotografia);
  $msg='';
  $pdo = Database::connect_sqlsrv();

  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "SELECT TOP 1 id_fotografia FROM rrhh_persona_fotografia WHERE id_persona=? AND fotografia_principal=? ORDER BY id_fotografia DESC";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_persona, 1));
    $foto_actual=$q0->fetch();

    if (!empty($foto_actual['id_fotografia'])) {
      // code...
      $sql0 = "UPDATE rrhh_persona_fotografia
                  SET fotografia_principal=?  WHERE id_fotografia=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array(0,$foto_actual['id_fotografia']));
    }

    $sql1 = "INSERT INTO rrhh_persona_fotografia (id_persona,fotografia)
                  VALUES(:id_persona,:foto)";
    $q1 = $pdo->prepare($sql1);
    $q1->bindParam(':id_persona',$id_persona, PDO::PARAM_INT);
    //$q1->bindParam(':tipo_fotografia',2184, PDO::PARAM_INT);
    //$q1->bindParam(':fotografia_principal',1, PDO::PARAM_BOOL);
    $q1->bindParam(':foto', $fotografia, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);

    $q1->execute();

    $sql2 = "SELECT TOP 1 id_fotografia FROM rrhh_persona_fotografia WHERE id_persona=? ORDER BY id_fotografia DESC";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array($id_persona));
    $foto_nueva=$q2->fetch();

    $sql0 = "UPDATE rrhh_persona_fotografia
                SET fotografia_principal=?, id_tipo_fotografia=?  WHERE id_fotografia=?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(1,2184,$foto_nueva['id_fotografia']));

    $compu_cliente= gethostbyaddr($_SERVER['REMOTE_ADDR']);
    $valor_anterior = array(
      'id_persona'=>$id_persona,
      'id_fotografia'=>$foto_actual['id_fotografia']
    );

    //echo $foto_nueva['id_fotografia'];
    $valor_nuevo = array(
      'id_persona'=>$id_persona,
      'descripcion'=>'Se cambió fotografia',
      'id_fotografia'=>$foto_nueva['id_fotografia'],
      'fecha'=>date('Y-m-d H:i:s'),
      'equipo'=>$compu_cliente
    );

    $log = "VALUES(158, 1163, 'rrhh_persona_fotografia', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q = $pdo->prepare($sql);
    $q->execute(array());
    $msg = array('msg'=>'OK','id'=>$foto_nueva['id_fotografia']);
    $pdo->commit();
  }catch (PDOException $e){
    $msg = array('msg'=>'ERROR','id'=>$e);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $msg = array('msg'=>'ERROR','id'=>$e2);
    }
  }

  echo json_encode($msg);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
