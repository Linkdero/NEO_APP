<?php

ini_set('display_errors', 1);
    error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {

  include_once '../../functions.php';
  include_once '../../../../../empleados/php/back/functions.php';
  date_default_timezone_set('America/Guatemala');

  $creador = $_SESSION['id_persona'];
  $ped_tra = $_POST['ped_tra'];
  $file = $_FILES["id_documento_respaldo"];
  $nombre = $file["name"];
  $tipo = $file["type"];
  $ruta_provisional = $file["tmp_name"];
  $size = $file["size"];
  /*$dimensiones = getimagesize($ruta_provisional);
  $width = $dimensiones[0];
  $height = $dimensiones[1];*/
  $carpeta = "../../../front/pedidos/files/";

  $yes='';
  $pdo = Database::connect_sqlsrv();
  $src = $carpeta.$nombre;


  $fecha = date('Y-m-d H:i:s');
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "SELECT TOP 1 ped_tra, reng_num, archivo, id_status, subido_por, subido_en, descripcion, observaciones FROM docto_ped_doctos_respaldo WHERE ped_tra = ?
            ORDER BY reng_num DESC";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      $ped_tra
    ));
    $ant = $q0->fetch();

    $sqlp = "SELECT REPLACE(STR(Ped_num, 5), SPACE(1), '0') AS Ped_num FROM APP_POS.dbo.PEDIDO_E WHERE ped_tra = ?";
    $qp = $pdo->prepare($sqlp);
    $qp->execute(array(
      $ped_tra
    ));
    $ped_info = $qp->fetch();

    $reng_num = 1;
    if(!empty($ant['reng_num'])){
      $reng_num += $ant['reng_num'];
    }

    /*$url = "https://report.feel.com.gt/ingfacereport/ingfacereport_documento?uuid=6483DE59-B517-44DF-B568-BB4C4D967CAA";
    file_put_contents($carpeta."/sample.pdf",file_get_contents($url));*/

    /*$sql1 = "UPDATE docto_ped_doctos_respaldo SET id_status = ? WHERE ped_tra = ?";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array(
      0,$ped_tra
    ));*/
    move_uploaded_file($ruta_provisional, $src);
    $archivo = $src;

    if(file_exists($archivo)) {
      //echo 'The file "'.$name.'" exists.';
      $sql2 = "INSERT INTO docto_ped_doctos_respaldo (ped_tra, reng_num, archivo, id_status, subido_por, subido_en, descripcion)
              VALUES(?,?,?,?,?,?,?)";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array(
        $ped_tra,$reng_num, $nombre, 0, $_SESSION['id_persona'],$fecha,'Documentos de Respaldo'
      ));

      move_uploaded_file($ruta_provisional, $src);
      $new_name = $ped_tra.'-'.$reng_num.'.pdf';
      rename($src, $carpeta.$new_name);

      $sql2 = "UPDATE docto_ped_doctos_respaldo SET archivo = ?
              WHERE ped_tra = ? AND reng_num = ?";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array(
        $new_name,$ped_tra,$reng_num
      ));

      $valor_anterior = array(
        'ped_tra'=>$ped_tra,
        'valor'=>$ant['archivo']//$estado_actual['id_status']
      );

      $valor_nuevo = array(
        'ped_tra'=>$ped_tra,
        'fecha'=>$fecha,
        'valor'=>$new_name
      );

      $log = "VALUES(325, 8017, 'docto_ped_doctos_respaldo', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
      $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
      $q = $pdo->prepare($sql);
      $q->execute(array());
      $yes = array('msg'=>'OK','valor_nuevo'=>$nombre);
      //echo json_encode($yes);
      $pdo->commit();

      $destinatarios = 'helen.cuyan@saas.gob.gt; tania.marroquin@saas.gob.gt';
      $subject = 'Documento de Respaldo '.$ped_info['Ped_num'];
      $body = 'Buen día, Sr. (a) <br><br> Por este medio le informo que se adjuntó documento de respaldo al pedido No. <strong>'.$ped_info['Ped_num'].'</strong> para ser revisado';
      $body.='<br>Siendo las: '.date('H:i:s').' del '.date('d-m-Y');
      $body.='<br><br>Favor revisarlo';

      $body.='<br><br><br>Correo enviado desde SAAS APP - Módulo control de Pedidos y Remesas';
      //echo $body;
      /*$insumos =*/ documento::enviar_correo_estado("'".$destinatarios."'", "'".$subject."'", "'".$body."'");
    }



  }catch (PDOException $e){

    $yes = array('msg'=>'ERROR','id'=>$e);
    //echo json_encode($yes);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','id'=>$e2);
    }
  }

  echo json_encode($yes);

  Database::disconnect_sqlsrv();

}else
{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}


?>
