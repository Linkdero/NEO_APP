<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../inc/functions.php';
//include_once 'Transporte.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
class Asignacion {

  public $asignacion;
  public $opcion = 1;

  protected function __construct() {
      $this->asignacion = array();
  }

  //opcion 1
  static function getAsignacionsList(){
    //inicio
    $tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : $_GET['tipo'];
    $filtro = (!empty($_POST['filtro'])) ? $_POST['filtro'] : $_GET['filtro'];
    $year = (!empty($_POST['year'])) ? $_POST['year'] : 2023;
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.asignacion_id, a.asignado_por AS asignado_por_id,
            a.asignacion_year, a.correlativo AS correlativoa,
            --asignacion_year, a.correlativo,
            CONVERT(VARCHAR, a.asignacion_fecha, 23) AS fecha, c.vehiculo_id, c.conductor_id, c.asignacion_tipo_transporte,
            c.asignacion_status, d.id_vehiculo, g.descripcion AS marca, f.descripcion AS linea, e.nro_placa,
            ISNULL(h.primer_nombre,' ')+' '+ISNULL(h.segundo_nombre,' ')+' '+ISNULL(h.tercer_nombre,' ')+' '+
            ISNULL(h.primer_apellido,' ')+' '+ISNULL(h.segundo_apellido,' ')+' '+ISNULL(h.tercer_apellido,' ') AS conductor,
            ISNULL(i.primer_nombre,' ')+' '+ISNULL(i.segundo_nombre,' ')+' '+ISNULL(i.tercer_nombre,' ')+' '+
            ISNULL(i.primer_apellido,' ')+' '+ISNULL(i.segundo_apellido,' ')+' '+ISNULL(i.tercer_apellido,' ') AS asignado_por
            FROM
            TransporteAsignacion a
            INNER JOIN TransporteAsignacionSolicitud b ON b.asignacion_id = a.asignacion_id
            INNER JOIN TransporteAsignacionVehiculo c ON c.asignacion_id = a.asignacion_id
            INNER JOIN dayf_vehiculos d ON c.vehiculo_id = d.id_vehiculo
            LEFT JOIN dayf_vehiculo_placas e ON e.id_vehiculo = d.id_vehiculo
            LEFT JOIN dayf_vehiculo_linea f ON d.id_linea = f.id_linea
            LEFT JOIN tbl_catalogo_detalle g ON d.id_marca = g.id_item
            LEFT JOIN rrhh_persona h ON c.conductor_id = h.id_persona
            LEFT JOIN rrhh_persona i ON c.asignacion_por = i.id_persona

          ";


          $sql.=" WHERE a.asignacion_year = $year";


    $p = $pdo->prepare($sql);
    $p->execute(array());
    $response = $p->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();
    $data = array();
    foreach ($response as $key => $a) {
      $sub_array = array(
        'asignacion_id'=>$a['asignacion_id'],
        'asignado_por_id'=>$a['asignado_por_id'],
        'asignacion_year'=>$a['asignacion_year'],
        'correlativo'=>'<strong>'.$a['asignacion_year'].'-'.$a['correlativoa'].'</strong> | Fecha: '.fecha_dmy($a['fecha']),
        'fecha'=>fecha_dmy($a['fecha']),
        'vehiculo_id'=>$a['vehiculo_id'],
        'conductor_id'=>$a['conductor_id'],
        'asignacion_tipo_transporte'=>$a['asignacion_tipo_transporte'],
        'asignacion_status'=>$a['asignacion_status'],
        'id_vehiculo'=>$a['id_vehiculo'],
        'marca'=>$a['marca'],
        'linea'=>$a['linea'],
        'nro_placa'=>$a['nro_placa'],
        'conductor'=>$a['conductor'],
        'asignado_por'=>$a['asignado_por'],
        //<span class="btn btn-sm btn-soft-info" onclick="imprimirCertificacion('.$t['solicitud_id'].')"><i class="fa fa-print"></i></span>'
      );
      $data[] = $sub_array;
    }
    $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData"=>$data
    );

    if($tipo == 0){
      echo json_encode($data);
    }else{
      echo json_encode($results);
    }
    //fin
  }

  //opcion 2
  static function getAsignacionesBoard(){
    //inicio
    $tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : $_GET['tipo'];
    $filtro = (!empty($_POST['filtro'])) ? $_POST['filtro'] : $_GET['filtro'];
    $year = (!empty($_POST['year'])) ? $_POST['year'] : 2023;
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.asignacion_id, a.asignado_por AS asignado_por_id,
            a.asignacion_year, a.correlativo AS correlativoa,
            --asignacion_year, a.correlativo,
            CONVERT(VARCHAR, a.asignacion_fecha, 23) AS fecha, a.asignacion_status
            FROM TransporteAsignacion a
          ";


          $sql.=" WHERE a.asignacion_year = $year";
          if($filtro == 1){
            $sql.=" AND a.asignacion_status IN (1,2)";
          }else if($filtro == 2){
            $sql.=" AND a.asignacion_status IN (3)";
          }else if($filtro == 3){
            $sql.=" AND a.asignacion_status IN (4)";
          }


    $p = $pdo->prepare($sql);
    $p->execute(array());
    $response = $p->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();
    $data = array();
    foreach ($response as $key => $a) {

      $vehiculos = self::getVehiculosByAsignacion($a['asignacion_id'],1);
      $solicitudes = self::getSolicitudesByAsignacion($a['asignacion_id'],0);

      $cars = array();
      $requests = array();

      foreach($vehiculos AS $v){
        $sub_array = array(
          'vehiculo'=>$v['marca'].' - '.$v['linea'].' - '.$v['modelo'].' - '.$v['nro_placa'],
          'estado'=>$v['estado_asignacion'],
          'linea'=>$v['linea'],
          'marca'=>$v['marca'],
          'modelo'=>$v['modelo'],
          'nro_placa'=>$v['nro_placa'],
          'tipo_asignacion'=>$v['tipo_asignacion'],
          'asignacion_status'=>$v['asignacion_status'],
          'estado_seguimiento'=>$v['estado_seguimiento'],
          'fecha_salida'=>(!empty($v['fecha_salida'])) ? $v['fecha_salida'] : ''
        );
        $cars[] = $sub_array;
      }

      foreach($solicitudes AS $s){
        $sub_array = array(
          'direccion'=>$s['direccion'],
          'solicitud_status_p'=>$s['solicitud_status_p'],
        );
        $requests[] = $sub_array;
      }

      $estadoA = retornaEstadoAsignacion($a['asignacion_status']);
      $progressAsignacion = '';
      $progressAsignacion .= '<div class="text-left" style="margin-top:0px; width:100px; ">
                        <div style="margin-top:0px; "><span class="badge-sm text-' . $estadoA['color'] . '">
                        <i class="fa fa-'.$estadoA['icon'].'"></i> ' . $estadoA['estado'] . '</span>
                        <br><div class="progress progress-striped skill-bar " style="height:6px">
                <div class="progress-bar progress-bar-striped bg-' . $estadoA['color'] . '" role="progressbar" aria-valuenow="' . $estadoA['porcentaje'] . '" aria-valuemin="' . $estadoA['porcentaje'] . '" aria-valuemax="100" style="width: ' . $estadoA['porcentaje'] . '%">
                </div>
              </div></div></div>';

      $sub_array = array(
        'DT_RowId'=>$a['asignacion_id'],
        'asignacion_id'=>$a['asignacion_id'],
        'estado'=>$progressAsignacion,
        'asignado_por_id'=>$a['asignado_por_id'],
        'asignacion_year'=>$a['asignacion_year'],
        'correlativo'=>'A-'.$a['correlativoa'],
        'correlativo_a'=>'<h5 class="text-info"><strong>A-'.$a['correlativoa'].'</strong></h5>',
        'fecha'=>fecha_dmy($a['fecha']),
        'vehiculos'=>$cars,
        'solicitudes'=>$requests,
        'destinos'=>'',
        'accion'=>''
      );
      $data[] = $sub_array;
    }
    $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData"=>$data
    );

    if($tipo == 0){
      echo json_encode($data);
    }else{
      echo json_encode($results);
    }
    //fin
  }

  //inicio
  //opcion 3
  static function getVehiculosByAsignacion($asignacion,$tipo){

    //$asignacion = (empty($asignacion)) ? $_GET['asignacion_id'] : $asignacion;

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $yes = '';
    $sql = "SELECT b.asignacion_id, a.asignacion_fecha, --a.asignacion_por, a.asignacion_status,
              b.vehiculo_id, b.conductor_id, b.reng_num, b.asignacion_status,
              c.id_linea, c.id_marca, c.modelo, d.nro_placa, e.descripcion AS linea, f.descripcion AS marca,
              g.primer_nombre, g.segundo_nombre, g.tercer_nombre, g.primer_apellido, g.segundo_apellido, g.tercer_apellido,
              b.asignacion_tipo_transporte, ISNULL(b.seguimiento_id,0) AS seguimiento_id, ISNULL(b.fecha_salida,'') AS fecha_salida,
              b.Kilometraje_ini, b.Kilometraje_fin, b.fecha_salida, b.fecha_regreso
              FROM TransporteAsignacion a
              INNER JOIN TransporteAsignacionVehiculo b ON b.asignacion_id = a.asignacion_id
              INNER JOIN dayf_vehiculos c ON b.vehiculo_id = c.id_vehiculo
              LEFT JOIN (
                SELECT  T.id_vehiculo, T.nro_placa
                FROM
                (
                  SELECT  a.id_vehiculo, a.nro_placa,  ROW_NUMBER() OVER (PARTITION BY id_vehiculo ORDER BY reng_num DESC) AS rnk FROM dayf_vehiculo_placas a
				  --WHERE a.id_vehiculo = 105
                ) T
                WHERE T.rnk = 1
              ) AS d ON d.id_vehiculo = b.vehiculo_id
              INNER JOIN dayf_vehiculo_linea e ON c.id_linea = e.id_linea
              INNER JOIN tbl_catalogo_detalle f ON c.id_marca = f.id_item
              INNER JOIN rrhh_persona g ON b.conductor_id = g.id_persona ";

              if(!empty($asignacion)){
                $sql .= "WHERE a.asignacion_id = ?";
                $sql .=" ORDER BY b.reng_num ASC";
                $q = $pdo->prepare($sql);
                $q->execute(array($asignacion));
                $response = $q->fetchAll();
              }else{
                $sql .= "WHERE b.seguimiento_id = ?";
                $sql .=" ORDER BY b.reng_num ASC";
                $q = $pdo->prepare($sql);
                $q->execute(array(1));
                $response = $q->fetchAll();
              }


    $data = array();

    foreach ($response as $key => $v) {
      // code...
      $arreglo = array();
      $arreglo = array(
        'asignacion_id'=>$v['asignacion_id'],
        'reng_num'=>$v['reng_num'],
        //'solicitud_id'=>$v['solicitud_id'],
        'vehiculo_id'=>$v['vehiculo_id'],
        'tipo_transporte'=>$v['asignacion_tipo_transporte'],
        'vehiculo_id'=>$v['vehiculo_id'],
        'conductor_id'=>$v['conductor_id'],
        'seguimiento_id'=>$v['seguimiento_id'],
        'fecha_salida'=>(!empty($v['fecha_salida'])) ? $v['fecha_salida'] : ''
      );

      $estadoasignacion = retornaEstadoVehiculoAsignacion($v['asignacion_status']);
      $tipoasignacion = retornaTipoAsignacion($v['asignacion_tipo_transporte']);
      $estadoseguimiento = retornaEstadoVehiculoSeguimiento($v['seguimiento_id']);
      $sub_array = array(
        'estado_asignacion'=>'fa fa-'.$estadoasignacion['icono'].' text-'.$estadoasignacion['color'],
        'estado_seguimiento'=>'fa fa-'.$estadoseguimiento['icono'].' text-'.$estadoseguimiento['color'],
        'seguimiento_id'=>$v['seguimiento_id'],
        'asignacion_id'=>$v['asignacion_id'],
        //'solicitud_id'=>$v['solicitud_id'],
        'asignacion_fecha'=>$v['asignacion_fecha'],
        'asignacion_status'=>$v['asignacion_status'],
        'vehiculo_id'=>$v['vehiculo_id'],
        'reng_num'=>$v['reng_num'],
        'asignacion_status'=>$v['asignacion_status'],
        'nro_placa'=>$v['nro_placa'],
        'linea'=>$v['linea'],
        'marca'=>$v['marca'],
        'modelo'=>$v['modelo'],
        'tipo_asignacion'=>$tipoasignacion,
        'Kilometraje_ini'=>(!empty($v['Kilometraje_ini'])) ? $v['Kilometraje_ini'] : 'Sin información',
        'Kilometraje_fin'=>(!empty($v['Kilometraje_fin'])) ? $v['Kilometraje_fin'] : 'Sin información',
        'fecha_salida'=>(!empty($v['fecha_salida'])) ? date('d-m-Y H:i',strtotime($v['fecha_salida'])) : 'Sin información',
        'fecha_regreso'=>(!empty($v['fecha_regreso'])) ? date('d-m-Y H:i',strtotime($v['fecha_regreso'])) : 'Sin información',
        //'hora_salida'=>date('H:i',strtotime($v['fecha_salida'])),
        //'hora_regreso'=>date('H:i',strtotime($v['fecha_regreso'])),
        'conductor'=>$v['primer_nombre'].' ' .$v['segundo_nombre'].' '.$v['tercer_nombre'].' '.$v['primer_apellido'].' '.$v['segundo_apellido'].' '.$v['tercer_apellido'],
        'fecha_salida'=>(!empty($v['fecha_salida'])) ? date('d-m-Y H:i', strtotime($v['fecha_salida'])) : '',
        'arreglo'=>$arreglo
      );

      $data[] = $sub_array;
    }

    if($tipo == 1){
      return $data;
    }else if($tipo == 2){
      echo json_encode($data);
    }else if($tipo == 3){
      $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData"=>$data
      );

      echo json_encode($results);
    }


  }

  static function getSolicitudesByAsignacion($asignacion,$filtro){

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.solicitud_id,
            b.descripcion AS direccion, a.solicitud_year, a.solicitud_correlativo AS correlativog, REPLACE(STR(a.solicitud_correlativo_dia, 3), SPACE(1), '0') AS correlativod,
            a.solicitud_duracion, a.solicitud_tipo_duracion, a.solicitud_fecha, a.solicitud_fecha_creacion,
            c.primer_nombre, c.segundo_nombre, c.tercer_nombre, c.primer_apellido, c.segundo_apellido, c.tercer_apellido,
            a.solicitud_status, a.solicitud_tiempo_finalizado, a.solicitud_tipo, d.descripcion AS motivo, a.solicitud_cant_personas, b.descripcion_corta AS direccion_siglas, a.solicitud_observaciones

            FROM TransporteSolicitud a
            INNER JOIN rrhh_direcciones b ON a.direccion_id = b.id_direccion
            INNER JOIN rrhh_persona c ON a.creado_por = c.id_persona
            INNER JOIN tbl_catalogo_detalle d ON a.solicitud_motivo_id = d.id_item
            INNER JOIN TransporteAsignacionSolicitud e ON a.solicitud_id = e.solicitud_id
            WHERE e.asignacion_id = ?
          ";



    $p = $pdo->prepare($sql);
    $p->execute(array($asignacion));
    $response = $p->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();
    $data = array();
    //$claseT = new Transporte;
    foreach ($response as $key => $t) {
      //$destinos = $claseT->getDestinosTransporte($t['solicitud_id'],2);
      $arrayDestinos = array();
      $lista = '';
      $lugar = '';
      $x = 0;
      /*foreach ($destinos as $key => $d) {
        // code...
        $x ++;
        $lista .= '<h6>'.$x.'. '.$d['departamento'].' - '.$d['municipio'].' - '.$d['lugar_poblado'].': <strong>'.$d['direccion'].'</strong></h2>';
      }*/

      //$lugares = count($destinos);

      //$lugar='<strong>Destino(s): </strong><br><span class="text-info"><br>'.$lista.'</span>';
      //$lugar2 = '<span class="text-info">'.$lista.'</span>';
      $status = retornaEstadoSolicitud($t['solicitud_status']);
      $statusV = retornaEstadoSolicitudVehiculo($t['solicitud_tiempo_finalizado']);
      $progresse = '';
      $progresse .= '<div class="text-left" style="margin-top:0px; width:100px; ">
                        <div style="margin-top:0px; "><span class="badge-sm text-' . $status['color'] . '">
                        <i class="fa fa-'.$status['icon'].'"></i> ' . $status['estado'] . '</span>
                        <br>
                        <span class="badge-sm text-' . $statusV['color'] . '">
                        <i class="fa fa-'.$statusV['icon'].'"></i> ' . $statusV['estado'] . '</span>';
      $progresse .= ($status['porcentaje'] > 0) ? '<div class="progress progress-striped skill-bar " style="height:6px">
                <div class="progress-bar progress-bar-striped bg-' . $statusV['color'] . '" role="progressbar" aria-valuenow="' . $statusV['porcentaje'] . '" aria-valuemin="' . $statusV['porcentaje'] . '" aria-valuemax="100" style="width: ' . $statusV['porcentaje'] . '%">
                </div>
              </div></div></div>' : '';
              $progressV = '';
              /*$progressV .= '<div style="margin-top:0px; "><span class="badge-sm text-' . $statusV['color'] . '">' . $statusV['estado'] . '</span>';
              $progressV .= ($statusV['porcentaje'] > 0) ? '<div class="progress progress-striped skill-bar " style="height:6px">
                        <div class="progress-bar progress-bar-striped bg-' . $statusV['color'] . '" role="progressbar" aria-valuenow="' . $statusV['porcentaje'] . '" aria-valuemin="' . $statusV['porcentaje'] . '" aria-valuemax="100" style="width: ' . $status['porcentaje'] . '%">
                        </div>
                      </div></div>' : '';*/
      $sub_array = array(
        'DT_RowId'=>$t['solicitud_id'],
        'solicitud_id'=>$t['solicitud_id'],
        'direccion'=>$t['direccion'],
        'direccion_siglas'=>$t['direccion_siglas'],
        'correlativo'=>'<strong>'.$t['solicitud_year'].'-'.$t['correlativog'].'-'.$t['correlativod'].'</strong>',
        'solicitud_fecha'=>date('d-m-Y', strtotime($t['solicitud_fecha'])),
        'solicitud_hora'=>date('h:i A', strtotime($t['solicitud_fecha'])),
        'solicitud_fecha_creacion'=>$t['solicitud_fecha_creacion'],
        'solicitante'=>$t['primer_nombre'].' ' .$t['segundo_nombre'].' '.$t['tercer_nombre'].' '.$t['primer_apellido'].' '.$t['segundo_apellido'].' '.$t['tercer_apellido'],
        'solicitud_status_p'=> $progresse,//'<span class="text-'.$status['color'].'">'.retornaEstadoSolicitud($t['solicitud_status']).'</span>',
        'solicitud_tiempo_finalizado_p'=>$progressV, //retornaEstadoSolicitudVehiculo($t['solicitud_tiempo_finalizado']),
        'solicitud_tipo'=>$t['solicitud_tipo'],
        'duracion'=>$t['solicitud_duracion'].' '.retornaTipoDuracion($t['solicitud_tipo_duracion']),
        'motivo'=>$t['motivo'],
        //'destinos'=>$lugar,//$arrayDestinos,
        //'destinosd'=>$lugar2,
        'solicitud_status'=>$t['solicitud_status'],
        'solicitud_tiempo_finalizado'=>$t['solicitud_tiempo_finalizado'],
        //'arreglo'=>$arreglo,
        'solicitud_observaciones'=>strtoupper($t['solicitud_observaciones']),
        'disponible'=>$status['disponible'],
        'cantidad'=>$t['solicitud_cant_personas'],
        'accion'=>'',
        //'cant_lugares'=>$lugares
        //<span class="btn btn-sm btn-soft-info" onclick="imprimirCertificacion('.$t['solicitud_id'].')"><i class="fa fa-print"></i></span>'
      );
      $data[] = $sub_array;
    }
    $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData"=>$data
    );

    if($filtro == 0){
      return $data;
    }else{
      echo json_encode($results);
    }
  }
  //fin

  //opcion 4
  static function guardarEstadoAsignacion(){
    $asignacion_id = $_POST['asignacion_id'];
    $estado = $_POST['estado'];
    $pdo = Database::connect_sqlsrv();
    $yes = '';
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sqlc = "UPDATE TransporteAsignacion SET asignacion_status = ? WHERE asignacion_id = ?";
      $qc = $pdo->prepare($sqlc);
      $qc->execute(array($estado,$asignacion_id));

      $state = ($estado == 3) ? 4 : 5;
      $sqlcd2 = "UPDATE TransporteSolicitud
                SET solicitud_tiempo_finalizado = ?
                FROM TransporteSolicitud
                INNER JOIN TransporteAsignacionSolicitud
                ON (TransporteSolicitud.solicitud_id = TransporteAsignacionSolicitud.solicitud_id)
                WHERE TransporteSolicitud.solicitud_status IN (2,3)
                AND TransporteAsignacionSolicitud.asignacion_id = ?";

      $qcd2 = $pdo->prepare($sqlcd2);
      $qcd2->execute(array($state,$asignacion_id));

      if($estado == 3){
        $yes = array('msg'=>'OK','id'=>'','message'=>'Proceso finalizado.');
      }else if($estado == 4){
        $yes = array('msg'=>'OK','id'=>'','message'=>'Proceso cancelado.');
      }


      $pdo->commit();
    }catch (PDOException $e){

      $yes = array('msg'=>'ERROR','id'=>$e);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','id'=>$e2);
      }
    }
    echo json_encode($yes);
    Database::disconnect_sqlsrv();
  }

  static function getVehiculosEnComision(){
    self::getVehiculosByAsignacion(NULL,3);
  }

  static function getAsignacionById(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sqlcd = "SELECT asignacion_id,asignacion_fecha,asignado_por,asignacion_year,correlativo,asignacion_status
              FROM TransporteAsignacion WHERE asignacion_id = ?";
    $qcd = $pdo->prepare($sqlcd);
    $qcd->execute(array($_GET['asignacion_id']));
    $a = $qcd->fetch();

    $sqlcd1 = "SELECT solicitud_id
              FROM TransporteAsignacionSolicitud WHERE asignacion_id = ?";
    $qcd1 = $pdo->prepare($sqlcd1);
    $qcd1->execute(array($_GET['asignacion_id']));
    $codes = $qcd1->fetchAll();

    $codigos = '';

    foreach ($codes as $key => $c) {
      // code...
      $codigos.=','.$c['solicitud_id'];
    }

    Database::disconnect_sqlsrv();

    $data = array(
      'asignacion_id'=>$a['asignacion_id'],
      'asignacion_fecha'=>$a['asignacion_fecha'],
      'asignado_por'=>$a['asignado_por'],
      'asignacion_year'=>$a['asignacion_year'],
      'correlativo'=>$a['correlativo'],
      'asignacion_status'=>$a['asignacion_status'],
      'codigos'=>$codigos
    );

    echo json_encode($data);

  }

}

if (isset($_POST['opcion']) || isset($_GET['opcion'])) {

  $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

  $asignacion = (!empty($_GET['asignacion_id'])) ? $_GET['asignacion_id'] : '';
  switch ($opcion) {
    case 0:
      Asignacion::getPrivilegios();
    break;
    case 1:
      Asignacion::getAsignacionsList();
    break;
    case 2:
      Asignacion::getAsignacionesBoard();
    break;
    case 3:
      Asignacion::getVehiculosByAsignacion($asignacion,2);
    break;
    case 4:
      Asignacion::guardarEstadoAsignacion();
    break;
    case 5:
      Asignacion::getVehiculosEnComision();
    break;
    case 6:
      Asignacion::getAsignacionById();
    break;
    /*case 5:
      Asignacion::retornaDireccionEmpleado();
    break;
    case 6:
      Asignacion::asignarVehiculosComision();
    break;
    case 7:
      Asignacion::actualizarEstadoSolicitud();
    break;
    case 8:
      Asignacion::getVehiculosBySolicitud();
    break;
    case 9:
      Asignacion::setEstadoVehiculoSingular();
    break;
    case 10:
      Asignacion::setEstadoVehiculoGrupal();
    break;
    case 11:
      Asignacion::updateVehiculoSolicitud();
    break;*/
  }
}

?>
