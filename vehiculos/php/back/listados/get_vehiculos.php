<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once './../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

class Vehiculos
{
  static function getAllVehiculos()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_vehiculo
          ,id_status
          ,nombre_estado
          ,nro_placa
          ,chasis
          ,motor
          ,nombre_tipo
          ,nombre_marca
          ,nombre_linea
          ,nombre_color
          ,detalle_franjas
          ,modelo
          ,observaciones
          ,capacidad_tanque
          ,nombre_tipo_combustible
          ,km_actual
          ,propietario
          ,nombre_persona_asignado
      FROM xxx_dayf_vehiculos";
    $p = $pdo->prepare($sql);
    $p->execute();
    $vehiculos = $p->fetchAll();
    $data = array();
    $estado = '';

    $accion = '
    <a id="actions1Invoker1" class="btn btn-sm btn-soft-info" href="#!" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
    <i class="fa fa-check-circle"></i> ';

    $accion2 = ' </i></a>
  <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker1" style="margin-right:20px">
    <div class="card overflow-hidden" style="margin-top:-20px;">
      <div class="card-header d-flex align-items-center py-3">
        <h2 class="h4 card-header-title">Opciones:
        </h2>
      </div>
      <div class="card-body animacion_right_to_left" style="padding: 0rem;">
        <div
          <ul class="list-unstyled mb-0">';
    $titulos = array("Detalle", "Editar");
    $iconos = array("fa fa-address-book", "fa fa-pencil");
    foreach ($vehiculos as $v) {
      $detalle = "";
      $x = 0;
      $funsiones = array('onclick="detalleVehiculo(' . $v["id_vehiculo"] . ')', 'onclick="formularioVehiculo(2, ' . $v["id_vehiculo"] . ')');
      if ($v["id_status"] == 1011 || $v["id_status"] == 8033 || $v["id_status"] == 8035 || $v["id_status"] == 8036) {
        $estado = '<span class="badge badge-danger">';
      } else if ($v["id_status"] == 1010 || $v["id_status"] == 8034) {
        $estado = '<span class="badge badge-success">';
      } else {
        $estado = '<span class="badge badge-warning">';
      }

      while ($x <= 1):
        $detalle .= '      
        <li class="mb-1">
          <a class="d-flex align-items-center link-muted py-2 px-3" ' . $funsiones[$x] . '">
            <i class=" ' . $iconos[$x] . ' mr-2"></i>   ' . $titulos[$x] . '
          </a>
        </li>';
        $x += 1;
      endwhile;

      $idVehiculo = $accion . $v["id_vehiculo"] . $accion2 . $detalle;
      $sub_array = array(
        'id_vehiculo' => $idVehiculo,
        'nombre_estado' => $estado . $v['nombre_estado'] . '</span>',
        'nro_placa' => $v['nro_placa'],
        'chasis' => $v['chasis'],
        'motor' => $v['motor'],
        'nombre_tipo' => $v['nombre_tipo'],
        'nombre_marca' => $v['nombre_marca'],
        'nombre_linea' => $v['nombre_linea'],
        'nombre_color' => $v['nombre_color'],
        'detalle_franjas' => $v['detalle_franjas'],
        'modelo' => $v['modelo'],
        'observaciones' => '<div style="height: 50px; width: 220px; overflow-y: scroll;">' . $v['observaciones'] . '</div>',
        'capacidad_tanque' => number_format($v['capacidad_tanque'], 2, '.', ','),
        'nombre_tipo_combustible' => $v['nombre_tipo_combustible'],
        'km_actual' => $v['km_actual'],
        'propietario' => $v['propietario'],
        'nombre_persona_asignado' => $v['nombre_persona_asignado']
      );
      $data[] = $sub_array;
    }

    $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData" => $data
    );

    echo json_encode($results);
  }
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
  $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

  switch ($opcion) {
    case 1:
      Vehiculos::getAllVehiculos();
      break;
  }
}