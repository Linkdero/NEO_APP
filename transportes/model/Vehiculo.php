<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
class Vehiculo {

  public $vehiculo;
  public $opcion = 1;

  protected function __construct() {
      $this->vehiculo = array();
  }

  //opcion 1
  static function getVehiculoById(){
    $id_vehiculo = $_GET['id'];
    
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_vehiculo,id_propietario,propietario,nro_placa,chasis,motor,
                   modelo,flag_franjas_de_color,detalle_franjas,observaciones,capacidad_tanque,kilometros_x_galon,
                   id_tipo_combustible,nombre_tipo_combustible,poliza_seguro,id_empresa_seguros,nombre_empresa_seguros,
                   id_linea,nombre_linea,id_color,nombre_color,id_marca,nombre_marca,id_tipo,nombre_tipo,id_uso,nombre_uso,
                   id_status,nombre_estado,id_servicio,km_servicio_proyectado,km_actual,km_ultimo_servicio,id_tipo_asignacion,
                   nombre_tipo_asignacion,id_giras,id_persona_asignado,nombre_persona_asignado,id_direccion,nombre_direccion,
                   id_persona_autoriza,nombre_persona_autoriza,codigo_interno,flag_devuelto
             FROM xxx_dayf_vehiculos
             WHERE id_vehiculo = ?";
    $p = $pdo->prepare($sql);
    $p->execute(array($id_vehiculo));
    $response = $p->fetch();
    Database::disconnect_sqlsrv();
    $data = array();
    $data = array(
      'DT_RowId'=>$response['id_vehiculo'],
      'id_vehiculo'=>$response['id_vehiculo'],
      'capacidadTanque'=>$response['capacidad_tanque'],
      'kmPorGalon'=>$response['kilometros_x_galon'],
    );

    echo json_encode($data);
  }

}

if (isset($_POST['opcion']) || isset($_GET['opcion'])) {

  $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];
  switch ($opcion) {
    case 1:
      Vehiculo::getVehiculoById();
    break;

  }
}
?>
