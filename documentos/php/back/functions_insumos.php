<?php
include_once '../../../inc/functions.php';
class Insumo{

  static function getInsumosList(){
    //inicio
    //echo $filtro;
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $filtro = intval($_POST['filtro']);
    //echo $row;
    $rowperpage = $_POST['length']; // Rows display per page
    //$columnIndex = $_POST['order'][0]['column']; // Column index
    //$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    //$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.Ppr_id, a.Ppr_onu, a.Ppr_cod, a.Ppr_codPre, a.Ppr_Nom, a.Ppr_Des, a.Ppr_Pres,a.Med_id,a.Ppr_Ren,a.Ppr_est,
                    b.Med_nom
             FROM APP_POS.dbo.PPR a
             INNER JOIN APP_POS.dbo.MEDIDA b ON a.Med_id=b.Med_id
          ";


          $searchQuery = " ";
          if($searchValue != '' && strlen($searchValue) >= 4 ){
             $searchQuery = " WHERE ( a.Ppr_cod  LIKE '%".$searchValue."%'
                              OR a.Ppr_Nom LIKE '%".$searchValue."%'
                             ) ";
                              /*OR a.bien_monto LIKE '%".$searchValue."%'
                              OR a.bien_fecha_adquisicion LIKE '%".$searchValue."%'
                              OR a.bien_renglon_id LIKE '%".$searchValue."%' ) ";*/

              $searchQuery .= " AND a.Ppr_est = $filtro";

          }else{
            $sql.= " WHERE a.Ppr_est = $filtro  ";
            $sql.="ORDER BY Ppr_id DESC OFFSET $row ROWS FETCH NEXT $rowperpage ROWS ONLY OPTION (RECOMPILE)";
          }

          $sql.= $searchQuery;



    $p = $pdo->prepare($sql);
    $p->execute(array());
    $response = $p->fetchAll(PDO::FETCH_ASSOC);

    ## Total number of record with filtering
    $sql2="SELECT COUNT(*) AS total
             FROM APP_POS.dbo.PPR a
             INNER JOIN APP_POS.dbo.MEDIDA b ON a.Med_id=b.Med_id
           $searchQuery";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array());
    $recordsF = $q2->fetch();
    $totalRecordwithFilter  = $recordsF['total'];


    $totalRecords = 0;
    $sql1="SELECT COUNT(*) AS total
             FROM APP_POS.dbo.PPR a
             INNER JOIN APP_POS.dbo.MEDIDA b ON a.Med_id=b.Med_id
             WHERE a.Ppr_est = $filtro  ";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array());
    $records = $q1->fetch();
    $totalRecords = $records['total'];

    Database::disconnect_sqlsrv();
    $data = array();
    foreach ($response as $key => $i) {
      $menu = '';
      $menu .= '<a id="actions1Invoker2" class=" btn btn-soft-info btn-sm" data-toggle="modal" data-target="#modal-remoto" href="documentos/php/front/insumos/insumo_formulario.php?ppr_id='.$i['Ppr_id'].'&tipo=2"><i class="far fa-file"></i></a></div>';

      $sub_array = array(
        //'DT_RowId'=>$b['bien_id'],
        'Ppr_id'=>$i['Ppr_id'],
        'Ppr_onu'=>$i['Ppr_onu'],
        'Ppr_cod'=>$i['Ppr_cod'],
        //'bien_nombre'=>$b['bien_nombre'],
        'Ppr_codPre'=>$i['Ppr_codPre'],
        'Ppr_Nom'=>$i['Ppr_Nom'],
        //'bien_status'=>$b['bien_status'],
        'Ppr_Des'=>$i['Ppr_Des'],
        'Ppr_Pres'=>$i['Ppr_Pres'],
        'Med_id'=>$i['Med_id'],
        'Ppr_Ren'=>$i['Ppr_Ren'],
        //'bien_ubicacion_id'=>$b['bien_ubicacion_id'],
        //'bien_tipo_id'=>$b['bien_tipo_id'],
        'Ppr_est'=>$i['Ppr_est'],
        'Med_nom'=>$i['Med_nom'],

        //'arreglo'=>$arreglo,
        'accion'=>$menu,/*'<div class="btn-group"><span class="btn btn-sm btn-info" onclick="imprimirCertificacion('.$b['bien_id'].')"><i class="fa fa-print"></i></span>
        <span class="btn btn-sm btn-info" onclick="imprimirSticker('.$b['bien_id'].')"><i class="fa fa-print"></i></span>
        </div>'*/
      );
      $data[] = $sub_array;
    }
    $results = array(
      "draw" => intval($draw),
      //"sEcho" => 1,
      "iTotalRecords" => $totalRecords,//count($data),
      "iTotalDisplayRecords" => $totalRecordwithFilter,//count($data),
      "aaData"=>$data
    );

    echo json_encode($results);
    //fin
  }

  static function getMedidasList(){
    //inicio
    $tipo_response = (!empty($_POST['tipo_response'])) ? $_POST['tipo_response'] : NULL;
    $pdo = Database::connect_sqlsrv();
    $sql2="SELECT Med_id, Med_nom, Med_est
          FROM APP_POS.dbo.MEDIDA
           WHERE Med_est = ?";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array(1));
    $medidas = $q2->fetchAll();

    Database::disconnect_sqlsrv();

    $data = array();

    foreach ($medidas as $key => $m) {
      // code...
      $sub_array = array(
        'id_item'=>$m['Med_id'],
        'item_string'=>$m['Med_nom'],
        'estado'=>($m['Med_est'] == 1) ? true : false,
        'accion'=>''
      );
      $data[] = $sub_array;
    }

    if($tipo_response == 1){
      $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
      );

      echo json_encode($results);
    }else{
      echo json_encode($data);
    }
    //fin
  }

  static function saveInsumo(){
    $opcion = $_POST['opcion'];
    $tipo = $_POST['tipo'];
    $Ppr_id = (!empty($_POST['Ppr_id'])) ? $_POST['Ppr_id'] : NULL;
    $ppr_cod = (!empty($_POST['ppr_cod'])) ? $_POST['ppr_cod'] : NULL;
    $ppr_nom = (!empty($_POST['ppr_nom'])) ? $_POST['ppr_nom'] : NULL;
    $cmb_medida_id = (!empty($_POST['cmb_medida_id'])) ? $_POST['cmb_medida_id'] : NULL;
    $ppr_cod_presentacion = (!empty($_POST['ppr_cod_presentacion'])) ? $_POST['ppr_cod_presentacion'] : NULL;
    $ppr_presentacion = (!empty($_POST['ppr_presentacion'])) ? $_POST['ppr_presentacion'] : NULL;
    $ppr_descripcion = (!empty($_POST['ppr_descripcion'])) ? $_POST['ppr_descripcion'] : NULL;
    $id_renglon = (!empty($_POST['id_renglon'])) ? $_POST['id_renglon'] : NULL;
    $Ppr_estado = (!empty($_POST['Ppr_estado'])) ? $_POST['Ppr_estado'] : NULL;

    $Ppr_estado = ($Ppr_estado == 1) ? true : false;
    $yes = '';
    $pdo = Database::connect_sqlsrv();
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $orden = 1;
      if($tipo == 1){
        //create
        $sql0 = "INSERT INTO APP_POS.dbo.PPR (Ppr_cod,Ppr_codPre,Ppr_Nom,Ppr_Des,Ppr_Pres,Med_id,Ppr_Ren,Ppr_est) VALUES (?,?,?,?,?,?,?,?)";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array(
          $ppr_cod,$ppr_cod_presentacion,$ppr_nom,$ppr_descripcion,$ppr_presentacion,$cmb_medida_id,$id_renglon,$Ppr_estado
        ));

        $yes = array('msg'=>'OK','valor_nuevo'=>'','message'=>'Insumo agregado.');

      }else if($tipo == 2){
        //update
        $sql0 = "UPDATE APP_POS.dbo.PPR SET Ppr_cod = ?,Ppr_codPre = ?,Ppr_Nom = ?,Ppr_Des = ?,Ppr_Pres = ?,Med_id = ?,Ppr_Ren = ?,Ppr_est = ? WHERE Ppr_id = ?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array(
          $ppr_cod,$ppr_cod_presentacion,$ppr_nom
          ,$ppr_descripcion,$ppr_presentacion,$cmb_medida_id,$id_renglon,$Ppr_estado,$Ppr_id
        ));

        $yes = array('msg'=>'OK','valor_nuevo'=>'','message'=>'Insumo actualizado.');
      }

      //echo json_encode($yes);
      $pdo->commit();
    }catch (PDOException $e){

      $yes = array('msg'=>'ERROR','message'=>$e);
      //echo json_encode($yes);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','message'=>$e2);
      }
    }

    Database::disconnect_sqlsrv();
    echo json_encode($yes);

  }

  static function saveMedida(){

    $opcion = $_POST['opcion'];
    $tipo = $_POST['tipo'];
    $med_id = (!empty($_POST['med_id'])) ? $_POST['med_id'] : NULL;
    $med_nom = (!empty($_POST['med_nom'])) ? $_POST['med_nom'] : NULL;
    $med_estado = (!empty($_POST['med_estado'])) ? $_POST['med_estado'] : NULL;

    $me_estado = ($med_estado == 1) ? true : false;
    $yes = '';
    $pdo = Database::connect_sqlsrv();
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $orden = 1;
      if($tipo == 1){
        //create

        $sql0 = "SELECT TOP 1 Med_id, REPLACE(STR(Med_id + 1, 5), SPACE(1), '0')  AS siguiente FROM MEDIDA ORDER BY Med_id DESC";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array());

        $med = $q0->fetch();

        $sql1 = "INSERT INTO APP_POS.dbo.MEDIDA (Med_id, Med_nom, Med_est) VALUES (?,?,?)";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array(
          $med['siguiente'],$med_nom,$me_estado
        ));

        $yes = array('msg'=>'OK','valor_nuevo'=>'','message'=>'Medida agregada.');

      }else if($tipo == 2){
        //update
        $sql0 = "UPDATE APP_POS.dbo.MEDIDA SET Med_nom = ?, Med_est = ? WHERE Med_id = ?";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array(
          $med_nom,$me_estado,$med_id
        ));

        $yes = array('msg'=>'OK','valor_nuevo'=>'','message'=>'Medida actualizada.');
      }

      //echo json_encode($yes);
      $pdo->commit();
    }catch (PDOException $e){

      $yes = array('msg'=>'ERROR','message'=>$e);
      //echo json_encode($yes);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','message'=>$e2);
      }
    }

    Database::disconnect_sqlsrv();
    echo json_encode($yes);

  }

  static function getMedidaById(){
    //inicio
    $med_id = $_GET['med_id'];
    $pdo = Database::connect_sqlsrv();
    $sql2="SELECT Med_id, Med_nom, Med_est
          FROM APP_POS.dbo.MEDIDA
           WHERE Med_id = ?";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array($med_id));
    $m = $q2->fetch();

    Database::disconnect_sqlsrv();

    $data = array();

    $data = array(
      'Med_id'=>$m['Med_id'],
      'Med_nom'=>$m['Med_nom'],
      'Med_est'=>($m['Med_est'] == 1) ? true : false,
      'accion'=>''
    );

    echo json_encode($data);
    //fin
  }

  static function getTotalInsumosPorCuatrimestre(){
    $cuatrimestre = $_POST['id_cuatrimestre'];
    $year = $_POST['id_year_c'];

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.Ppr_id, a.Ppr_onu, a.Ppr_cod, a.Ppr_codPre, a.Ppr_Nom, a.Ppr_Des, a.Ppr_Pres,a.Med_id,a.Ppr_Ren,a.Ppr_est,
                    b.Med_nom, c.cuatrimestre, c.gastado
             FROM APP_POS.dbo.PPR a
             INNER JOIN APP_POS.dbo.MEDIDA b ON a.Med_id=b.Med_id
      			 INNER JOIN (
        			 SELECT  a.Ppr_id, SUM(ISNULL(a.Ppr_costo,0) * a.Ppr_can) AS gastado, ((datepart(month,f.factura_fecha)-1)/4)+1 AS cuatrimestre
                     FROM
        			 docto_ped_pago_detalle a

        			 INNER JOIN APP_POS.dbo.PPR b ON a.Ppr_id = b.Ppr_id
        			 LEFT JOIN docto_ped_pago f ON a.orden_compra_id = f.orden_compra_id
        			 LEFT JOIN docto_ped_pago_presupuesto g  ON f.id_pago = g.id_pago
                     WHERE --a.Ppr_id = ?-- AND g.nro_registro = 479
        			 --AND
        			 ((datepart(month,f.factura_fecha)-1)/4)+1 = ? --((datepart(month,GETDATE())-1)/4)+1
        			 AND ISNULL(f.ped_nog,'') = ''
        				AND YEAR(f.factura_fecha) = ? --AND g.id_year = ?
                AND CONVERT(VARCHAR(3),ISNULL(b.Ppr_Ren,0)) = '211'
        			 GROUP BY a.Ppr_id, ((datepart(month,f.factura_fecha)-1)/4)+1
           ) AS c ON c.Ppr_id = a.Ppr_id
           WHERE CONVERT(VARCHAR(3),ISNULL(a.Ppr_Ren,0)) = '211'
           ORDER BY c.gastado DESC
           ";

            //$sql0.="GROUP BY nro_orden, clase_proceso, year, cur, cur_devengado";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($cuatrimestre,$year));
    $facturas = $q0->fetchAll();
    Database::disconnect_sqlsrv();

    $data = array();
    foreach ($facturas as $key => $f) {
      // code...
      $sub_array = array(
        'DT_RowId'=>$f['Ppr_id'],
        'Ppr_id'=>$f['Ppr_id'],
        'Ppr_cod'=>$f['Ppr_cod'],
        'Ppr_codPre'=>$f['Ppr_codPre'],
        'Ppr_Nom'=>$f['Ppr_Nom'],
        'Ppr_Des'=>$f['Ppr_Des'],
        'Ppr_Pres'=>$f['Ppr_Pres'],
        'Med_id'=>$f['Med_id'],
        'Ppr_Ren'=>$f['Ppr_Ren'],
        'Ppr_est'=>$f['Ppr_est'],
        'Med_nom'=>$f['Med_nom'],
        'cuatrimestre'=>$f['cuatrimestre'],
        'year'=>$year,
        'gastado'=>number_format($f['gastado'],2,'.',','),
        'accion'=>''
      );

      $data[] = $sub_array;
    }

    $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData"=>$data
    );
    echo json_encode($results);
  }
}

if (isset($_POST['opcion']) || isset($_GET['opcion'])) {

  $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];
  switch ($opcion) {
    case 0:
      Insumo::getPrivilegios(1);
    break;
    case 1:
      Insumo::getInsumosList();
    break;
    case 2:
      Insumo::getMedidasList();
    break;
    case 3:
      Insumo::saveInsumo();
    break;
    case 4:
      Insumo::saveMedida();
    break;
    case 5:
      Insumo::getMedidaById();
    break;
    case 6:
      Insumo::getTotalInsumosPorCuatrimestre();
    break;
  }
}

?>
