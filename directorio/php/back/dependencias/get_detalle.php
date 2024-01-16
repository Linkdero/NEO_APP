<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';

  $id_dependencia = $_POST['id_dependencia'];
  $DIRECTORIO = new Directorio();
  $detalles = $DIRECTORIO::get_detalle_dependencia($id_dependencia);
  $data = array();
  foreach ($detalles as $detalle) {
    $numero = ($detalle['numero'] != "") ? $detalle['numero'] : null;
    $nombre = ($detalle['nombre'] != "") ? $detalle['nombre'] : null;
    $puesto = ($detalle['puesto'] != "") ? $detalle['puesto'] : null;
    $button = "<div class='btn-group' role='group'>
                      <span title='Inactivar Contacto' class='btn btn-sm btn-personalizado outline' onclick='delete_row({$detalle['id_detalle']});'>
                        <i class='fa fa-trash-alt' data-toggle='tooltip' data-placement='right'></i>
                      </span>
                    </div>";
    $data[] = array(
      "id" => $detalle['id_detalle'],
      "numero" => $detalle['numero'],
      "nombre" => $detalle['nombre'],
      "puesto" => $detalle['puesto'],
      "fecha" => fecha_dmy($detalle['fecha']),
      "accion" => $button
    );
  }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData" => $data
  );

  echo json_encode($results);
else :
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
