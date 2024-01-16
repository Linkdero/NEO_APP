<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $opcion = $_POST["opcion"];
    $data = array();
    $response = array();
    if($opcion == "1"){
        $rows = empleado::get_plazas_estado();
        foreach ($rows as $row){
            if($row["id_direccion_presupuestaria"] != ""){
                if(array_key_exists($row["id_direccion_presupuestaria"], $data) && array_key_exists($row["estado_plaza"], $data[$row["id_direccion_presupuestaria"]])){
                    $data[$row["id_direccion_presupuestaria"]][$row["estado_plaza"]] = $data[$row["id_direccion_presupuestaria"]][$row["estado_plaza"]] + floatval($row["monto_sueldo_plaza"]);
                }else{
                    $data[$row["id_direccion_presupuestaria"]][$row["estado_plaza"]] = floatval($row["monto_sueldo_plaza"]);
                }
            }
        }
        foreach($data as $key => $value){
            $nombre = empleado::get_direcciones_saas_by_id($key);
            $response[] = array(
                "direccion" => $nombre["descripcion"],
                "siglas" => $nombre["descripcion_corta"],
                "ocupada" => $value["OCUPADA"],
                "vacante" => $value["VACANTE"],
                "total" => $value["OCUPADA"] + $value["VACANTE"]
            );
        }


    }else if($opcion == "2"){
/*         $fecha = $_POST["siglas"];
        $rows = visita::get_data_by_date($fecha);
        foreach ($rows as $row){
            $data[] = array(
                "fecha" => $row["fecha"],
                "value" => $row["visitas"],
            );
        } */
    }
    echo json_encode($response);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
