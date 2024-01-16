<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $row_puerta = visita::get_data_by_ip($_SERVER["REMOTE_ADDR"]);
    $visitas = array();
    $visitas = visita::get_visitas($row_puerta["id_puerta"]);

    // echo json_encode($visitas);
    $data = array();
    foreach ($visitas as $visita){
      if($visita['de_oficina']){
        if($visita['hora_sale'] == NULL){
          $salida = "No ha salido";
        }else{
          $salida = date_format(new DateTime($visita['hora_sale']), 'H:i:s');
        }
        if($visita["id_captura1"] < 0){
          $url_1 = "img/NA.png";
          $url_2 = "img/NA.png";
        }else{
          $url_1 = "img/".$visita["ruta_puerta"]."/".$visita["id_visita"]."C1.jpg";
          $url_2 = "img/".$visita["ruta_puerta"]."/".$visita["id_visita"]."C2.jpg";
        }
        $sub_array = array(
          'ID' => $visita['id_visita'],
          'oficina' => $visita['de_oficina'],
          'dependencia' => $visita['dependencia'],
          'autoriza' => $visita['autoriza'],
          'fecha' => date_format(new DateTime($visita['fecha']), 'd-m-Y'),
          'entrada' => date_format(new DateTime($visita['hora_entra']), 'H:i:s'),
          'salida' => $salida,
          'puerta' => $visita['nombre_puerta'],
          'gafete' => $visita['no_gafete'],
          'img' => "<button type='button' onclick='drawImg(this.value);' value=".$url_1." class='btn btn-info'>Foto</button>",
        );
        $data[] = $sub_array;
      }
      
    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data
  );

  echo json_encode($results);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
