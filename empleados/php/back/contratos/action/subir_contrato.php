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
  $id_persona = $_POST['id_persona'];
  $rengnumero = $_POST['reng_num'];
  $id_tipo = $_POST['id_tipo'];
  $file = $_FILES["id_contrato_pdf"];
  $nombre = $file["name"];
  $tipo = $file["type"];
  $ruta_provisional = $file["tmp_name"];
  $size = $file["size"];
  $sizefile = $_POST['sizefile'];
  /*$dimensiones = getimagesize($ruta_provisional);
  $width = $dimensiones[0];
  $height = $dimensiones[1];*/
  $carpeta = "../../../front/contratos/files/";

  $yes='';
  $pdo = Database::connect_sqlsrv();
  $src = $carpeta.$nombre;
  $sizemb = round($sizefile/1024/1024,1);

  $fecha = date('Y-m-d H:i:s');
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "SELECT TOP 1 reng_num, correlativo, archivo FROM rrhh_empleado_contrato_detalle WHERE reng_num = ?
            ORDER BY correlativo DESC";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(
      $rengnumero
    ));
    $ant = $q0->fetch();

    $reng_num = 1;
    if(!empty($ant['correlativo'])){
      $reng_num += $ant['correlativo'];
    }

    /*$url = "https://report.feel.com.gt/ingfacereport/ingfacereport_documento?uuid=6483DE59-B517-44DF-B568-BB4C4D967CAA";
    file_put_contents($carpeta."/sample.pdf",file_get_contents($url));*/

    clearstatcache();
    move_uploaded_file($ruta_provisional, $src);
    //echo $nombre;

    /*if($sizemb <= 1){
      //sleep(0);
    }else if($sizemb > 1 && $sizemb <= 2){
      //echo '2';
      sleep(2);
    }else if($sizemb > 2 && $sizemb <= 3){
      //echo '4';
      sleep(4);
    }else if($sizemb > 3 && $sizemb <= 4){
      //echo '6';
      sleep(6);
    }else if($sizemb > 4 && $sizemb <= 5){
      //echo '8';
      sleep(8);
    }else if($sizemb > 5){
      //echo '10';
      sleep(10);
    }*/
    if (file_exists($src)) {
      //echo 'existe';
      $sql2 = "INSERT INTO rrhh_empleado_contrato_detalle (reng_num, correlativo, id_status, archivo, subido_por, subido_en,descripcion, tipo_documento)
              VALUES(?,?,?,?,?,?,?,?)";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array(
        $rengnumero,$reng_num, 0,$nombre, $_SESSION['id_persona'],$fecha,'Contrato subido',$id_tipo
      ));
      $new_name = $rengnumero.'_'.$reng_num.'_'.$id_tipo.'.pdf';
      rename($src, $carpeta.$new_name);

      $sql2 = "UPDATE rrhh_empleado_contrato_detalle SET archivo = ?
            WHERE reng_num = ? AND correlativo = ?";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array(
        $new_name,$rengnumero,$reng_num
      ));
      $valor_anterior = array(
        'id_contrato'=>$rengnumero,
        'valor'=>$ant['archivo'],//$estado_actual['id_status']
        'tipo'=>''
      );

      $valor_nuevo = array(
        'id_contrato'=>$rengnumero,
        'fecha'=>$fecha,
        'valor'=>$new_name,
        'tipo'=>$id_tipo
      );

      $log = "VALUES(167, 1163, 'rrhh_empleado_contrato_detalle', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
      $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
      $q = $pdo->prepare($sql);
      $q->execute(array());
      $yes = array('msg'=>'OK','id'=>$nombre);
    }else{
      $yes = array('msg'=>'ERROR','id'=>'No existe');
    }


    //$new_name = $ped_tra.'-'.$reng_num.'.pdf';
    //rename($src, $carpeta.$new_name);

    /*$sql2 = "UPDATE docto_ped_doctos_respaldo SET archivo = ?
            WHERE ped_tra = ? AND reng_num = ?";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array(
      $new_name,$ped_tra,$reng_num
    ));


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
    /*$insumos =documento::enviar_correo_estado("'".$destinatarios."'", "'".$subject."'", "'".$body."'");*/

    $pdo->commit();

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
