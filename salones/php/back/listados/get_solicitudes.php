<?php
include_once '../../../../inc/functions.php';
sec_session_start();

if (function_exists('verificar_session') && verificar_session() == true) {
  date_default_timezone_set('America/Guatemala');
  include_once '../../functions.php';

  $opcion = $_POST["opcion"];
  $array_solicitudes = array();
  $salon = new Salon();

  $data = array();
  if ($opcion == 1) {
    $array_solicitudes = $salon->get_all_solicitudes();
    foreach ($array_solicitudes as $value) {
      $fini = date('d/m/Y', strtotime($value["fecha_inicio"]));
      $ffin = date('d/m/Y', strtotime($value["fecha_fin"]));
      $hini = date('h:i A', strtotime($value["fecha_inicio"]));
      $hfin = date('h:i A', strtotime($value["fecha_fin"]));

      $fec = ($fini == $ffin) ? $fini : $fini . " - " . $ffin;
      $hor = ($hini == $hfin) ? $hini : $hini . " - " . $hfin;
      $data[] = array(
        "salon" => $value["nombre"],
        "motivo" => $value["motivo"],
        "solicitante" => $value["primer_nombre"] . " " . $value["primer_apellido"],
        "inicio" => $fec,
        "fin" => $hor,
        "audiovisuales" => $value["audiovisuales"],
        "mobiliario" => $value["mobiliario"],
        "estado" => $value["estado"]
      );
    }

    $response = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData" => $data
    );

    echo json_encode($response);
  } else if ($opcion == 2) {
    $array_solicitudes = $salon->get_solicitudes();
    foreach ($array_solicitudes as $value) {

      $estado = $value['estado'];
      $color  = "";
      $badge = "";

      // echo $estado;

      switch ($estado) {
        case 0:
          $color = '#fb4143';
          $badge = '<span class="badge badge-danger">Rechazado</span>';
          break;
        case 1:
          $color = '#fab633';
          $badge = '<span class="badge badge-warning">Pendiente</span>';
          break;
        case 2:
          $color = '#0dd157';
          $badge = '<span class="badge badge-success">Aprobado</span>';
          break;
        case 3:
          $color = '#2972fa';
          $badge = '<span class="badge badge-info">Finalizado</span>';
          break;
      }


      $data[] = array(
        "title" => $value["nombre"],
        "start" => $value["date_start"],
        "end" => $value["date_end"],
        "color" => $color,
        "motivo" => $value["motivo"],
        "solicitante" => $value["primer_nombre"] . " " . $value["primer_apellido"],
        "audiovisuales" => $value["audiovisuales"],
        "mobiliario" => $value["mobiliario"],
        "estado" => $value["estado"],
        "nombre" => $value["nombre"],
        "inicio" => $value["fecha_inicio"],
        "fin" => $value["fecha_fin"],
        "badge" => $badge,
        "id_solicitud" => $value["id_solicitud"]
      );
    }
    echo json_encode($data);
  }
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
