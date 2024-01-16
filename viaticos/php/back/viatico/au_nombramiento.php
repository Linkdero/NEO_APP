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
  $cambio=(!empty($_POST['cambio']))?$_POST['cambio']:1;

  $tipo = '';

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sqls = "SELECT id_status, id_pais FROM vt_nombramiento WHERE vt_nombramiento=?";
  $qs = $pdo->prepare($sqls);
  $qs->execute(array($vt_nombramiento));
  $estado_actual = $qs->fetch();

  //echo $id_bodega;

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
      if($estado==8194){
        $sql = "UPDATE vt_nombramiento SET id_status=? WHERE vt_nombramiento=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($estado,$vt_nombramiento));
      }
      if($estado==935){
        $state = 0;

        if(usuarioPrivilegiado()->hasPrivilege(4)){
          $state = 936;
          $tipo = 1;
        }
        if(usuarioPrivilegiado()->hasPrivilege(316)){
          $state = 8193;
          $tipo = 2;
        }
        $estado = $state;
        $sql = "UPDATE vt_nombramiento SET id_status=?, tipo_anticipo = ? WHERE vt_nombramiento=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($state,$tipo,$vt_nombramiento));
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

      /*$sql2 = "EXEC procesa_formulario @correlativo=?, @tipo_documento=?, @renglon=?";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array($vt_nombramiento,$tipo_documento,0));*/

    }
    if($estado==940){
      /*$sql = "EXEC sp_sel_valida_exterior ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($vt_nombramiento));
      $pais = $q->fetch();

      $tipo_documento=1017;

      $sql2 = "EXEC procesa_formulario @correlativo=?, @tipo_documento=?, @renglon=?";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array($vt_nombramiento,$tipo_documento,0));*/
      $sql = "UPDATE vt_nombramiento SET id_status=? WHERE vt_nombramiento=?";
      $q = $pdo->prepare($sql);
      $q->execute(array($estado,$vt_nombramiento));

    }
    if($estado_actual['id_pais'] == 'GT'){
      if($estado==934 || $estado==1072 || $estado==1635 || $estado==1636 || $estado==1643 || $estado==6167 || $estado==7972){
        $sql = "UPDATE vt_nombramiento_detalle SET nro_frm_vt_cons = nro_frm_vt_ant, nro_frm_vt_liq = nro_frm_vt_ant
        WHERE vt_nombramiento=? AND ISNULL(nro_frm_vt_ant, 0) > 0 AND ISNULL(nro_frm_vt_liq,0) = 0";
        $q = $pdo->prepare($sql);
        $q->execute(array($vt_nombramiento));
      }
    }


  }

  $valor_anterior = array(
    'vt_nombramiento'=>$vt_nombramiento,
    'id_status'=>$estado_actual['id_status']
  );

  $valor_nuevo = array(
    'vt_nombramiento'=>$vt_nombramiento,
    'id_status'=>$estado,
    'fecha'=>date('Y-m-d H:i:s'),
    'tipo_anticipo'=>$tipo
  );

  $log = "VALUES(5, 1121, 'vt_nombramiento', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
  $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
  $q = $pdo->prepare($sql);
  $q->execute(array());





else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
