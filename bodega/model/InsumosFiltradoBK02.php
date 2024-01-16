<?php
include_once '../../inc/functions.php';
include_once 'Requisicion.php';
$req = new Requisicion;
$privilegio = $req->getPrivilegios(2);

//sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  //include_once '../functions.php';

  $response = array();
  $filtro = $_GET['q'];
  $bodega_id = $_GET['bodega_id'];
  $queryPerfil = '';
  $perfil = $req->getPerfilBodega($bodega_id,2);

  $profile = $perfil['perfil'];
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT  a.Pro_idint, a.Ent_id, a.Pro_des, a.Med_id, c.Med_nom, b.Bod_id
          FROM APP_POS.dbo.PRODUCTO a
          INNER JOIN APP_POS.dbo.PRO_SSFAMILIA_BODEGA b ON b.ssf_id = a.ssf_id
          INNER JOIN APP_POS.dbo.MEDIDA c ON a.Med_id=c.Med_id ";
  if($bodega_id == '04'){
    $sql0 .= "INNER JOIN APP_POS.dbo.RequisicionPerfilDetalle d ON a.Pro_idint = d.ProductoId ";
    $queryPerfil .= " AND d.perfilCodigo = $profile ";
  }
  $sql0 .=" WHERE b.bod_id = ? AND a.Pro_des LIKE '%".$_GET['q']."%' ";
  $sql0 .= $queryPerfil;





          //echo $privilegio['residencias_solicita_cocina']. ' -- -- '.$privilegio['residencias_solicita_recursos'];

  if($bodega_id == '01'){
    if($privilegio['residencias_solicita_cocina'] == 1){
    }else if($privilegio['residencias_solicita_recursos'] == 1){
      $sql0 .=" AND a.Pro_idint IN (5652,5659,7853,632,5640,1433,4268,5507,
                527,531,568,2627,1236,1242,864,5875,6007,740)";
    }else if($privilegio['residencias_solicita_comitivas'] == 1){
      $sql0 .=" AND a.Pro_idint IN (4372,9468,791,4269,6994,6250,5727,864,5875,5652,765,6813,802,821,814,810,816,818,812,822,2583,820)";
    }else{
      $sql0 .=" AND a.Pro_idint IN (4372,9468,791,4269,6994,6250,5727,864,5875,5652,765,6813,802)";
    }
  }

  $sql0 .= " ORDER BY a.Pro_idint ASC";

  //if($e['id_de'])
  /*SELECT a.Pro_idint, a.Ent_id, a.Pro_des, a.Med_id, b.Med_nom, c.Bod_id
   FROM APP_POS.dbo.PRODUCTO a
   INNER JOIN APP_POS.dbo.MEDIDA b ON a.Med_id=b.Med_id
   INNER JOIN APP_POS.dbo.PRODUCTO_DETALLE c ON c.Pro_idint = a.Pro_idint
   INNER JOIN APP_POS.dbo.PRO_FAMILIA_BODEGA d ON a.fam_id = d.fam_id
   WHERE d.bod_id = ? AND a.Pro_des LIKE '%".$_GET['q']."%' ORDER BY a.Pro_idint ASC*/
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array($bodega_id));
  $insumos = $q0->fetchAll();

  $data = array();

  foreach($insumos as $i){
    $sub_array = array(
      'Pro_idint'=>$i['Pro_idint'],
      //'Pedd_can'=>number_format($i['Pedd_can'],0,'',''),
      'Ent_id'=>$i['Ent_id'],
      'Pro_des'=>$i['Pro_des'],
      'Med_id'=>$i['Med_id'],
      'Med_nom'=>$i['Med_nom'],
      'Bod_id'=>$i['Bod_id'],

    );
    //$data[] = $sub_array;

     $data[] = ['id'=>$i['Pro_idint'], 'text'=>$i['Pro_des'].' - '.$i['Med_nom']];
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
