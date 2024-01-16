<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
class Inventario{

  public $inventario;
  public $opcion = 1;

  protected function __construct() {
      $this->examen = array();
  }

  static function getPrivilegios($tipo){
    $array = evaluar_flags_by_sistema($_SESSION['id_persona'],8687);

    $auditoria = ($array[0]['flag_es_menu'] == 1 && $array[0]['flag_acceso'] == 1) ? true : false;
    $verificar = ($array[0]['flag_es_menu'] == 1 && $array[0]['flag_actualizar'] == 1) ? true : false;
    $auditoria_verificar = ($auditoria == true && $verificar == true) ? true : false;

    $data = array(

      'asistente_direccion'=>($array[0]['flag_es_menu'] == 1) ? true : false,
      'director'=>($array[1]['flag_es_menu'] == 1) ? true : false,
      'inventario_asistente'=>($array[2]['flag_es_menu'] == 1) ? true : false,
      'inventario_encargado'=>($array[3]['flag_es_menu'] == 1) ? true : false,
      'inventario_jefe'=>($array[4]['flag_es_menu'] == 1) ? true : false,
      'financiero_director'=>($array[5]['flag_es_menu'] == 1) ? true : false,
      'financiero_subdirector'=>($array[6]['flag_es_menu'] == 1) ? true : false,

      'auditoria'=>$auditoria,
      'auditoria_verificar'=>$auditoria_verificar,
      'verificar'=>$verificar

    );

    if($tipo == 1){
      echo json_encode($data);
    }else if($tipo == 2){
      return $data;
    }
  }

  public static function getBienesList(){

    //echo $filtro;
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    //echo $row;
    $rowperpage = $_POST['length']; // Rows display per page
    //$columnIndex = $_POST['order'][0]['column']; // Column index
    //$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    //$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.bien_id, a.bien_sicoin_code, a.bien_nombre, a.bien_descripcion_completa,
                   a.bien_descripcion, a.bien_monto, a.bien_status, a.bien_status_movimiento,
                   a.bien_fecha_adquisicion, a.bien_fecha_creacion, a.bien_renglon_id, a.bien_ubicacion_id,
                   a.bien_tipo_id, b.verificacion_fecha, b.empleado,
                   c.bien_marca_t,c.bien_modelo,c.bien_descripcion,c.bien_chasis,c.bien_motor,c.bien_placa,
                   ROW_NUMBER() OVER (ORDER BY a.bien_id ASC) AS Rn
                   FROM APP_INVENTARIO.dbo.InventarioBien a
            LEFT JOIN (SELECT  T.verificacion_persona, T.verificacion_id, T.verificacion_fecha, T.bien_id, T.empleado
              FROM
               (
                 SELECT a.*,  ROW_NUMBER() OVER (PARTITION BY a.bien_id ORDER BY a.verificacion_id DESC) AS rnk,
                 ISNULL(b.primer_nombre,'')+' '+ISNULL(b.segundo_nombre,'')+' '+ISNULL(b.tercer_nombre,'')+' '+
                 ISNULL(b.primer_apellido,'')+' '+ISNULL(b.segundo_apellido,'')+' '+ISNULL(b.tercer_apellido,'') AS empleado
                 FROM APP_INVENTARIO.dbo.InventarioBienVerificacion a
                 INNER JOIN rrhh_persona b ON a.verificacion_persona = b.id_persona
               ) T
               WHERE T.rnk = 1
             ) AS b ON b.bien_id = a.bien_id
             LEFT JOIN APP_INVENTARIO.dbo.InventarioBienVehiculo AS c ON c.bien_id = a.bien_id


          ";

          $searchQuery = " ";
          if($searchValue != '' && strlen($searchValue) > 4 ){
             $searchQuery = " WHERE ( a.bien_sicoin_code  LIKE '%".$searchValue."%'
                              OR a.bien_descripcion_completa LIKE '%".$searchValue."%'
                              OR c.bien_placa LIKE '%".$searchValue."%' )";
                              /*OR a.bien_monto LIKE '%".$searchValue."%'
                              OR a.bien_fecha_adquisicion LIKE '%".$searchValue."%'
                              OR a.bien_renglon_id LIKE '%".$searchValue."%' ) ";*/
          }else{
            $sql.="ORDER BY bien_id DESC OFFSET $row ROWS FETCH NEXT $rowperpage ROWS ONLY OPTION (RECOMPILE)";
          }

          $sql.= $searchQuery;



    $p = $pdo->prepare($sql);
    $p->execute(array());
    $response = $p->fetchAll(PDO::FETCH_ASSOC);

    ## Total number of record with filtering
    $sql2="SELECT COUNT(*) AS total
           FROM APP_INVENTARIO.dbo.InventarioBien a
           LEFT JOIN APP_INVENTARIO.dbo.InventarioBienVehiculo c ON c.bien_id = a.bien_id
           $searchQuery";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array());
    $recordsF = $q2->fetch();
    $totalRecordwithFilter  = $recordsF['total'];


    $totalRecords = 0;
    $sql1="SELECT COUNT(*) AS total FROM APP_INVENTARIO.dbo.InventarioBien";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array());
    $records = $q1->fetch();
    $totalRecords = $records['total'];

    Database::disconnect_sqlsrv();
    $data = array();
    foreach ($response as $key => $b) {

      $menu = '<div class="dropdown">
                <button class="btn btn-light dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Acción
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="d-flex align-items-center py-2 px-3" onclick="" data-id="'.$b['bien_id'].'" data-type="'.$b['bien_sicoin_code'].'" id="infoInventarioD"><i class="fa fa-edit mr-3"></i> Detalle</a>
                  <a class="d-flex align-items-center py-2 px-3" onclick="" data-id="'.$b['bien_id'].'" data-type="'.$b['bien_sicoin_code'].'" id="infoInventarioEdit"><i class="fa fa-edit mr-3"></i> Editar</a>

                  <a class="d-flex align-items-center py-2 px-3" onclick="imprimirSticker('.$b['bien_id'].')"><i class="fa fa-print mr-3"></i> Código QR </a>
                </div>
              </div>';

      $placa = ($b['bien_renglon_id'] == 325) ? ' MARCA <bold>'.$b['bien_marca_t'].'</bold>, MODELO <bold>'.$b['bien_modelo'].'</bold>, COLOR <bold>'.$b['bien_descripcion'].'</bold> CHASIS <bold>'.$b['bien_chasis'].'</bold>, MOTOR <bold>'.$b['bien_motor'].'</bold>, PLACA <bold>'.$b['bien_placa'].'</bold>.' : '';

      $sub_array = array(
        //'DT_RowId'=>$b['bien_id'],
        'bien_id'=>$b['bien_id'],
        'bien_sicoin_code'=>$b['bien_sicoin_code'],
        //'bien_nombre'=>$b['bien_nombre'],
        'bien_descripcion'=>(!empty($b['bien_descripcion_completa'])) ? $b['bien_descripcion_completa'].$placa : $b['bien_descripcion'].$placa,
        'bien_monto'=>$b['bien_monto'],
        //'bien_status'=>$b['bien_status'],
        //'bien_status_movimiento'=>$b['bien_status_movimiento'],
        'bien_fecha_adquisicion'=>fecha_dmy($b['bien_fecha_adquisicion']),
        //'bien_fecha_creacion'=>$b['bien_fecha_creacion'],
        'bien_renglon_id'=>$b['bien_renglon_id'],
        //'bien_ubicacion_id'=>$b['bien_ubicacion_id'],
        //'bien_tipo_id'=>$b['bien_tipo_id'],
        'bien_verificacion'=> (!empty($b['empleado'])) ? fecha_dmy($b['verificacion_fecha']).' <br> '.$b['empleado'] : '',
        'bien_estado'=>'',

        //'arreglo'=>$arreglo,
        'accion'=>$menu,/*'<div class="btn-group"><span class="btn btn-sm btn-info" onclick="imprimirCertificacion('.$b['bien_id'].')"><i class="fa fa-print"></i></span>
        <span class="btn btn-sm btn-info" onclick="imprimirSticker('.$b['bien_id'].')"><i class="fa fa-print"></i></span>
        </div>'*/
      );
      $data[] = $sub_array;
    }
    $results = array(
      "draw" => intval($draw),
      //"sEcho" => 1,
      "iTotalRecords" => $totalRecords,//count($data),
      "iTotalDisplayRecords" => $totalRecordwithFilter,//count($data),
      "aaData"=>$data
    );

    echo json_encode($results);
  }

  static function getHojaCertificacion(){


    $tipo = $_POST['tipo'];
    if($tipo == 1){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql1 = "SELECT a.certificacion_id, b.bienes, CONVERT(VARCHAR, a.certificacion_fecha,23) AS fecha_certificacion, c.descripcion AS direccion,
                d.descripcion AS departamento,
                ISNULL(e.primer_nombre,'')+' '+ISNULL(e.segundo_nombre,'')+' '+ISNULL(e.tercer_nombre,'')+' '+ISNULL(e.primer_apellido,'')+' '+ISNULL(e.segundo_apellido,'')+' '+ISNULL(e.tercer_apellido,'') AS solicitante,
                ISNULL(f.primer_nombre,'')+' '+ISNULL(f.segundo_nombre,'')+' '+ISNULL(f.tercer_nombre,'')+' '+ISNULL(f.primer_apellido,'')+' '+ISNULL(f.segundo_apellido,'')+' '+ISNULL(f.tercer_apellido,'') AS generador,
                a.certificacion_creador_tipo,a.certificacion_status
                FROM APP_INVENTARIO.dbo.InventarioCertificacion a
                LEFT JOIN (
                	SELECT b.certificacion_id, STRING_AGG (CONVERT(NVARCHAR(max),c.bien_sicoin_code)+',', CHAR(13)) AS bienes
                	FROM APP_INVENTARIO.dbo.InventarioCertificacionDetalle b
                	INNER JOIN APP_INVENTARIO.dbo.InventarioBien c ON b.bien_id = c.bien_id
                	GROUP BY b.certificacion_id
                	) AS b ON b.certificacion_id = a.certificacion_id
                INNER JOIN rrhh_direcciones c ON a.direccion_id = c.id_direccion
                LEFT JOIN rrhh_departamentos d ON a.departamento_id = d.id_departamento
                INNER JOIN rrhh_persona e ON a.certificacion_solicitante = e.id_persona
                INNER JOIN rrhh_persona f ON a.certificacion_creada_por = f.id_persona
                WHERE a.certificacion_id = ?
                ORDER BY certificacion_id DESC";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(array(
        $_POST['certificacion_id']
      ));
      $cert = $q1->fetch();

      $sql = "SELECT
            	b.bien_id,
            	b.bien_sicoin_code,
            	b.bien_nombre,
              b.bien_descripcion,
            	b.bien_descripcion_completa,
            	b.bien_monto,
            	b.bien_status,
            	b.bien_status_movimiento,
            	b.bien_fecha_adquisicion,
            	b.bien_fecha_creacion,
            	b.bien_renglon_id,
            	b.bien_ubicacion_id,
            	b.bien_tipo_id,
              REPLACE(STR(a.correlativo, 5), SPACE(1), '0') AS correlativo,
              a.year,
            	CONVERT(VARCHAR,c.certificacion_fecha,23) AS fecha,
            	c.certificacion_creador_tipo
              FROM APP_INVENTARIO.dbo.InventarioCertificacionDetalle a
              INNER JOIN APP_INVENTARIO.dbo.InventarioBien b ON a.bien_id = b.bien_id
              INNER JOIN APP_INVENTARIO.dbo.InventarioCertificacion c ON a.certificacion_id = c.certificacion_id
              WHERE c.certificacion_id = ?";
      $p = $pdo->prepare($sql);
      $p->execute(array($_POST['certificacion_id']));

      $certificaciones = $p->fetchAll();
      Database::disconnect_sqlsrv();
      $hojas = array();

      foreach ($certificaciones as $key => $response) {
        //inicio
        $p = self::retornaTipoGeneradorCert($response['certificacion_creador_tipo']);
        $persona = $p['tipo'];

        $string = $response['bien_descripcion_completa'];


        $partirString = explode('(',$string);
        $cleanString = trim(preg_replace('/\s+/', ' ',$partirString[0]));
        $partDetalle = substr($partirString[1], 0, -1);


        $x = 0;
        foreach ($partirString as $key => $value) {
          // code...
          //echo $x.' -- -- '. $value;
          $x = $key;
        }

        $detalle = explode(',',$partirString[$x]);

        $marca = (!empty($detalle[0])) ? $detalle[0] : 'S/M';
        $modelo = (strlen($detalle[1]) > 1) ? $detalle[1] : 'S/M';
        $serie = (strlen($detalle[2]) > 2) ? substr($detalle[2], 0, -1) : 'S/S';

        $modelo = ($modelo[0] == ' ') ? substr($modelo, 1) : $modelo;
        $serie = ($serie[0] == ' ') ? substr($serie, 1) : $serie;

        $clean_string = $response['bien_descripcion'].' MARCA |'.$marca.',| MODELO |'.$modelo.',| SERIE |'.$serie.'|, NUMERO DE BIEN |'.$response['bien_sicoin_code'].'|.';//json_encode($partirString),
        $anotacionV = 'LA PRESENTE CERTIFICACIÓN TIENE VIGENCIA HASTA EL TREINTA Y UNO (31) DE DICIEMBRE DEL PRESENTE AÑO.';
        if($response['bien_renglon_id'] == 325){
          //inicio
          $sql = "  SELECT * FROM APP_INVENTARIO.dbo.InventarioBienVehiculo WHERE bien_id = ?";
          $p = $pdo->prepare($sql);
          $p->execute(array($response['bien_id']));
          $v = $p->fetch(PDO::FETCH_ASSOC);

          $clean_string = $response['bien_descripcion'].' MARCA |'.$v['bien_marca_t'].'|, MODELO |'.$v['bien_modelo'].'|, COLOR |'.$v['bien_descripcion'].'| CHASIS |'.$v['bien_chasis'].'|, MOTOR |'.$v['bien_motor'].'|, PLACA |'.$v['bien_placa'].'|, NUMERO DE INVENTARIO |'.$response['bien_sicoin_code'].'|.';

          //fin
        }
        //$clean_string.='<br>'.'Y PARA LOS USOS QUE AL INTERESADO  CONVENGA SE EXTIENDE LA PRESENTE CERTIFICACIÓN A LOS '. strtoupper(fechaATexto(date('d/m/Y')));
        //inicio
        $sub_array = array(
          'DT_RowId'=>$response['bien_id'],
          'bien_id'=>$response['bien_id'],
          'correlativo'=>$response['correlativo'],
          'year'=>$response['year'],
          'bien_sicoin_code'=>$response['bien_sicoin_code'],
          'bien_nombre'=>$response['bien_nombre'],
          'bien_descripcion'=>$partirString[0],//.' MARCA ||'.$marca.',|| MODELO ||'.$modelo.',|| SERIE ||'.$serie.'||, NUMERO DE BIEN ||'.$response['bien_sicoin_code'].'||.',//json_encode($partirString),,
          'bien_descripcion_completa'=>$response['bien_descripcion_completa'],
          'bien_monto'=>$response['bien_monto'],
          'bien_status'=>$response['bien_status'],
          'bien_status_movimiento'=>$response['bien_status_movimiento'],
          'bien_fecha_adquisicion'=>$response['bien_fecha_adquisicion'],
          'bien_fecha_creacion'=>$response['bien_fecha_creacion'],
          'bien_renglon_id'=>$response['bien_renglon_id'],
          'bien_ubicacion_id'=>$response['bien_ubicacion_id'],
          'bien_tipo_id'=>$response['bien_tipo_id'],
          'marca'=>$marca,
          'modelo'=>($modelo[0] == ' ') ? substr($modelo, 1) : $modelo ,//(strlen($detalle[1]) > 1) ? $detalle[1] : 'S/M',
          'serie'=>($serie[0] == ' ') ? substr($serie, 1) : $serie ,//(strlen($detalle[2]) > 2) ? $detalle[2] : 'S/S',
          'clean_string'=>$clean_string,
          'texto_'=>$response['bien_descripcion'].' MARCA ||'.$marca.',|| MODELO ||'.$modelo.',|| SERIE ||'.$serie.'||, NUMERO DE BIEN ||'.$response['bien_sicoin_code'].'||.',//json_encode($partirString),
          'accion'=>'',
          'partes'=>json_encode($partirString),
          'fecha_certificacion'=>'Y PARA LOS USOS QUE AL INTERESADO  CONVENGA SE EXTIENDE LA PRESENTE CERTIFICACIÓN A LOS '. strtoupper(fechaATextoNumeroIncluido(date('d/m/Y', strtotime($response['fecha'])))),
          'encargado'=>$persona,
          'anotacionV'=>$anotacionV

        );

        $hojas[] = $sub_array;

        //fin
      }

      $data = array(
        'certificacion_id'=>$cert['certificacion_id'],
        'fecha_certificacion'=>$cert['fecha_certificacion'],
        'direccion'=>$cert['direccion'],
        'departamento'=>$cert['departamento'],
        'solicitante'=>$cert['solicitante'],
        'certificaciones'=>$hojas
      );
      echo json_encode($data);



      /*$sql = "SELECT id_genero FROM rrhh_persona WHERE id_persona = ?";
      $p = $pdo->prepare($sql);
      $p->execute(array($_SESSION['id_persona']));

      $genero = $p->fetch(PDO::FETCH_ASSOC);

      $priv = self::getPrivilegios(2);
      $persona = json_encode($priv);
      if($priv['inventario_asistente'] == true){
        if($genero['id_genero'] == 818){
          $persona = 'EL INFRASCRITO ASISTENTE';
        }else if($genero['id_genero'] == 819){
          $persona = 'LA INFRASCRITA ASISTENTE';
        }
      }else
      if($priv['inventario_encargado'] == true){
        if($genero['id_genero'] == 818){
          $persona = 'EL INFRASCRITO ENCARGADO';
        }else if($genero['id_genero'] == 819){
          $persona = 'LA INFRASCRITA ENCARGADA';
        }
      }else
      if($priv['inventario_jefe'] == true){
        if($genero['id_genero'] == 818){
          $persona = 'EL INFRASCRITO JEFE';
        }else if($genero['id_genero'] == 819){
          $persona = 'LA INFRASCRITA JEFA';
        }
      }*/


      //fin
    }else if($tipo == 2){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "  SELECT * FROM APP_INVENTARIO.dbo.InventarioBien WHERE bien_id = ?";
      $p = $pdo->prepare($sql);
      $p->execute(array($_POST['bien_id']));
      $response = $p->fetch(PDO::FETCH_ASSOC);

      $data = array(
        'bien_sicoin_code'=>$response['bien_sicoin_code'],
        'url_code'=>$response['bien_sicoin_code'],
      );

      echo json_encode($data);
    }



  }

  //opcion 3
  public static function getBienById(){

    //echo $filtro;
    $bien_id = $_GET['bien_id'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.bien_id, a.bien_sicoin_code, a.bien_nombre, a.bien_descripcion_completa,
                   a.bien_descripcion, a.bien_monto, a.bien_status, a.bien_status_movimiento,
                   a.bien_fecha_adquisicion, a.bien_fecha_creacion, a.bien_renglon_id, a.bien_ubicacion_id,
                   a.bien_tipo_id, b.empleado, b.verificacion_fecha, b.lugarNombre, b.ubicacionNombre, b.verificacion_bien_estado,
				   c.bien_id, c.bien_placa, c.bien_modelo, c.bien_chasis, c.bien_motor, c.bien_marca_t, c.bien_descripcion AS color
                   FROM APP_INVENTARIO.dbo.InventarioBien a
            LEFT JOIN (
              SELECT  T.verificacion_persona, T.verificacion_id, T.verificacion_fecha, T.bien_id, T.empleado,
              T.lugarNombre, T.ubicacionNombre, T.verificacion_bien_estado
              FROM
               (
                 SELECT a.verificacion_id, a.verificacion_fecha, a.verificacion_persona, a.bien_id, ROW_NUMBER() OVER (PARTITION BY a.bien_id ORDER BY a.verificacion_id DESC) AS rnk,
                 ISNULL(b.primer_nombre,'')+' '+ISNULL(b.segundo_nombre,'')+' '+ISNULL(b.tercer_nombre,'')+' '+
                 ISNULL(b.primer_apellido,'')+' '+ISNULL(b.segundo_apellido,'')+' '+ISNULL(b.tercer_apellido,'') AS empleado,
                 c.lugarNombre, d.ubicacionNombre, a.verificacion_bien_estado

                 FROM APP_INVENTARIO.dbo.InventarioBienVerificacion a
                 INNER JOIN rrhh_persona b ON a.verificacion_persona = b.id_persona
                 LEFT JOIN APP_INVENTARIO.dbo.LugarBien c ON a.verificacion_lugar = c.lugarId
                 LEFT JOIN APP_INVENTARIO.dbo.UbicacionLugar d ON c.ubicacionId = d.ubicacionId
               ) T
               WHERE T.rnk = 1
             ) AS b ON b.bien_id = a.bien_id
             LEFT JOIN APP_INVENTARIO.dbo.InventarioBienVehiculo c ON c.bien_id = a.bien_id
             WHERE a.bien_id = ?
          ";
    $p = $pdo->prepare($sql);
    $p->execute(array($bien_id));
    $b = $p->fetch(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();

    $p = self::getPrivilegios(2);

    $estado = '';
    if(!empty($b['verificacion_bien_estado'])){
      $state = self::retornaEstadoBien($b['verificacion_bien_estado']);
      $estado = $state['estado'];
    }


    $data = array(
      'bien_id'=>$b['bien_id'],
      'bien_sicoin_code'=>$b['bien_sicoin_code'],
      'bien_nombre'=>$b['bien_nombre'],
      'bien_descripcion'=>$b['bien_descripcion'],(!empty($b['bien_descripcion_completa'])) ? $b['bien_descripcion_completa'] : $b['bien_descripcion'],
      'bien_descripcion_completa'=>$b['bien_descripcion_completa'],
      'bien_monto'=>$b['bien_monto'],
      'bien_status'=>$b['bien_status'],
      'bien_status_movimiento'=>$b['bien_status_movimiento'],
      'bien_fecha_adquisicion'=>fecha_dmy($b['bien_fecha_adquisicion']),
      'bien_fecha_creacion'=>$b['bien_fecha_creacion'],
      'bien_renglon_id'=>$b['bien_renglon_id'],
      'bien_ubicacion_id'=>$b['bien_ubicacion_id'],
      'bien_tipo_id'=>$b['bien_tipo_id'],
      'bien_verificacion'=> (!empty($b['empleado'])) ? fecha_dmy($b['verificacion_fecha']).' <br> '.$b['empleado'].' <br> '.$estado.' <br> '.$b['lugarNombre'].' - '. $b['ubicacionNombre']: 'No se ha verificado.',
      'bien_placa'=>$b['bien_placa'],
      'bien_modelo'=>$b['bien_modelo'],
      'bien_chasis'=>$b['bien_chasis'],
      'bien_motor'=>$b['bien_motor'],
      'bien_marca_t'=>$b['bien_marca_t'],
      'color'=>$b['color'],
      'privilegios'=>$p

    );

    echo json_encode($data);
  }

  //opcion 4
  public static function verificarBien(){

    //echo $filtro;
    $fecha = date('Y-m-d H:i:s');
    $bien_id = $_POST['bien_id'];
    $id_persona = $_POST['id_persona'];
    $estado_id = $_POST['estado_id'];
    $lugar_id = $_POST['lugar_id'];

    $yes='';
    $pdo = Database::connect_sqlsrv();
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = "INSERT INTO APP_INVENTARIO.dbo.InventarioBienVerificacion (verificacion_fecha, verificacion_tipo,
              verificacion_lugar, verificacion_persona, verificacion_status, bien_id, verificacion_bien_estado)
              VALUES (?,?,?,?,?,?,?)
            ";
      $p = $pdo->prepare($sql);
      $p->execute(array($fecha, 1, $lugar_id, $id_persona, 1, $bien_id, $estado_id));

      $yes = array('msg'=>'OK','message'=>'Bien verificado');
      //echo json_encode($yes);
       $pdo->commit();

    }catch (PDOException $e){

      $yes = array('msg'=>'ERROR','id'=>$e);
      //echo json_encode($yes);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','id'=>$e2);
      }
    }
    echo json_encode($yes);
  }

  //opcion 5
  public static function getEstadoBienList(){
    $data = array();

    $sub_array = array('id_item' => '', 'item_string' => '-- Seleccionar --' );
    $data[] = $sub_array;

    $sub_array = array('id_item' => 1, 'item_string' => 'RESGUARDO' );
    $data[] = $sub_array;

    $sub_array = array('id_item' => 2, 'item_string' => 'ALMACENADO' );
    $data[] = $sub_array;

    $sub_array = array('id_item' => 3, 'item_string' => 'BAJA' );
    $data[] = $sub_array;

    echo json_encode($data);
  }

  //opcion 6
  public static function getUbicacionesList(){
    //inicio
    $tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : $_GET['tipo'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.lugarId, a.lugarEstado, a.lugarNombre, a.ubicacionId,
                   b.ubicacionNombre, b.ubicacionDetalle, b.ubicacionEstado
            FROM APP_INVENTARIO.dbo.LugarBien a
            INNER JOIN APP_INVENTARIO.dbo.UbicacionLugar b ON a.ubicacionId = b.ubicacionId
          ";
    $p = $pdo->prepare($sql);
    $p->execute(array());
    $lugares = $p->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();

    $p = self::getPrivilegios(2);
    $data = array();
    if($tipo == 2){
      $sub_array = array(
        'id_item'=>'',
        'item_string'=>'-- Seleccionar --',
      );
      $data[] = $sub_array;
    }
    foreach ($lugares as $key => $l) {
      // code...
      $sub_array = array(
        'id_item'=>$l['lugarId'],
        'item_string'=>$l['lugarNombre'].' - '.$l['ubicacionNombre'],
        'lugarId'=>$l['lugarId'],
        'lugarEstado'=>$l['lugarEstado'],
        'lugarNombre'=>$l['lugarNombre'],
        'ubicacionId'=>$l['ubicacionId'],
        'ubicacionNombre'=>$l['ubicacionNombre'],
        'ubicacionDetalle'=>$l['ubicacionDetalle'],
        'ubicacionEstado'=>$l['ubicacionEstado'],
      );

      $data[] = $sub_array;
    }

    if($tipo == 1){
      echo json_encode($data);
    }else if($tipo == 2){
      echo json_encode($data);
    }

    //fin
  }

  //opcion 7
  static function retornaEstadoBien($valor){
    $arreglo = array(1 => 'RESGUARDO', 2 => 'ALMACENADO', 3 => 'BAJA', 4 => 'TRASLADO');

    $array = array(
      'estado'=> $arreglo[$valor],
    );
    return $array;
  }

  static function retornaEstadoCertificacion($valor){
    $estado = array(1 => 'GENERADO', 2 => 'ENTREGADO');
    $color = array(1 => 'info', 2 => 'success');
    $array = array(
      'estado'=> $estado[$valor],
      'color'=>$color[$valor]
    );
    return $array;
  }

  static function retornaTipoGeneradorCert($valor){
    $tipo = array(
      1 => 'EL INFRASCRITO ASISTENTE',
      2 => 'LA INFRASCRITA ASISTENTE',
      3 => 'EL INFRASCRITO ENCARGADO',
      4 => 'LA INFRASCRITA ENCARGADA',
      5 => 'EL INFRASCRITO JEFE',
      6 => 'LA INFRASCRITA JEFA',
    );

    $array = array(
      'tipo'=>$tipo[$valor]
    );

    return $array;
  }


  //opcion 8
  static function generaCertificacion(){
    //inicio
    $direccion_id = $_POST['id_direccion'];
    $departamento_id = $_POST['id_departamento'];
    $id_empleado = $_POST['id_empleado'];
    $fecha_solicitud = date('Y-m-d H:i:s', strtotime($_POST['fecha_solicitud']));
    $fecha_certificacion = date('Y-m-d H:i:s', strtotime($_POST['fecha_certificacion']));
    $chck_baja_cuantía = $_POST['chck_baja_cuantía'];
    $bienes = $_POST['filter_bienes'];

    $modalidad = ($chck_baja_cuantía == 'on') ? 1 : 0;

    $fecha = date('Y-m-d H:i:s', strtotime($_POST['fecha_solicitud']));
    $creado_en = date('Y-m-d H:i:s');
    $creado_por = $_SESSION['id_persona'];

    $yes='';
    $pdo = Database::connect_sqlsrv();
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = "SELECT id_genero FROM rrhh_persona WHERE id_persona = ?";
      $p = $pdo->prepare($sql);
      $p->execute(array($_SESSION['id_persona']));

      $genero = $p->fetch(PDO::FETCH_ASSOC);

      $priv = self::getPrivilegios(2);
      $persona = json_encode($priv);
      if($priv['inventario_asistente'] == true){
        if($genero['id_genero'] == 818){
          $persona = 1; //'EL INFRASCRITO ASISTENTE';
        }else if($genero['id_genero'] == 819){
          $persona = 2; //'LA INFRASCRITA ASISTENTE';
        }
      }else
      if($priv['inventario_encargado'] == true){
        if($genero['id_genero'] == 818){
          $persona = 3;//'EL INFRASCRITO ENCARGADO';
        }else if($genero['id_genero'] == 819){
          $persona = 4;//'LA INFRASCRITA ENCARGADA';
        }
      }else
      if($priv['inventario_jefe'] == true){
        if($genero['id_genero'] == 818){
          $persona = 5;//'EL INFRASCRITO JEFE';
        }else if($genero['id_genero'] == 819){
          $persona = 6;//'LA INFRASCRITA JEFA';
        }
      }


      $sql = "INSERT INTO APP_INVENTARIO.dbo.InventarioCertificacion (

        certificacion_fecha,
        certificacion_fecha_real,
        certificacion_tipo_uso,
        certificacion_tipo_entrega,
        certificacion_creada_en,
        certificacion_creada_por,
        certificacion_creador_tipo,
        direccion_id,
        departamento_id,
        certificacion_motivo,
        certificacion_modalidad_pago,
        certificacion_solicitante,
        certificacion_fecha_solicitado,
        certificacion_status


      )
              VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ";
      $p = $pdo->prepare($sql);
      $p->execute(array($fecha_certificacion,$creado_en,NULL,NULL,$creado_en,$creado_por,$persona,$direccion_id,$departamento_id,NULL,$modalidad,$id_empleado,$fecha_solicitud,1));

      $cgid = $pdo->lastInsertId();

      foreach ($bienes as $value) {
        // code...

        $year = date('Y');
        $sql1 = "SELECT TOP 1 correlativo FROM APP_INVENTARIO.dbo.InventarioCertificacionDetalle WHERE year = ? ORDER BY correlativo DESC";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array(
          $year
        ));
        $numero = $q1->fetch();

        $correlativo = 1;
        if(!empty($numero['correlativo'])){
          $correlativo += $numero['correlativo'];
        }

        $sql1 = "INSERT INTO APP_INVENTARIO.dbo.InventarioCertificacionDetalle
        (
          certificacion_id,
          bien_id,
          year,
          correlativo,
          generado_en,
          generado_por,
          certificacion_status,
          folio_ini,
          folio_fin
        )
        VALUES (?,?,?,?,?,?,?,?,?)
        ";
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array(
          $cgid,
          $value,
          $year,
          $correlativo,
          $creado_en,
          $creado_por,
          1,
          NULL,
          NULL
        ));

      }

      $tBienes = count($bienes);

      $message = ($tBienes == 1) ? 'Bien certificado' : 'Bienes certificados';

      $yes = array('msg'=>'OK','message'=>$message);
      //echo json_encode($yes);
       $pdo->commit();

    }catch (PDOException $e){

      $yes = array('msg'=>'ERROR','message'=>$e);
      //echo json_encode($yes);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','message'=>$e2);
      }
    }
    echo json_encode($yes);
    //fin
  }

  //opcion 9
  static function getCertificaciones(){

    $year = $_POST['year'];
    $tipo = $_POST['tipo'];
    $pdo = Database::connect_sqlsrv();
    $sql1 = "SELECT a.certificacion_id, b.bienes, CONVERT(VARCHAR, a.certificacion_fecha,23) AS fecha_certificacion, c.descripcion AS direccion,
              d.descripcion AS departamento,
              ISNULL(e.primer_nombre,'')+' '+ISNULL(e.segundo_nombre,'')+' '+ISNULL(e.tercer_nombre,'')+' '+ISNULL(e.primer_apellido,'')+' '+ISNULL(e.segundo_apellido,'')+' '+ISNULL(e.tercer_apellido,'') AS solicitante,
              ISNULL(f.primer_nombre,'')+' '+ISNULL(f.segundo_nombre,'')+' '+ISNULL(f.tercer_nombre,'')+' '+ISNULL(f.primer_apellido,'')+' '+ISNULL(f.segundo_apellido,'')+' '+ISNULL(f.tercer_apellido,'') AS generador,
              a.certificacion_creador_tipo,a.certificacion_status, a.direccion_id
              FROM APP_INVENTARIO.dbo.InventarioCertificacion a
              LEFT JOIN (
              	SELECT b.certificacion_id, STRING_AGG (CONVERT(NVARCHAR(max),c.bien_sicoin_code)+',', CHAR(13)) AS bienes
              	FROM APP_INVENTARIO.dbo.InventarioCertificacionDetalle b
              	INNER JOIN APP_INVENTARIO.dbo.InventarioBien c ON b.bien_id = c.bien_id
              	GROUP BY b.certificacion_id
              	) AS b ON b.certificacion_id = a.certificacion_id
              INNER JOIN rrhh_direcciones c ON a.direccion_id = c.id_direccion
              LEFT JOIN rrhh_departamentos d ON a.departamento_id = d.id_departamento
              INNER JOIN rrhh_persona e ON a.certificacion_solicitante = e.id_persona
              INNER JOIN rrhh_persona f ON a.certificacion_creada_por = f.id_persona
              WHERE YEAR(a.certificacion_fecha) = ? AND a.certificacion_status = ?
              ";
              $sql1 .= ($tipo == 1) ? " ORDER BY a.certificacion_id DESC " : " ORDER BY a.certificacion_entregado_en DESC ";
    $q1 = $pdo->prepare($sql1);
    $q1->execute(array(
      $year, $tipo
    ));
    $certificaciones = $q1->fetchAll();
    $data = array();

    foreach ($certificaciones as $key => $c) {
      // code...
      $state = self::retornaEstadoCertificacion($c['certificacion_status']);
      $generador = self::retornaTipoGeneradorCert($c['certificacion_creador_tipo']);
      $entrega = ($tipo == 1) ? '<a class="d-flex align-items-center py-2 px-3 link-muted" id="idEntregar" data-id="'.$c['certificacion_id'].'" data-type="'.$c['direccion_id'].'"><i class="fa-regular fa-arrow-right-to-arc mr-3"></i> Entregar</a>' : '';
      $menu = '<div class="dropdown">
                <button class="btn btn-light dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Acción
                </button>
                <div class="dropdown-menu dropdown-menu-right border-0 py-0" aria-labelledby="dropdownMenuButton">
                  '.$entrega.'
                  <a class="d-flex align-items-center py-2 px-3 link-muted" onclick="imprimirCertificacion('.$c['certificacion_id'].')"><i class="fa fa-print mr-3"></i> Certificación</a>
                </div>
              </div>';
      $sub_array = array(
        'certificacion_id'=>$c['certificacion_id'],
        'bienes'=>$c['bienes'],
        'fecha_certificacion'=>fecha_dmy($c['fecha_certificacion']),
        'direccion'=>$c['direccion'],
        'departamento'=>$c['departamento'],
        'solicitante'=>$c['solicitante'],
        'generador'=>$c['generador'],
        'generador_tipo'=>$generador['tipo'],
        'certificacion_status'=>'<span class="text-'.$state['color'].'"><i class="fa fa-check-circle"></i> '.$state['estado'].'</span>',
        'accion'=>$menu
      );

      $data[] = $sub_array;
    }

    $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData"=>$data
    );

    echo json_encode($results);
  }

  static function filterLiveBienes(){
    $response = array();
    $filtro = $_GET['q'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.bien_id, a.bien_sicoin_code
             FROM APP_INVENTARIO.dbo.InventarioBien a
             WHERE ISNULL(a.bien_status,1)=? AND a.bien_sicoin_code LIKE '%".$_GET['q']."%'
             ORDER BY a.bien_id ASC
            ";
    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array(1));
    $bienes = $q0->fetchAll();

    $data = array();

    foreach($bienes as $b){
       $data[] = ['id'=>$b['bien_id'], 'text'=>$b['bien_sicoin_code']];
     }

     echo json_encode($data);
  }

  public static function getBienesSeleccionados(){
    $data = array();
    if(isset($_GET['bienes'])){
      //inicio

      //echo $_GET['bienes'];
      //if(!empty($_GET['bienes'])){
        $comillas = str_replace('"', '', $_GET['bienes']);
        $corchete1 = str_replace('[', '', $comillas);
        $corchete2 = str_replace(']', '', $corchete1);

        $bienes = "(".$corchete2.")";

        //echo $filtro;
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT a.bien_id, a.bien_sicoin_code, a.bien_nombre, a.bien_descripcion_completa,
                       a.bien_descripcion, a.bien_monto, a.bien_status, a.bien_status_movimiento,
                       a.bien_fecha_adquisicion, a.bien_fecha_creacion, a.bien_renglon_id, a.bien_ubicacion_id,
                       a.bien_tipo_id
                       FROM APP_INVENTARIO.dbo.InventarioBien a
                       WHERE a.bien_id IN $bienes
              ";
        $p = $pdo->prepare($sql);
        $p->execute(array());
        $response = $p->fetchAll(PDO::FETCH_ASSOC);

        Database::disconnect_sqlsrv();
        $data = array();
        foreach ($response as $key => $b) {
          //inicio
          $string = $b['bien_descripcion_completa'];


          $partirString = explode('(',$string);
          $cleanString = trim(preg_replace('/\s+/', ' ',$partirString[0]));
          $partDetalle = substr($partirString[1], 0, -1);


          $x = 0;
          foreach ($partirString as $key => $value) {
            // code...
            //echo $x.' -- -- '. $value;
            $x = $key;
          }

          $detalle = explode(',',$partirString[$x]);

          $marca = (!empty($detalle[0])) ? $detalle[0] : 'S/M';
          $modelo = (strlen($detalle[1]) > 1) ? $detalle[1] : 'S/M';
          $serie = (strlen($detalle[2]) > 2) ? substr($detalle[2], 0, -1) : 'S/S';

          $modelo = ($modelo[0] == ' ') ? substr($modelo, 1) : $modelo;
          $serie = ($serie[0] == ' ') ? substr($serie, 1) : $serie;

          $clean_string = $b['bien_descripcion'].' MARCA <b>'.$marca.'</b>, MODELO <b>'.$modelo.'</b> SERIE <b>'.$serie.'</b>, NUMERO DE BIEN <b>'.$b['bien_sicoin_code'].'</b>.';//json_encode($partirString),
          $anotacionV = 'LA PRESENTE CERTIFICACIÓN TIENE VIGENCIA HASTA EL TREINTA Y UNO (31) DE DICIEMBRE DEL PRESENTE AÑO.';
          if($b['bien_renglon_id'] == 325){
            //inicio
            $sql = "  SELECT * FROM APP_INVENTARIO.dbo.InventarioBienVehiculo WHERE bien_id = ?";
            $p = $pdo->prepare($sql);
            $p->execute(array($b['bien_id']));
            $v = $p->fetch(PDO::FETCH_ASSOC);

            $clean_string = $b['bien_descripcion'].' MARCA <b>'.$v['bien_marca_t'].'</b>, MODELO <b>'.$v['bien_modelo'].'</b>, COLOR <b>'.$v['bien_descripcion'].'</b> CHASIS <b>'.$v['bien_chasis'].'</b>, MOTOR <b>'.$v['bien_motor'].'</b>, PLACA <b>'.$v['bien_placa'].'</b>, NUMERO DE INVENTARIO <b>'.$b['bien_sicoin_code'].'</b>.';

            //fin
          }
          //fin
          $sub_array = array(
            'DT_RowId'=>$b['bien_id'],
            'bien_id'=>$b['bien_id'],
            'bien_sicoin_code'=>$b['bien_sicoin_code'],
            'bien_descripcion'=>$clean_string,//(!empty($b['bien_descripcion_completa'])) ? $b['bien_descripcion_completa'] : $b['bien_descripcion'],
            'bien_monto'=>$b['bien_monto'],
            'bien_status'=>$b['bien_status'],
            'bien_fecha_adquisicion'=>fecha_dmy($b['bien_fecha_adquisicion']),
            'bien_renglon_id'=>$b['bien_renglon_id'],
          );
          $data[] = $sub_array;
        }
      //}

      echo json_encode($data);
      //fin
    }else{
      echo json_encode($data);
    }

  }

  //opcion 12
  static function entregaCertificacion(){
    //inicio
    $certificacion_id = $_POST['certificacion_id'];
    $id_empleado = $_POST['id_empleado'];
    $fecha_entrega = date('Y-m-d H:i:s', strtotime($_POST['fecha_entrega']));

    $fecha = date('Y-m-d H:i:s', strtotime($_POST['fecha_entrega']));
    $creado_en = date('Y-m-d H:i:s');
    $creado_por = $_SESSION['id_persona'];

    $yes='';
    $pdo = Database::connect_sqlsrv();
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


      $sql = "UPDATE APP_INVENTARIO.dbo.InventarioCertificacion
              SET certificacion_entregado_por = ?, certificacion_entregado_en = ?, certificacion_recibido_por = ?,
              certificacion_status = ?
              WHERE certificacion_id = ?

            ";
      $p = $pdo->prepare($sql);
      $p->execute(array($creado_por,$creado_en, $id_empleado, 2, $certificacion_id));

      $sql = "SELECT COUNT(certificacion_id) AS conteo FROM APP_INVENTARIO.dbo.InventarioCertificacionDetalle WHERE certificacion_id = ?";
      $p = $pdo->prepare($sql);
      $p->execute(array($certificacion_id));

      $conteo = $p->fetch(PDO::FETCH_ASSOC);


      $message = ($conteo['conteo'] == 1) ? 'Certificado entregado' : 'Certificados entregados';

      $yes = array('msg'=>'OK','message'=>$message);
      //echo json_encode($yes);
       $pdo->commit();

    }catch (PDOException $e){

      $yes = array('msg'=>'ERROR','message'=>$e);
      //echo json_encode($yes);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','message'=>$e2);
      }
    }
    echo json_encode($yes);
    //fin
  }

  //opcion 13
  static function actualizarDescripcionBien(){
    //inicio
    $bien_id = $_POST['bien_id'];
    $descripcion = $_POST['descripcion'];
    //$fecha_entrega = date('Y-m-d H:i:s', strtotime($_POST['fecha_entrega']));

    $yes='';
    $pdo = Database::connect_sqlsrv();
    try{
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = "SELECT bien_descripcion, bien_renglon_id FROM APP_INVENTARIO.dbo.InventarioBien WHERE bien_id = ?";
      $p = $pdo->prepare($sql);
      $p->execute(array($bien_id));
      $valor_ant = $p->fetch();

      $sql = "UPDATE APP_INVENTARIO.dbo.InventarioBien
              SET bien_descripcion = ?
              WHERE bien_id = ?

            ";
            $p = $pdo->prepare($sql);
            $p->execute(array($descripcion, $bien_id));


      if($valor_ant['bien_renglon_id'] == 325){
        $bien_placa = (!empty($_POST['bien_placa'])) ? $_POST['bien_placa'] : NULL;
        $bien_modelo = (!empty($_POST['bien_modelo'])) ? $_POST['bien_modelo'] : NULL;
        $bien_chasis = (!empty($_POST['bien_chasis'])) ? $_POST['bien_chasis'] : NULL;
        $bien_motor = (!empty($_POST['bien_motor'])) ? $_POST['bien_motor'] : NULL;
        $bien_marca_t = (!empty($_POST['bien_marca_t'])) ? $_POST['bien_marca_t'] : NULL;
        $color = (!empty($_POST['color'])) ? $_POST['color'] : NULL;

        $sql = "UPDATE APP_INVENTARIO.dbo.InventarioBienVehiculo
                SET bien_placa = ?,
                bien_modelo = ?,
                bien_chasis = ?,
                bien_motor = ?,
                bien_marca_t = ?,
                bien_descripcion = ?,
                fecha_modificacion = ?
                WHERE bien_id = ?

              ";
              $p = $pdo->prepare($sql);
              $p->execute(array($bien_placa,$bien_modelo,$bien_chasis,$bien_motor,$bien_marca_t,$color,date('Y-m-d H:i:s'), $bien_id));

      }

      $yes = array('msg'=>'OK','message'=>'Información actualizada');
      createLog(342, 8687, 'APP_INVENTARIO.dbo.InventarioBien','Actualizado Información',$valor_ant['bien_descripcion'], $descripcion);
      //echo json_encode($yes);
       $pdo->commit();

    }catch (PDOException $e){

      $yes = array('msg'=>'ERROR','message'=>$e);
      //echo json_encode($yes);
      try{ $pdo->rollBack();}catch(Exception $e2){
        $yes = array('msg'=>'ERROR','message'=>$e2);
      }
    }
    echo json_encode($yes);
    //fin
  }


}


if (isset($_POST['opcion']) || isset($_GET['opcion'])) {

  $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];
  switch ($opcion) {
    case 0:
      Inventario::getPrivilegios(1);
    break;
    case 1:
      Inventario::getBienesList();
    break;
    case 2:
      Inventario::getHojaCertificacion();
    break;
    case 3:
      Inventario::getBienById();
    break;
    case 4:
      Inventario::verificarBien();
    break;
    case 5:
      Inventario::getEstadoBienList();
    break;
    case 6:
      Inventario::getUbicacionesList();
    break;

    case 8:
      Inventario::generaCertificacion();
    break;
    case 9:
      Inventario::getCertificaciones();
    break;
    case 10:
      Inventario::filterLiveBienes();
    break;
    case 11:
      Inventario::getBienesSeleccionados();
    break;
    case 12:
      Inventario::entregaCertificacion();
    break;
    case 13:
      Inventario::actualizarDescripcionBien();
    break;

  }
}
?>
