<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  //$solicitante = $_SESSION['id_persona'];

  /*$clase = new empleado;
  $e = $clase->get_empleado_by_id_ficha($id_persona);*/

  $vt_nombramiento=$_POST['vt_nombramiento'];
  $estado=$_POST['estado'];
  $cambio=$_POST['cambio'];

  //echo $id_bodega;
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if($estado==933 || $estado==934){
    $sql = "UPDATE vt_nombramiento SET id_status=?, fecha_autorizado=?, usr_autoriza=? WHERE vt_nombramiento=?";
     $q = $pdo->prepare($sql);
     $q->execute(
       array(
         $estado,
         date('Y-m-d H:i:s'),
         $_SESSION['id_persona'],
         $vt_nombramiento
       ));
  }else{
    $sql = "UPDATE vt_nombramiento SET id_status=? WHERE vt_nombramiento=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($estado,$vt_nombramiento));

    if($estado==935 || $estado==7959){
      $sql2 = "UPDATE vt_nombramiento SET tipo_cambio=? WHERE vt_nombramiento=?";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array($cambio,$vt_nombramiento));

      $tipo_documento=1014;//viático anticipo
      $sql = "exec procesa_formulario @correlativo=?,@tipo_documento=?,@renglon=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($vt_nombramiento,$tipo_documento,0));
      if($estado==7959){
        $sql = "UPDATE vt_nombramiento SET id_status=? WHERE vt_nombramiento=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($estado,$vt_nombramiento));
      }
    }else
    if($estado==939){
      // el estado 39 es el estado futuro
      $sql = "EXEC sp_sel_valida_exterior ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($vt_nombramiento));
      $pais = $q->fetch();

      $tipo_documento;
      if($pais['id_pais']<>'GT'){
        $tipo_documento=1016;
        //echo $tipo_nombramiento;
      }else{
        $tipo_documento=1015;
      }

      $sql2 = "EXEC procesa_formulario @correlativo=?, @tipo_documento=?, @renglon=?";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array($vt_nombramiento,$tipo_documento,0));

    }
    if($estado==940){
      $sql = "EXEC sp_sel_valida_exterior ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($vt_nombramiento));
      $pais = $q->fetch();

      $tipo_documento=1017;

      $sql2 = "EXEC procesa_formulario @correlativo=?, @tipo_documento=?, @renglon=?";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array($vt_nombramiento,$tipo_documento,0));

    }

  }



else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
