<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
class Transporte{

  public $transporte;
  public $opcion = 1;

  protected function __construct() {
      $this->transporte = array();
  }

  public static function getPrivilegios($tipo){
    $data = array();

    $array = evaluar_flags_by_sistema($_SESSION['id_persona'],8686);

    $pos = $array[3]['id_persona'];
    $data = array(
      'dir_asistente'=>($array[0]['flag_autoriza'] == 1) ? true : false,
      'dir_director'=>($array[1]['flag_autoriza'] == 1) ? true : false,
      'dir_responsable'=>($array[2]['flag_autoriza'] == 1) ? true : false,
      'encargado_transporte'=>($array[3]['flag_autoriza'] == 1) ? true : false,
      'jefe_transporte'=>($array[4]['flag_autoriza'] == 1) ? true : false,
      'subdirector_manto'=>($array[5]['flag_autoriza'] == 1) ? true : false,
      'director_financiero'=>($array[6]['flag_autoriza'] == 1) ? true : false,
      'secretario'=>($array[7]['flag_autoriza'] == 1) ? true : false,
      'conductor'=>($array[8]['flag_autoriza'] == 1) ? true : false,
      'comision_presidencial'=>($array[10]['flag_es_menu'] == 1) ? true : false,
      'columna_check'=>(($array[1]['flag_autoriza'] == 1) || ($array[3]['flag_autoriza'] == 1) || ($array[4]['flag_autoriza'] == 1)) ? true :false
    );
    if($tipo == 1){
      echo json_encode($data);
    }else if($tipo == 2){
      return $data;
    }

  }
  public static function getTransportesList(){

    $filtro = (!empty($_POST['filtro'])) ? $_POST['filtro'] : $_GET['filtro'];
    $year = (!empty($_POST['year'])) ? $_POST['year'] : NULL;
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

          ";
          if($filtro == 1){
            $sql.= " WHERE a.solicitud_tiempo_finalizado IN (1,2,3) AND a.solicitud_status NOT IN (4)";
          }else if ($filtro == 2){
            $sql.= " WHERE a.solicitud_tiempo_finalizado IN (4)";
          }else if ($filtro == 3){
            $sql.=" WHERE a.solicitud_status IN (4)";
          }else if($filtro == 0){
            $renglon=$_GET['solicitud_id'];
            $parametros = substr($renglon, 1);
            //echo $parametros;
            $sql.= " WHERE a.solicitud_id IN ($parametros)";
          }

          if(!empty($year)){
            $sql.= " AND a.solicitud_year = $year";
          }

    $p = $pdo->prepare($sql);
    $p->execute(array());
    $response = $p->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();
    $data = array();
    foreach ($response as $key => $t) {
      $destinos = self::getDestinosTransporte($t['solicitud_id'],2);
      $arrayDestinos = array();
      $lista = '';
      $lugar = '';
      $x = 0;
      foreach ($destinos as $key => $d) {
        // code...
        $x ++;
        $lista .= '<h6>'.$x.'. '.$d['departamento'].' - '.$d['municipio'].' - '.$d['lugar_poblado'].': <strong>'.$d['direccion'].'</strong></h2>';
      }

      $lugares = count($destinos);

      $lugar='<strong>Destino(s): </strong><br><span class="text-info"><br>'.$lista.'</span>';
      $lugar2 = '<span class="text-info">'.$lista.'</span>';
      $status = retornaEstadoSolicitud($t['solicitud_status']);
      $statusV = retornaEstadoSolicitudVehiculo($t['solicitud_tiempo_finalizado']);
      $progresse = '';
      $progresse .= '<div class="text-left" style="margin-top:0px; width:100px; "><div style="margin-top:0px; "><span class="badge-sm text-' . $status['color'] . '"><i class="fa fa-'.$status['icon'].'"></i> ' . $status['estado'] . '</span><br><span class="badge-sm text-' . $statusV['color'] . '"><i class="fa fa-'.$statusV['icon'].'"></i> ' . $statusV['estado'] . '</span>';
      $progresse .= ($status['porcentaje'] > 0) ? '<div class="progress progress-striped skill-bar " style="height:6px">
                <div class="progress-bar progress-bar-striped bg-' . $status['color'] . '" role="progressbar" aria-valuenow="' . $status['porcentaje'] . '" aria-valuemin="' . $status['porcentaje'] . '" aria-valuemax="100" style="width: ' . $status['porcentaje'] . '%">
                </div>
              </div></div>' : '';
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
        'correlativo'=>'<strong>S - '.$t['correlativog'],//$t['solicitud_year'].'-'.$t['correlativog'].'-'.$t['correlativod'].'</strong>',
        'solicitud_fecha'=>date('d-m-Y', strtotime($t['solicitud_fecha'])),
        'solicitud_hora'=>date('h:i A', strtotime($t['solicitud_fecha'])),
        'solicitud_fecha_creacion'=>$t['solicitud_fecha_creacion'],
        'solicitante'=>$t['primer_nombre'].' ' .$t['segundo_nombre'].' '.$t['tercer_nombre'].' '.$t['primer_apellido'].' '.$t['segundo_apellido'].' '.$t['tercer_apellido'],
        'solicitud_status_p'=> $progresse,//'<span class="text-'.$status['color'].'">'.retornaEstadoSolicitud($t['solicitud_status']).'</span>',
        'solicitud_tiempo_finalizado_p'=>$progressV, //retornaEstadoSolicitudVehiculo($t['solicitud_tiempo_finalizado']),
        'solicitud_tipo'=>$t['solicitud_tipo'],
        'duracion'=>$t['solicitud_duracion'].' '.retornaTipoDuracion($t['solicitud_tipo_duracion']),
        'motivo'=>$t['motivo'].'|'.$t['solicitud_status'].'||'.$t['solicitud_tiempo_finalizado'],
        'destinos'=>$lugar,//$arrayDestinos,
        'destinosd'=>$lugar2,
        'solicitud_status'=>$t['solicitud_status'],
        'solicitud_tiempo_finalizado'=>$t['solicitud_tiempo_finalizado'],
        //'arreglo'=>$arreglo,
        'solicitud_observaciones'=>strtoupper($t['solicitud_observaciones']),
        'disponible'=>$status['disponible'],
        'cantidad'=>$t['solicitud_cant_personas'],
        'accion'=>'',
        'cant_lugares'=>$lugares
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
      echo json_encode($data);
    }else{
      echo json_encode($results);
    }
  }

  static function getHojaCertificacion(){
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "  SELECT * FROM TransporteBien WHERE bien_id = ?";
    $p = $pdo->prepare($sql);
    $p->execute(array($_POST['bien_id']));
    $response = $p->fetch(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();
    $data = array();
    $data = array(
      'DT_RowId'=>$response['bien_id'],
      'bien_id'=>$response['bien_id'],
      'bien_sicoin_code'=>$response['bien_sicoin_code'],
      'bien_nombre'=>$response['bien_nombre'],
      'bien_descripcion'=>$response['bien_descripcion'],
      'bien_monto'=>$response['bien_monto'],
      'bien_status'=>$response['bien_status'],
      'bien_status_movimiento'=>$response['bien_status_movimiento'],
      'bien_fecha_adquisicion'=>$response['bien_fecha_adquisicion'],
      'bien_fecha_creacion'=>$response['bien_fecha_creacion'],
      'bien_renglon_id'=>$response['bien_renglon_id'],
      'bien_ubicacion_id'=>$response['bien_ubicacion_id'],
      'bien_tipo_id'=>$response['bien_tipo_id'],
      //'arreglo'=>$arreglo,
      'accion'=>''
    );

    echo json_encode($data);
  }
  static function crearSolicitudTransporte(){
    include_once '../../empleados/php/back/functions.php';
    $clase = new empleado;
    $pdo = Database::connect_sqlsrv();

    $id_direccion = (!empty($_POST['id_direccion'])) ? $_POST['id_direccion'] : NULL;
    $id_departamento = (!empty($_POST['id_departamento'])) ? $_POST['id_departamento'] : NULL;
    $id_empleado = (!empty($_POST['id_empleado'])) ? $_POST['id_empleado'] : NULL;

    $fecha_salida = date('Y-m-d H:i:s', strtotime($_POST['fecha_salida']));
    $hora_salida = date('H:i:s', strtotime($_POST['fecha_salida']));
    $duracion = $_POST['duracion'];
    $id_tipo_seleccion = $_POST['id_tipo_seleccion'];
    $cant_personas = $_POST['cant_personas'];
    $observaciones = strtoupper($_POST['observaciones']);
    $motivo = $_POST['motivo_solicitud'];
    $year = date('Y');
    $creado_en = date('Y-m-d H:i:s');
    $creado_por = $_SESSION['id_persona'];
    $destinos = $_POST['destinos'];
    $fecha_regreso = date('Y-m-d', strtotime($_POST['fecha_regreso']));
    $tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : 1;
    $responsable = $_POST['responsable'];

    $operado_por = '';
    if(empty($id_direccion)){
      $e = $clase->get_empleado_by_id_ficha($creado_por);
      $direccion =$e['id_dirf'];

      if (($e['id_subdireccion_funcional'] == 34 && $_SESSION['id_persona'] != 5790) || ($e['id_subdireccion_funcional'] == 21 && $_SESSION['id_persona'] != 5790)) {
        $direccion = 207;
      }

      if($e['id_tipo']==2){
        $direccion=$e['id_dirs'];
      }
      else
      if($e['id_tipo']==4){
        $direccion=$e['id_dirapy'];
      }
    }else{
      $operado_por = $creado_por;
      $direccion = $id_direccion;
      $creado_por = $id_empleado;

    }

    $yes = '';
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $correlativo = 1;
      $correlativo_diario = 1;

      /*$sqlc = "SELECT TOP 1 solicitud_correlativo, CONVERT(VARCHAR,solicitud_fecha_creacion,23) AS fecha FROM TransporteSolicitud WHERE YEAR(solicitud_fecha_creacion) = ? ORDER BY solicitud_year, solicitud_correlativo DESC";
      $qc = $pdo->prepare($sqlc);
      $qc->execute(array($year));
      $data_correlativo = $qc->fetch();
      if(!empty($data_correlativo['solicitud_correlativo'])){
        if(date('Y-m-d') == $data_correlativo['fecha']){
          $correlativo = $data_correlativo['solicitud_correlativo'];
        }else{
          $correlativo = $data_correlativo['solicitud_correlativo'] + 1;
        }
      }
      $sqlcd = "SELECT correlativo FROM TransporteSolicitud WHERE correlativo = ?";
      $qcd = $pdo->prepare($sqlcd);
      $qcd->execute(array($correlativo));
      $valor = $qcd->fetch();
      if(!empty($valor)){
        $correlativo = $valor['correlativo'];
      }
      */
      $correlativo = self::getRandomString(1);

      $sql0 = "INSERT INTO TransporteSolicitud (
        solicitud_fecha,solicitud_year,solicitud_correlativo,solicitud_correlativo_dia,solicitud_status,
        solicitud_motivo_id,solicitud_fecha_creacion,solicitud_cant_personas,solicitud_duracion,solicitud_tipo_duracion,
        direccion_id,departamento_id,creado_por,creado_en,solicitud_observaciones, solicitud_fecha_regreso, solicitud_tipo, solicitud_tiempo_finalizado,
        solicitud_responsable
      )
      VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($fecha_salida,$year,$correlativo,$correlativo_diario,1,$motivo,$creado_en,$cant_personas,$duracion,$id_tipo_seleccion,$direccion,NULL,$creado_por,$creado_en,$observaciones,$fecha_regreso,$tipo,1,$responsable));

      //$cg=$clased->get_correlativo_generado($creador);
      $lastInsertId = $pdo->lastInsertId();

      if(!empty($operado_por)){
        $sql2 = "UPDATE TransporteSolicitud SET solicitud_status = ?, operado_por = ? WHERE solicitud_id = ?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array(2,$operado_por,$lastInsertId));
      }

      $x = 0;
      foreach ($destinos as $d) {
        $poblado = (!empty($d['lugar_id'])) ? $d['lugar_id'] : NULL;
        $x ++;
        $sql3 = "INSERT INTO TransporteSolicitudDestino (
          solicitud_id,
          reng_num,
          id_pais,
          id_departamento,
          id_municipio,
          destino_poblado,
          direccion,
          destino_status

        ) values(?,?,?,?,?,?,?,?)";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array($lastInsertId,$x,'GT',$d['departamento_id'],$d['municipio_id'],$poblado,strtoupper($d['direccion']),1));
      }
      $lastInsertId = $correlativo;


      $yes = array('msg'=>'OK','id'=>$lastInsertId,'message'=>'Solicitud generada.');

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

  static function getDestinosTransporte($destino,$tipo){
    //inicio
    $destino = (!empty($destino)) ? $destino : NULL;
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.solicitud_id, a.reng_num, a.id_pais, a.id_departamento, a.id_municipio, a.destino_poblado, a.direccion, a.destino_status,
            b.nombre AS departamento, c.nombre AS municipio, d.nombre AS lugar_poblado

            FROM TransporteSolicitudDestino a
            LEFT JOIN tbl_departamento b ON a.id_departamento = b.id_departamento
            LEFT JOIN tbl_municipio c ON a.id_municipio = c.id_municipio
            LEFT JOIN tbl_aldea d ON a.destino_poblado = d.id_aldea
          ";

          if(!empty($destino)){
            $sql.= " WHERE a.solicitud_id = $destino";
          }
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $response = $p->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();
    $data = array();
    foreach ($response as $key => $d) {
      $sub_array = array(
        'solicitud_id'=>$d['solicitud_id'],
        'reng_num'=>$d['reng_num'],
        'id_pais'=>$d['id_pais'],
        'id_departamento'=>$d['id_departamento'],
        'id_municipio'=>$d['id_municipio'],
        'destino_poblado'=>$d['destino_poblado'],
        'direccion'=>$d['direccion'],
        'destino_status'=>$d['destino_status'],
        'departamento'=>$d['departamento'],
        'municipio'=>$d['municipio'],
        'lugar_poblado'=>$d['lugar_poblado'],
      );
      $data[] = $sub_array;
    }


    if($tipo == 1){
      $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData"=>$data
      );

      echo json_encode($results);
    }else if($tipo == 2){
      return $data;
    }

    //fin
  }

  static function retornaDireccionEmpleado(){
    //include_once '../../viaticos/php/back/functions.php';
    include_once '../../empleados/php/back/functions.php';

    $empleados = array();
    $clase = new empleado;
    $empleado = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);

    $direccion = $empleado['id_dirf'];
    if (($empleado['id_subdireccion_funcional'] == 34 && $_SESSION['id_persona'] != 5790) || ($empleado['id_subdireccion_funcional'] == 21 && $_SESSION['id_persona'] != 5790)) {
      $direccion = 207;
    }
    $data = array(
      'id_direccion'=>$direccion
    );
    echo json_encode($data);
  }

  //opcion 6
  static function asignarVehiculosComision(){
    $solicitud_id = $_POST['solicitud_id'];
    $parametros = substr($solicitud_id, 1);

    $codigos = '('.$parametros.')';
    $arreglo=explode(",",$parametros);

    $vehiculos = $_POST['vehiculos'];
    $year = date('Y');
    $creado_en = date('Y-m-d H:i:s');
    $creado_por = $_SESSION['id_persona'];

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $yes = '';
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      /*$correlativo = 1;
      $sqlcd = "SELECT TOP 1 correlativo FROM TransporteAsignacion WHERE asignacion_year = ? ORDER BY asignacion_id DESC";
      $qcd = $pdo->prepare($sqlcd);
      $qcd->execute(array($year));
      $datosc = $qcd->fetch();
      if(!empty($datosc['correlativo'])){
        $correlativo = $datosc['correlativo'] + 1;
      }*/

      $correlativo = self::getRandomString(2);

      $sql0 = "INSERT INTO TransporteAsignacion (
        asignacion_fecha,
        asignado_por,
        asignacion_year,
        correlativo,
        asignacion_status
      )
      VALUES(?,?,?,?,?)";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($creado_en, $creado_por, $year, $correlativo,1));

      $lastInsertId = $pdo->lastInsertId();

      foreach ($arreglo as $key => $value) {
        // code...
        $sql1 = "INSERT INTO TransporteAsignacionSolicitud (
          asignacion_id,
          solicitud_id,
          asignacion_fecha,
          asignacion_por,
          asignacion_status
        )
        VALUES(?,?,?,?,?)";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array($lastInsertId, $value, $creado_en, $creado_por, 1));

        $sql3 = "UPDATE TransporteSolicitud SET solicitud_tiempo_finalizado = ? WHERE solicitud_id = ? ";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array(2,$value));

      }

      foreach ($vehiculos as $key => $v) {
        // code...
        $reng_num = 1;
        $sqlcd = "SELECT TOP 1 reng_num FROM TransporteAsignacionVehiculo WHERE asignacion_id = ? ORDER BY reng_num DESC";
        $qcd = $pdo->prepare($sqlcd);
        $qcd->execute(array($lastInsertId));
        $reng = $qcd->fetch();
        if(!empty($reng['reng_num'])){
          $reng_num = $reng['reng_num'] + 1;
        }

        $fecha_salida = str_replace('T', ' ', $v['fecha_salida']);
        $sql2 = "INSERT INTO TransporteAsignacionVehiculo (
          asignacion_id,
          vehiculo_id,
          reng_num,
          conductor_id,
          conductor_tipo,
          asignacion_tipo_transporte,
          asignacion_status,
          asignacion_fecha,
          asignacion_por,
          vehiculo_tipo,
          fecha_salida
        )
        VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($lastInsertId, $v['vehiculo_id'], $reng_num, $v['conductor_id'],1,$v['tipo_transporte_id'],1,$creado_en,$creado_por,$v['tipo_vehiculo'],date('Y-m-d H:i:s', strtotime($fecha_salida))));
      }



      $yes = array('msg'=>'OK','id'=>$lastInsertId,'message'=>'Solicitud generada.');

      $pdo->commit();
    }catch (PDOException $e){

      $yes = array('msg'=>'ERROR','id'=>$e);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','id'=>$e2);
      }
    }
    echo json_encode($yes);
    Database::disconnect_sqlsrv();

    /*foreach ($vehiculos as $key => $v) {
      // code...
      echo $v['vehiculo_id'];
    }*/
  }

  //opcion 7
  static function actualizarEstadoSolicitud(){
    $solicitud_id = $_POST['solicitud_id'];
    $parametros = '('.substr($solicitud_id, 1).')';
    $status_actual = $_POST['status_actual'];
    $status_anterior = $_POST['status_anterior'];
    $message = $_POST['message'];

    //echo $status_actual;
    //$arreglo=explode(",",$parametros);

    $year = date('Y');
    $creado_en = date('Y-m-d H:i:s');
    $creado_por = $_SESSION['id_persona'];

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $yes = '';
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $correlativo = 1;
      $sqlcd = "UPDATE TransporteSolicitud SET solicitud_status = ? WHERE solicitud_id IN $parametros AND solicitud_status = ?";
      $qcd = $pdo->prepare($sqlcd);
      $qcd->execute(array($status_actual,$status_anterior));

      $yes = array('msg'=>'OK','id'=>'','message'=>$message);

      $pdo->commit();
    }catch (PDOException $e){

      $yes = array('msg'=>'ERROR','id'=>$e);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','id'=>$e2);
      }
    }
    echo json_encode($yes);
    Database::disconnect_sqlsrv();

    /*foreach ($vehiculos as $key => $v) {
      // code...
      echo $v['vehiculo_id'];
    }*/
  }

  //opcion 8
  static function getVehiculosBySolicitud(){
    $solicitud = $_GET['solicitud_id'];
    $tipo = $_GET['tipo'];

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $yes = '';
    $sql = "SELECT a.asignacion_id, a.solicitud_id, a.asignacion_fecha, a.asignacion_por, a.asignacion_status,
              b.vehiculo_id, b.conductor_id, b.reng_num, b.asignacion_status,
              c.id_linea, c.id_marca, c.modelo, d.nro_placa, e.descripcion AS linea, f.descripcion AS marca,
              g.primer_nombre, g.segundo_nombre, g.tercer_nombre, g.primer_apellido, g.segundo_apellido, g.tercer_apellido,
              b.asignacion_tipo_transporte, ISNULL(b.seguimiento_id,0) AS seguimiento_id,
              b.fecha_salida, b.fecha_regreso, h.correlativo AS correlativoa
              FROM TransporteAsignacionSolicitud a
              INNER JOIN TransporteAsignacionVehiculo b ON a.asignacion_id = b.asignacion_id
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
              INNER JOIN rrhh_persona g ON b.conductor_id = g.id_persona
              INNER JOIN TransporteAsignacion h ON a.asignacion_id = h.asignacion_id
              ";

              if($tipo == 'asignacion'){
                $sql .= " WHERE a.asignacion_id = ?";
              }else{
                $sql .= " WHERE a.solicitud_id = ?";
              }

    $q = $pdo->prepare($sql);
    $q->execute(array($solicitud));
    $response = $q->fetchAll();

    $data = array();

    foreach ($response as $key => $v) {
      // code...
      $arreglo = array();
      $arreglo = array(
        'asignacion_id'=>$v['asignacion_id'],
        'solicitud_id'=>$v['solicitud_id'],
        'vehiculo_id'=>$v['vehiculo_id'],
        'tipo_transporte'=>$v['asignacion_tipo_transporte'],
        'vehiculo_id'=>$v['vehiculo_id'],
        'conductor_id'=>$v['conductor_id'],
        'reng_num'=>$v['reng_num'],
        'seguimiento_id'=>$v['seguimiento_id'],
        'fecha_salida'=>$v['fecha_salida'],
      );

      $estadoasignacion = retornaEstadoVehiculoAsignacion($v['asignacion_status']);
      $tipoasignacion = retornaTipoAsignacion($v['asignacion_tipo_transporte']);
      $estadoseguimiento = retornaEstadoVehiculoSeguimiento($v['seguimiento_id']);
      $sub_array = array(
        'correlativo_a'=>'<small>Asignación No.</small> <strong>A-'.$v['correlativoa'].'</strong>',
        'estado_asignacion'=>'fa fa-'.$estadoasignacion['icono'].' text-'.$estadoasignacion['color'],
        'estado_seguimiento'=>'fa fa-'.$estadoseguimiento['icono'].' text-'.$estadoseguimiento['color'],
        'seguimiento_id'=>$v['seguimiento_id'],
        'asignacion_id'=>$v['asignacion_id'],
        'solicitud_id'=>$v['solicitud_id'],
        'asignacion_fecha'=>$v['asignacion_fecha'],
        'asignacion_status'=>$v['asignacion_status'],
        'vehiculo_id'=>$v['vehiculo_id'],
        'reng_num'=>$v['reng_num'],
        'asignacion_status'=>$v['asignacion_status'],
        'nro_placa'=>$v['nro_placa'],
        'linea'=>$v['linea'],
        'marca'=>$v['marca'],
        'modelo'=>$v['modelo'],
        'fecha_salida'=>date('d-m-Y H:i',strtotime($v['fecha_salida'])),
        'fecha_regreso'=>((!empty($v['fecha_regreso']))) ? date('H:i',strtotime($v['fecha_regreso'])) : 'Sin información',
        'hora_salida'=>date('H:i',strtotime($v['fecha_salida'])),
        'hora_regreso'=>((!empty($v['fecha_regreso']))) ? date('H:i',strtotime($v['fecha_regreso'])) : 'Sin información',
        'tipo_asignacion'=>$tipoasignacion,
        'conductor'=>$v['primer_nombre'].' ' .$v['segundo_nombre'].' '.$v['tercer_nombre'].' '.$v['primer_apellido'].' '.$v['segundo_apellido'].' '.$v['tercer_apellido'],
        'arreglo'=>$arreglo
      );

      $data[] = $sub_array;
    }

    echo json_encode($data);
  }

  //opcion 9
  static function setEstadoVehiculoSingular(){
    $solicitud_id = (!empty($_POST['solicitud_id'])) ? $_POST['solicitud_id'] : NULL;
    $asignacion_id = $_POST['asignacion_id'];
    $vehiculo_id = $_POST['vehiculo_id'];
    $estado = $_POST['estado'];
    $message = $_POST['mensaje'];
    $reng_num = $_POST['reng_num'];
    $id_anulacion_asignacion = (!empty($_POST['id_anulacion_asignacion'])) ? $_POST['id_anulacion_asignacion'] : NULL;
    $id_kilometraje = (!empty($_POST['id_kilometraje'])) ? $_POST['id_kilometraje'] : NULL;
    $id_fecha = (!empty($_POST['id_fecha'])) ? date('Y-m-d H:i:s', strtotime($_POST['id_fecha'])) : NULL;

    //echo $id_kilometraje;

    //echo $status_actual;
    //$arreglo=explode(",",$parametros);

    $year = date('Y');
    $creado_en = date('Y-m-d H:i:s');
    $creado_por = $_SESSION['id_persona'];

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $yes = '';
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      if($estado == 3){
        //eliminar
        $sqlcd = "UPDATE TransporteAsignacionVehiculo SET asignacion_status = ?, asigancion_obs_cancelacon = ? WHERE asignacion_id = ? AND vehiculo_id = ? AND reng_num = ?";
        $qcd = $pdo->prepare($sqlcd);
        $qcd->execute(array($estado, $id_anulacion_asignacion, $asignacion_id, $vehiculo_id, $reng_num));

        $sql2 = "SELECT COUNT(b.solicitud_id) AS conteo
        FROM TransporteAsignacionVehiculo a
        INNER JOIN TransporteAsignacionSolicitud b ON a.asignacion_id = b.asignacion_id
        WHERE b.asignacion_id = ? AND a.vehiculo_id = ? AND a.reng_num = ?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($asignacion_id, $vehiculo_id, $reng_num));
        $solicitudes = $q2->fetch();

        if($solicitudes['conteo'] == 1){
          $sqlcd = "UPDATE TransporteAsignacionSolicitud SET asignacion_status = ? WHERE asignacion_id = ?";
          $qcd = $pdo->prepare($sqlcd);
          $qcd->execute(array(2, $asignacion_id));
        }

        $yes = array('msg'=>'OK','id'=>'','message'=>'Vehículo cancelado.');
      }else{
        //inicio
        // 1 es salida
        // 2 es retorno
        $campo = ($estado == 1) ? 'Kilometraje_ini' : 'Kilometraje_fin';
        $campo2 = ($estado == 1) ? 'fecha_salida' : 'fecha_regreso';
        $correlativo = 1;

        //verificar que el vehículo está de comisión
        $sqlcda = "SELECT a.seguimiento_id, b.correlativo
        FROM TransporteAsignacionVehiculo a
        INNER JOIN TransporteAsignacion b ON a.asignacion_id = b.asignacion_id
        WHERE a.vehiculo_id = ? AND a.seguimiento_id = ?";
        $qcda = $pdo->prepare($sqlcda);
        $qcda->execute(array($vehiculo_id,1));
        $vehiculo = $qcda->fetch();

        if(empty($vehiculo['seguimiento_id']) || $estado == 2){
          $sqlcd = "UPDATE TransporteAsignacionVehiculo SET seguimiento_id = ?, $campo = ?, $campo2 = ? WHERE asignacion_id = ? AND vehiculo_id = ? AND reng_num = ?";
          $qcd = $pdo->prepare($sqlcd);
          $qcd->execute(array($estado, $id_kilometraje,$id_fecha,$asignacion_id, $vehiculo_id, $reng_num));

          $sqlcd = "UPDATE TransporteAsignacion SET asignacion_status = ? WHERE asignacion_id = ?";
          $qcd = $pdo->prepare($sqlcd);
          $qcd->execute(array(2,$asignacion_id));

          $sqlcd2 = "UPDATE TransporteSolicitud
                    SET solicitud_tiempo_finalizado = ?
                    FROM TransporteSolicitud
                    INNER JOIN TransporteAsignacionSolicitud
                    ON (TransporteSolicitud.solicitud_id = TransporteAsignacionSolicitud.solicitud_id)
                    WHERE TransporteSolicitud.solicitud_status IN (2,3)
                    AND TransporteAsignacionSolicitud.asignacion_id = ?";
          $qcd2 = $pdo->prepare($sqlcd2);
          $qcd2->execute(array(3,$asignacion_id));

          sleep(1.5);
          $yes = array('msg'=>'OK','id'=>'','message'=>$message);
        }else{
          $yes = array('msg'=>'ERROR','id'=>'','message'=>'El vehículo se encuentra en comisión, en la asignación: A-'.$vehiculo['correlativo']);
        }

        /*$sql2 = "SELECT COUNT(a.vehiculo_id) AS conteo
        FROM TransporteAsignacionVehiculo a
        INNER JOIN TransporteAsignacionSolicitud b ON a.asignacion_id = b.asignacion_id
        WHERE b.solicitud_id = ? AND a.asignacion_status = ?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($solicitud_id, 1));
        $totalAsgi = $q2->fetch();

        $sql3 = "SELECT COUNT(a.seguimiento_id) AS conteo
        FROM TransporteAsignacionVehiculo a
        INNER JOIN TransporteAsignacionSolicitud b ON a.asignacion_id = b.asignacion_id
        WHERE b.solicitud_id = ? AND a.seguimiento_id = ?";
        $q3 = $pdo->prepare($sql3);
        $q3->execute(array($solicitud_id, 2));
        $totalTer = $q3->fetch();

        if($totalAsgi['conteo'] == $totalTer['conteo']){
          $sql4 = "UPDATE TransporteSolicitud SET solicitud_tiempo_finalizado = ? WHERE solicitud_id = ?";
          $q4 = $pdo->prepare($sql4);
          $q4->execute(array(4,$solicitud_id));
        }else{
          $sqlcd = "UPDATE TransporteSolicitud SET solicitud_tiempo_finalizado = ? WHERE solicitud_id = ?";
          $qcd = $pdo->prepare($sqlcd);
          $qcd->execute(array(3,$solicitud_id));
        }*/



        //fin
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
  static function setEstadoVehiculoGrupal(){

  }

  static function updateVehiculoSolicitud(){
    $asignacion_id = $_POST['asignacion_id'];
    $tipo = $_POST['tipo'];
    $info= $_POST['datos'];
    $creado_en = date('Y-m-d H:i:s');
    $creado_por = $_SESSION['id_persona'];
  //echo $info['conductor_id'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $yes = '';

    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      if($tipo == 2){
        $sqlcd = "UPDATE TransporteAsignacionVehiculo SET vehiculo_id = ?, conductor_id = ?, fecha_salida = ?, asignacion_tipo_transporte = ? WHERE asignacion_id = ? AND reng_num = ?";
        $qcd = $pdo->prepare($sqlcd);
        $qcd->execute(array($info['vehiculo_id'],$info['conductor_id'],date('Y-m-d H:i:s', strtotime($info['fecha_salida'])),$info['tipo_transporte_id'],$asignacion_id,$info['reng_num']));
        $yes = array('msg'=>'OK','id'=>'','message'=>'Vehículo actualizado correctamente');
      }else if($tipo == 3){
        //inicio
        $reng_num = 1;
        $sqlcd = "SELECT TOP 1 reng_num FROM TransporteAsignacionVehiculo WHERE asignacion_id = ? ORDER BY reng_num DESC";
        $qcd = $pdo->prepare($sqlcd);
        $qcd->execute(array($asignacion_id));
        $reng = $qcd->fetch();
        if(!empty($reng['reng_num'])){
          $reng_num = $reng['reng_num'] + 1;
        }

        $fecha_salida = str_replace('T', ' ', $info['fecha_salida']);
        $sql2 = "INSERT INTO TransporteAsignacionVehiculo (
          asignacion_id,
          vehiculo_id,
          reng_num,
          conductor_id,
          conductor_tipo,
          asignacion_tipo_transporte,
          asignacion_status,
          asignacion_fecha,
          asignacion_por,
          vehiculo_tipo,
          fecha_salida
        )
        VALUES(?,?,?,?,?,?,?,?,?,?,?)";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($asignacion_id, $info['vehiculo_id'], $reng_num, $info['conductor_id'],1,$info['tipo_transporte_id'],1,$creado_en,$creado_por,$info['tipo_vehiculo'],date('Y-m-d H:i:s', strtotime($info['fecha_salida']))));
        //fin
        $yes = array('msg'=>'OK','id'=>'','message'=>'Vehículo agregado a la comisión');
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

  static function getRandomString($tipo)
  {
    $random = self::generarCorrelativo();
    $validacion = false;
    $validacion = self::validarCorrelativo($random,$tipo);

    while($validacion == false){
      $random = self::generarCorrelativo();
      $validacion = self::validarCorrelativo($random,$tipo);
    }

    if($validacion == true){
      return $random;
    }else {
      return $validacion;
    }
  }

  static function generarCorrelativo(){
    $n = 3;
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $randomString .= $characters[$index];
    }
    for ($i = 0; $i < $n; $i++) {
      $index = rand(0, strlen($numbers) - 1);
      $randomString .= $numbers[$index];
    }

    return $randomString;
  }

  static function validarCorrelativo($aleatorio,$tipo){
    $pdo = Database::connect_sqlsrv();
    if($tipo == 1){
      //inicio
      $correlativo = '';
      $sqlcd = "SELECT solicitud_correlativo FROM TransporteSolicitud WHERE solicitud_correlativo = ?";
      $qcd = $pdo->prepare($sqlcd);
      $qcd->execute(array($aleatorio));
      $valor = $qcd->fetch();
      if($valor['solicitud_correlativo'] == $aleatorio){
        return false;
      }else{
        return true;
      }
      //fin
    }else if($tipo == 2){
      $correlativo = '';
      $sqlcd = "SELECT correlativo FROM TransporteAsignacion WHERE correlativo = ?";
      $qcd = $pdo->prepare($sqlcd);
      $qcd->execute(array($aleatorio));
      $valor = $qcd->fetch();
      if($valor['correlativo'] == $aleatorio){
        return false;
      }else{
        return true;
      }
    }



  }
  static function get_motivos()
  {
    $privilegios = self::getPrivilegios(2);

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_catalogo, id_item, id_status, descripcion
            FROM tbl_catalogo_detalle
            WHERE id_catalogo = ?";

    if($privilegios["comision_presidencial"] == true){
      $sql .= ' AND id_item IN (8596,8597,8598,8599,8600)';
    }else{
      $sql .= ' AND id_item NOT IN(8599)';
    }

    //$stmt = $pdo->prepare($sql);
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array(153))) {
      $items = array(
        "data" => $stmt->fetchAll(),
        "status" => 200,
        "msg" => "Ok"
      );
    } else {
      $items = array(
        "data" => null,
        "status" => 400,
        "msg" => "Error al ejecutar la consulta"
      );
    }

    $data = array();

    if($items["status"] == 200){
        $response = "";
        $sub_array = array(
          'id_item'=>'',
          'item_string'=>'- Seleccionar -'
        );
        $data[] = $sub_array;
        foreach($items["data"] as $i){
            //$response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
            $sub_array = array(
              'id_item'=>$i['id_item'],
              'item_string'=>$i['descripcion']
            );
            $data[] = $sub_array;
        }

    }else{
        $response = $items["msg"];
    }
    Database::disconnect_sqlsrv();
    echo json_encode($data);
  }
}


if (isset($_POST['opcion']) || isset($_GET['opcion'])) {

  $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];
  switch ($opcion) {
    case 0:
      Transporte::getPrivilegios($_GET['tipo']);
    break;
    case 1:
      Transporte::getTransportesList();
    break;
    case 2:
      Transporte::getHojaCertificacion();
    break;
    case 3:
      Transporte::crearSolicitudTransporte();
    break;
    case 4:
      Transporte::getSolicitudesList();
    break;
    case 5:
      Transporte::retornaDireccionEmpleado();
    break;
    case 6:
      Transporte::asignarVehiculosComision();
    break;
    case 7:
      Transporte::actualizarEstadoSolicitud();
    break;
    case 8:
      Transporte::getVehiculosBySolicitud();
    break;
    case 9:
      Transporte::setEstadoVehiculoSingular();
    break;
    case 10:
      Transporte::setEstadoVehiculoGrupal();
    break;
    case 11:
      Transporte::updateVehiculoSolicitud();
    break;
    case 12:
      Transporte::get_motivos();
    break;
  }
}
?>
