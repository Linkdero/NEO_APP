<?php
include '../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    date_default_timezone_set('America/Guatemala');
    include_once '../../../quinta/php/back/functions.php';
    $opcion = $_POST["opcion"];
    $ini_=$_POST['ini'];
    $fin_=$_POST['fin'];
    $ini=date('Y-m-d', strtotime($ini_));
    $fin=date('Y-m-d', strtotime($fin_));
    $puerta=$_POST['puerta'];
    $oficina=$_POST['oficina'];
    $no_salido=$_POST['no_salido'];

    $data = array();
    if($opcion == "1"){
        $rows = visita::get_data_by_doors($ini,$fin,$oficina,$puerta,$no_salido);
        foreach ($rows as $row){
            $data[] = array(
                "puerta" => $row["puerta"],
                "siglas" => $row["siglas"],
                "visitas" => $row["visitas"],
            );
        }
    }else if($opcion == "2"){
        $fecha = $_POST["siglas"];
        $rows = visita::get_data_by_date($fecha,$ini,$fin);
        foreach ($rows as $row){
            $data[] = array(
                "fecha" => $row["fecha"],
                "value" => $row["visitas"],
            );
        }
    }
    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
