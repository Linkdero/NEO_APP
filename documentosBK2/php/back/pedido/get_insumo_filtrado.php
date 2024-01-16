<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $filtro = $_GET['q'];
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql0 = "SELECT a.Ppr_id, a.Ppr_onu, a.Ppr_cod, a.Ppr_codPre, a.Ppr_Nom, a.Ppr_Des, a.Ppr_Pres,a.Med_id,a.Ppr_Ren,a.Ppr_est,
                  b.Med_nom
           FROM APP_POS.dbo.PPR a
           INNER JOIN APP_POS.dbo.MEDIDA b ON a.Med_id=b.Med_id
           WHERE a.Ppr_est=? AND a.Ppr_Nom LIKE '%".$_GET['q']."%' OR a.Ppr_est=? AND a.Ppr_cod LIKE '%".$_GET['q']."%'
           ORDER BY a.Ppr_id ASC
          ";
  //if($e['id_de'])
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array(1,1));
  $insumos = $q0->fetchAll();

  $data = array();

  foreach($insumos as $i){
    $sub_array = array(
      'Ppr_id'=>$i['Ppr_id'],
      //'Pedd_can'=>number_format($i['Pedd_can'],0,'',''),
      'Ppr_cod'=>$i['Ppr_cod'],
      'Ppr_codPre'=>$i['Ppr_codPre'],
      'Ppr_Nom'=>$i['Ppr_Nom'],
      'Ppr_Des'=>$i['Ppr_Des'],
      'Ppr_Pres'=>$i['Ppr_Pres'],
      'Ppr_Ren'=>$i['Ppr_Ren'],
      'Ppr_Med'=>$i['Med_nom']

    );
    //$data[] = $sub_array;

     $data[] = ['id'=>$i['Ppr_id'], 'text'=>$i['Ppr_Ren'].' - '.$i['Ppr_cod'].' - '.$i['Ppr_Nom'].' - '.$i['Ppr_Des'].' - '.$i['Ppr_Pres'].' - '.$i['Med_nom']];
  }

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
