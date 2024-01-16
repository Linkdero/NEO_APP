<?php
include_once '../../../../inc/functions.php';
sec_session_start();

if (function_exists('verificar_session') && verificar_session() == true) {
  date_default_timezone_set('America/Guatemala');
  include_once '../../functions.php';

  $array_salones = array();
  $salon = new Salon();
  $array_salones = $salon::get_salones();
  $data = array();
  $opcion = $_REQUEST["opcion"];
  if ($opcion == 1) {
    foreach ($array_salones as $value) {
      $accion = '<div class="btn-group btn-group-sm" role="group">
                  <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mr-2" role="group" aria-label="Second group">
                      <button class="btn btn-personalizado outline btn-sm" data-toggle="modal" data-target="#modal-remoto" href="salones/php/front/salones/salon_nuevo.php?id=' . $value["id_salon"] . '">
                        <i class="fa fa-pencil-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>';
      $data[] = array(
        "nombre" => $value["nombre"],
        "capacidad" => $value["capacidad"],
        "ubicacion" => $value["ubicacion"],
        // "usuario" => $value["primer_nombre"] . " " . $value["primer_apellido"],
        // "fecha" => $value["fecha"],
        "estado" => $value["estado"],
        "accion" => $accion
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
    $response = "";
    foreach ($array_salones as $value) {
      $response .= "<option value=" . $value['id_salon'] . ">" . $value['nombre'] . "</option>";
    }
    echo $response;
  }
} else {
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
