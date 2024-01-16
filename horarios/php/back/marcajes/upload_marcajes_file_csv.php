<?php
include_once '../../../../inc/functions.php';
  use Shuchkin\SimpleXLSX;
date_default_timezone_set('America/Guatemala');
  //if(file_exists($new_name)){


    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', true);
    set_time_limit(0);

    require_once '../../../../inc/excel/SimpleXLSX.php';
    $data = array();
    if (isset($_FILES['archivo'])) {
        if ($xlsx = SimpleXLSX::parse($_FILES['archivo']['tmp_name'])) {
            /*echo '<h2>Parsing Result</h2>';
            echo '<table border="1" cellpadding="3" style="border-collapse: collapse">';*/

            $dim = $xlsx->dimension();
            $cols = $dim[0];

            $yes='';
            $pdo = Database::connect_sqlsrv();
            try{
              $pdo->beginTransaction();
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              //inicio
              foreach ($xlsx->readRows() as $k => $r) {
                if($k > 8){
                  $punto = '';
                  $ip = '';
                  if($r[8] == "Biométrico de Ingreso"){
                    $punto = 4;
                    $ip = '172.16.30.50';
                  }else if($r[8] == "Biométrico de Egreso"){
                    $punto = 5;
                    $ip = '172.16.30.51';
                  }else if($r[8] == "Biométrico Recursos Humanos"){
                    $punto = 6;
                    $ip = '172.16.30.52';
                  }
                  /*$sub_array  = array(
                    'id_persona'=>$r[1],
                    'hora'=>$r[7],
                    'temperatura'=>$r['2'],
                    'resultado'=>$r[9],
                    'punto'=>$punto
                  );
                  $data[] = $sub_array;*/
                  $temp = (!empty($r[2])) ? $r[2] : 0;
                  if(is_numeric($r[1])){
                    $sql0 = "IF NOT EXISTS (SELECT * FROM tbl_marcaje_facial
                               WHERE id_persona = ?  AND hora = ? AND punto = ?)
                             BEGIN
                                 INSERT INTO tbl_marcaje_facial (id_persona, hora, punto, temperatura, resultado,ip_address)
                                 VALUES (?,?,?,?,?,?);

                                 INSERT INTO tbl_control_ingreso(
                                  id_persona,
                              		fecha,
                              		id_punto,
                              		ip_address,
                              		id_direccion,
                              		id_puesto
                                  )
                                  SELECT
                                  ?,
                                  ?,
                                  ?,
                                  ?,
                              		f.id_dirf,
                              		f.id_pfuncional
                                  FROM xxx_rrhh_Ficha f
                                  WHERE id_persona = ?
                             END";
                    $q0 = $pdo->prepare($sql0);
                    $q0->execute(array(
                      $r[1],$r[7],$punto,$r[1],$r[7],$punto,$temp,$r[9],$ip,
                      $r[1],$r[7],$punto,$ip,$r[1]
                    ));
                  }

                }


                //if(!empty($r[1])){
                //}
              }

              //echo json_encode($data);

              //fin
              $yes = array('msg'=>'OK','id'=>'');
              $pdo->commit();
            }catch (PDOException $e){

              $yes = array('msg'=>'ERROR','id'=>$e);
              //echo json_encode($yes);
              try{ $pdo->rollBack();}catch(Exception $e2){
                $yes = array('msg'=>'ERROR','id'=>$e2);

              }
              Database::disconnect_sqlsrv();
            }




        } else {
            $yes = array('msg'=> SimpleXLSX::parseError(),'id'=>'');
        }
    }else{
      $yes = array('msg'=>'Documento no encontrado.','id'=>'');
    }

    echo json_encode($yes);

  //}






     //cerramos bucle

?>
