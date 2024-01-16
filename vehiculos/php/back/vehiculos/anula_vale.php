<?php

include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true){
  date_default_timezone_set('America/Guatemala');
  include_once '../functions.php';
  
        $nro_vale = $_POST['nro_vale'];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // obtener numero de inventario
        $sql1 = "SELECT inv_id_doc_insumo from dayf_combustibles WHERE nro_vale = ?";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array($nro_vale));
        $nro_inventa = $q1->fetch();

        $sql2 = "UPDATE dayf_combustibles set id_estado=1149 where nro_vale = ? ";
        $q2 = $pdo->prepare($sql2);
        if($q2->execute(array($nro_vale))){

            $sql3 = "UPDATE inv_movimiento_encabezado set id_status=0 where id_doc_insumo = ? ";
            $q3 = $pdo->prepare($sql3);
            if($q3->execute(array($nro_inventa['inv_id_doc_insumo']))){
                $response = array(
                    "status" => true,
                    "msg" => "Ok"
                );
            }else{
                $response = array(
                    "status" => false,
                    "msg" => "Error"
                );
            }
        }else{
            $response = array(
                "status" => false,
                "msg" => "Error"
            );
        }
        echo json_encode($response);
        Database::disconnect_sqlsrv();
    
}else{
    echo "<script type='text/javascript'> window.location='principal'; </script>";
}
  
?>
  