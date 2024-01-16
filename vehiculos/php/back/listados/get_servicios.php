<?php
include_once '../../../../inc/functions.php';
sec_session_start();
include_once '../functions.php';
date_default_timezone_set('America/Guatemala');

$array = evaluar_flags_by_sistema($_SESSION['id_persona'], 1162);
$permisos1 = $array[1]['flag_insertar'] == 1 ? 1 : 0;
$permisos2 = ($array[1]['flag_actualizar'] == 1) ? 2 : 0;
class Servicio
{
  public $servicios;
  //public $opcion = 1;
  protected function __construct()
  {
    $this->servicios = array();
  }

  static function getServicios()
  {
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT vs.id_servicio
    , [dbo].[RTF2Text](descripcion_solicitado) AS descripcion_solicitado
    ,cd.descripcion as estado
    ,nro_orden
	  ,cdd.descripcion as servicio
	  ,v.nombre_marca
	  ,v.nombre_color
	  ,v.modelo
	  ,v.nro_placa
	  ,pr.descripcion as taller
    ,vs.km_actual
	  ,(p.primer_nombre + ' ' + p.primer_apellido) as nombre_recibe
	  ,(pp.primer_nombre + ' ' + pp.primer_apellido) as nombre_entrega
    ,fecha_recepcion
    ,vs.id_status
    FROM dayf_vehiculo_servicios as vs
    left join xxx_dayf_vehiculos as v on vs.id_vehiculo = v.id_vehiculo
    left join dayf_proveedores as pr on vs.id_taller = pr.id_proveedor
    left join rrhh_persona as p on vs.recepcion_id_persona_recibe = p.id_persona
    left join rrhh_persona as pp on vs.recepcion_id_persona_entrega = pp.id_persona
    inner join tbl_catalogo_detalle as cd on vs.id_status = cd.id_item
    inner join tbl_catalogo_detalle as cdd on vs.id_tipo_servicio = cdd.id_item
    where vs.id_status = ? and fecha_recepcion > '01-01-2023'
    ORDER BY vs.id_servicio DESC";
    $p = $pdo->prepare($sql);
    $p->execute(array($_POST['estado']));
    $servicios = $p->fetchAll(PDO::FETCH_ASSOC);
    $data = array();
    $porcentaje = '';
    $color = "";
    $y = "";
    $z = "";

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
        <div>
          <ul class="list-unstyled mb-0">';

    $titulos = array("Detalle", "Asignar Mécanico", "Finalizar");
    $iconos = array("fa fa-address-book", "fa fa-cog", "fa fa-check-square");

    foreach ($servicios as $s) {
      $detalle = "";
      $x = 0;
      $funsiones = array('onclick="detalleServicio(' . $s["id_servicio"] . ', ' . $_POST['estado'] . ', ' . $GLOBALS['permisos1'] . ',' . $GLOBALS['permisos2'] . ')', 'onclick="asignarMecanico(' . $s["id_servicio"] . ')', 'onclick="finalizarservicio(' . $s["id_servicio"] . ')');

      if ($s["id_status"] == 5487) {
        $porcentaje = 33;
        $color = 'info';
        $y = 1;
        $z = 1;
      } else if ($s["id_status"] == 5488) {
        $porcentaje = 50;
        $color = 'info';
        $y = 2;
        $z = 2;
      } else if ($s["id_status"] == 5489) {
        $porcentaje = 100;
        $color = 'success';
        $y = 1;
        $z = 2;
      }

      if ($GLOBALS['permisos1'] == 1 && $GLOBALS['permisos2'] == 0) {
        $z = 5;
      }

      while ($x <= $y):
        $detalle .= '      
        <li class="mb-1">
          <a class="d-flex align-items-center link-muted py-2 px-3" ' . $funsiones[$x] . '">
            <i class=" ' . $iconos[$x] . ' mr-2"></i>   ' . $titulos[$x] . '
          </a>
        </li>';
        $x += $z;
      endwhile;

      $idServicio = $accion . $s["id_servicio"] . $accion2 . $detalle;

      $estado = '<div class="progress progress-striped skill-bar" style="height:6px">
      <div class="progress-bar progress-bar-striped progress-bar-animated bg-' . $color . '" role="progressbar" aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: ' . $porcentaje . '%">
      </div>
      </div>';
      if ($s["descripcion_solicitado"][1] == '>') {
        $descripcion = '<' . $s["descripcion_solicitado"];
      } else {
        $descripcion = $s["descripcion_solicitado"];
      }

      $sub_array = array(
        "id_servicio" => $idServicio,
        "estado" => $s["estado"] . $estado,
        "nro_orden" => $s["nro_orden"],
        "servicio" => $s["servicio"],
        "nombre_marca" => $s["nombre_marca"],
        "nombre_color" => $s["nombre_color"],
        "modelo" => $s["modelo"],
        "nro_placa" => $s["nro_placa"],
        "taller" => $s["taller"],
        "km_actual" => $s["km_actual"],
        "nombre_recibe" => $s["nombre_recibe"],
        "nombre_entrega" => $s["nombre_entrega"],
        "fecha_recepcion" => $s["fecha_recepcion"],
        "descripcion_solicitado" => '  <div style="height: 50px; width: 220px; overflow-y: scroll;">' . strtoupper(wordwrap($descripcion, 50, '<br />')) . '</div>',
      );
      $data[] = $sub_array;
    }

    $result = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData" => $data
    );
    echo json_encode($result);
  }
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
  $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

  switch ($opcion) {
    //Para el Datatable
    case 1:
      Servicio::getServicios();
      break;
  }
}