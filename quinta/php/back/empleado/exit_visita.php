<?php

include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');
?>
<?php

  $carne = $_POST['gafete'];
  $puerta = visita::get_data_by_ip($_SERVER["REMOTE_ADDR"]);
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sqlGafete = "SELECT id FROM tbl_control_GafetexCodigo
            WHERE id=? AND estado=? AND id_puerta=?";
  $p0 = $pdo->prepare($sqlGafete);
  $p0->execute(array($carne,1,$puerta['id_puerta']));
  $estado = $p0->fetch();
  if($estado['id'] == $carne){
    $sql = "UPDATE tbl_control_visitas SET salida=?, hora_sale=?
            WHERE id_puerta=? AND no_gafete=? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(1,date('H:i:s'),$puerta['id_puerta'],$carne));
    $sql2 = "UPDATE tbl_control_GafetexCodigo SET estado=?
              WHERE id=? ";
    $stmt2 = $pdo->prepare($sql2);
    if($stmt2->execute(array(0,$carne))){
      echo 1;
    }else{
      echo 2;
    }  
  }else{
    echo 2;
  }
  Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
