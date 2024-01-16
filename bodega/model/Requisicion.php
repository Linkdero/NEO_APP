<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');

include_once '../../empleados/php/back/functions.php';

class Requisicion
{

  //opcion 0
  static function getPrivilegios($tipo)
  {
    //inicio
    $array = evaluar_flags_by_sistema($_SESSION['id_persona'], 8326);

    $privilegio = array();

    $bodegas = array(

    );

    $data = array(
      'bodega_financiero' => ($array[0]['flag_es_menu'] == 1) ? true : false,
      'bodega_residencias' => ($array[1]['flag_es_menu'] == 1) ? true : false,
      'bodega_edificios' => ($array[2]['flag_es_menu'] == 1) ? true : false,
      'bodega_talleres' => ($array[3]['flag_es_menu'] == 1) ? true : false,
      'bodega_academia' => ($array[4]['flag_es_menu'] == 1) ? true : false,
      'bodega_armeria' => ($array[5]['flag_es_menu'] == 1) ? true : false,


      //inicio
      'bodega_financiero_rev' => ($array[0]['flag_actualizar'] == 1) ? true : false,
      'bodega_residencias_rev' => ($array[1]['flag_actualizar'] == 1) ? true : false,
      'bodega_edificios_rev' => ($array[2]['flag_actualizar'] == 1) ? true : false,
      'bodega_talleres_rev' => ($array[3]['flag_actualizar'] == 1) ? true : false,
      'bodega_academia_rev' => ($array[4]['flag_actualizar'] == 1) ? true : false,
      'bodega_armeria_rev' => ($array[5]['flag_actualizar'] == 1) ? true : false,
      //fin


      //inicio
      'bodega_financiero_aut' => ($array[0]['flag_autoriza'] == 1) ? true : false,
      'bodega_residencias_aut' => ($array[1]['flag_autoriza'] == 1) ? true : false,
      'bodega_edificios_aut' => ($array[2]['flag_autoriza'] == 1) ? true : false,
      'bodega_talleres_aut' => ($array[3]['flag_autoriza'] == 1) ? true : false,
      'bodega_academia_aut' => ($array[4]['flag_autoriza'] == 1) ? true : false,
      'bodega_armeria_aut' => ($array[5]['flag_autoriza'] == 1) ? true : false,
      //fin

      'requisicion' => ($array[9]['flag_es_menu'] == 1) ? true : false,
      'requisicion_solicita' => ($array[9]['flag_insertar'] == 1) ? true : false,
      'requisicion_revisar' => ($array[9]['flag_autoriza'] == 1) ? true : false,
      'requisicion_autoriza' => ($array[9]['flag_autoriza'] == 1) ? true : false,

      //DESCARGA ES COCINA
      'residencias_solicita_cocina' => ($array[1]['flag_es_menu'] == 1 && $array[1]['flag_descarga'] == 1) ? true : false,
      //ACCESO ES RECURSOS
      'residencias_solicita_recursos' => ($array[1]['flag_es_menu'] == 1 && $array[1]['flag_acceso'] == 1) ? true : false,
      'residencias_solicita_comitivas' => ($array[1]['flag_es_menu'] == 1 && $array[1]['flag_imprimir'] == 1) ? true : false,


      'inspectoria' => ($array[6]['flag_es_menu'] == 1) ? true : false,
      'inspectoria_autoriza' => ($array[6]['flag_es_menu'] == 1 && $array[6]['flag_autoriza'] == 1) ? true : false,

      'solicita' => ($array[16]['flag_es_menu'] == 1) ? true : false,
      'solicita_autoriza' => ($array[16]['flag_autoriza'] == 1) ? true : false,
      'despacha' => ($array[17]['flag_es_menu'] == 1) ? true : false,

      'formulario1h' => ($array[10]['flag_es_menu'] == 1) ? true : false,
      'inventarios' => ($array[7]['flag_es_menu'] == 1) ? true : false,

      'visualizar_para_autorizar' =>
      (
        ($array[0]['flag_actualizar'] == 1 || $array[0]['flag_autoriza'] == 1) ||
        ($array[1]['flag_actualizar'] == 1 || $array[1]['flag_autoriza'] == 1) ||
        ($array[2]['flag_actualizar'] == 1 || $array[2]['flag_autoriza'] == 1) ||
        ($array[3]['flag_actualizar'] == 1 || $array[3]['flag_autoriza'] == 1) ||
        ($array[4]['flag_actualizar'] == 1 || $array[4]['flag_autoriza'] == 1) ||
        ($array[5]['flag_actualizar'] == 1 || $array[5]['flag_autoriza'] == 1)
      ) ? true : false,

    );
    ini_set('max_execution_time', 60); // Establece el límite en 60 segundos

    if ($tipo == 1) {
      echo json_encode($data);
    } else if ($tipo == 2) {
      return $data;
    }
    //fin
  }

  static function getBodegasParaEvaluar($valor, $tipo)
  {
    $array = evaluar_flags_by_sistema($_SESSION['id_persona'], 8326);
    $bodega = array(
      //inicio
      '04' => ($array[0]['flag_autoriza'] == 1 || $array[6]['flag_autoriza'] == 1) ? true : false,
      '01' => ($array[1]['flag_autoriza'] == 1) ? true : false,
      '09' => ($array[2]['flag_autoriza'] == 1) ? true : false,
      '06' => ($array[3]['flag_autoriza'] == 1) ? true : false,
      '10' => ($array[4]['flag_autoriza'] == 1) ? true : false,
      '11' => ($array[5]['flag_autoriza'] == 1) ? true : false,
      //fin
    );
    $id_bodega = array(
      //inicio
      '04' => ($array[0]['flag_autoriza'] == 1 || $array[6]['flag_autoriza'] == 1) ? '04' : '',
      '01' => ($array[1]['flag_autoriza'] == 1) ? '01' : '',
      '09' => ($array[2]['flag_autoriza'] == 1) ? '09' : '',
      '06' => ($array[3]['flag_autoriza'] == 1) ? '06' : '',
      '10' => ($array[4]['flag_autoriza'] == 1) ? '10' : '',
      '11' => ($array[5]['flag_autoriza'] == 1) ? '11' : '',
      //fin
    );

    $validacion = array(
      'bodega' => $bodega[$valor],
      'id_bodega' => $id_bodega[$valor],
    );

    if ($tipo == 1) {
      echo $validacion;
    } else if ($tipo == 2) {
      return $validacion;
    }

  }

  //opcion 1
  static function getRequisiciones()
  {
    ini_set('max_execution_time', 60); // Establece el límite en 60 segundos

    $filtro = (!empty($_POST['filtro'])) ? $_POST['filtro'] : NULL;
    //incio
    $clase = new empleado;
    $e = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);
    //$depto=(!empty($e['id_depto_funcional']))?$e['id_depto_funcional']:0;
    $dir = '';
    if ($_SESSION['id_persona'] == 5449 || $_SESSION['id_persona'] == 6627 || $_SESSION['id_persona'] == 1151) {
      $dir = 5;
      $departamento = 7988;
    } else if ($e['id_subdireccion_funcional'] == 34 || $e['id_subdireccion_funcional'] == 37 || $_SESSION['id_persona'] == 8678) {
      $dir = 207; // para unir las 2 subdirecciones
    } else {
      if (!empty($e['id_dirf'])) {
        $dir = $e['id_dirf'];
      } else {
        if (!empty($e['id_subsecre'])) {
          $e['id_subsecre'];
        } else {
          $dir = $e['id_secre'];
        }
      }
    }

    //$dir=(!empty($e['id_dirf']))?$e['id_dirf']:0;*/

    $privilegio = self::getPrivilegios(2);

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT
              a.requisicionId,
              a.requisicionResolucionId,
              REPLACE(STR(a.requisicionNum, 5), SPACE(1), '0') AS requisicionNum,
              a.requisicionStatus,
              a.requisicionDireccionSolicitante,
              a.requisicionUnidad,
              a.requisicionUtilizadoEn,
              a.requisicionUtilizadoPor,
              a.requisicionAutorizadoPor,
              a.requisicionAutorizadoEn,
              a.requisicionBodegaId,
              a.requisicionObservaciones,
              b.descripcion AS direccion,
              ISNULL(c.Uni_nom,'') AS unidad,
              d.descripcion AS bodega,
              ISNULL(e.primer_nombre,'')+' '+ISNULL(e.segundo_nombre,'')+' '+ISNULL(e.tercer_nombre,'')+' '+
              ISNULL(e.primer_apellido,'')+' '+ISNULL(e.segundo_apellido,'')+' '+ISNULL(e.tercer_apellido,'') AS solicitante,
              a.requisicionSolicitudRecursos
              FROM APP_POS.dbo.RequisicionEncabezado a
              INNER JOIN rrhh_direcciones b ON a.requisicionDireccionSolicitante = b.id_direccion
              LEFT JOIN APP_POS.dbo.UNIDAD c ON a.requisicionUnidad = c.id_departamento
              INNER JOIN tbl_pantallas d ON a.requisicionBodegaId = d.nombre_archivo COLLATE Modern_Spanish_CI_AS
              LEFT JOIN rrhh_persona e ON a.requisicionSolicitanteId = e.id_persona
              WHERE ";
    $sql01 = '';
    $sql02 = '';
    $sql03 = '';
    $sql04 = '';
    $sql05 = '';
    //echo $dir;

    if ($filtro == 9999) {
      $sql0 .= " ISNULL(a.requisicionDireccionSolicitante,0) > 0 AND YEAR(a.requisicionUtilizadoEn) = " . $_POST['year'] . " AND MONTH(a.requisicionUtilizadoEn) = " . $_POST['mes'] . " ";
    } else {
      //inicio
      if ($filtro == 1) {
        $sql01 .= " a.requisicionStatus IN (1,2,3,6,7,8,9)";
        createLog(364, 8326, 'APP_POS.dbo.RequisicionEncabezado', 'Visualizado requisciones pendiente de despacho', '', '');
      } else if ($filtro == 2) {
        $sql02 .= " a.requisicionStatus IN (4) ";
        createLog(364, 8326, 'APP_POS.dbo.RequisicionEncabezado', 'Visualizado requisciones despachadas', '', '');
      } else if ($filtro == 3) {
        $sql03 .= " a.requisicionStatus IN (5) ";
        createLog(364, 8326, 'APP_POS.dbo.RequisicionEncabezado', 'Visualizado requisciones anuladas', '', '');
      }

      if ($privilegio['residencias_solicita_recursos'] == true) {
        $sql04 .= " OR ISNULL(a.requisicionSolicitudRecursos,0) = 1 ";
      } else {
        $sql05 .= " AND ISNULL(a.requisicionSolicitudRecursos,0) <> 1 ";
      }

      $sql0 .= $sql01 . $sql02 . $sql03;
      /*Bodega de Financiero 04
      Bodega de Residencias 01
      Bodega de Edificios 09
      Bodega de Talleres 06
      Bodega de Academia 10
      Bodega de Armeria 11*/

      if(($privilegio['inspectoria'] == true || $privilegio['bodega_financiero'] == true) && ($privilegio['bodega_financiero_rev'])){
        $sql0 .= " AND a.requisicionBodegaId = '04' --AND a.requisicionStatus IN (2,6)";
        $sql0 .=" OR a.requisicionDireccionSolicitante = $dir ";
        $sql0.=" AND ".$sql01.$sql02.$sql03;
      }else
      if(($privilegio['bodega_financiero'] == true) && ($privilegio['bodega_financiero_aut'])){
        $sql0 .= " AND a.requisicionBodegaId = '04' --AND a.requisicionStatus IN (7)";
        $sql0 .=" OR a.requisicionDireccionSolicitante = $dir ";
        $sql0.=" AND ".$sql01.$sql02.$sql03;
      }else
      if(($privilegio['bodega_residencias'] == true) && ($privilegio['bodega_residencias_rev'] == true || $privilegio['bodega_residencias_aut'] == true)) {
        $sql0 .=" AND a.requisicionDireccionSolicitante = $dir ";
        $sql0 .= " OR a.requisicionBodegaId = '01'";

        $sql0 .=" AND ".$sql01.$sql02.$sql03;
        $sql0 .= " OR ISNULL(a.requisicionSolicitudRecursos,0) = 1";
        $sql0 .=" AND ".$sql01.$sql02.$sql03;
        //.$sql04.$sql05;
      }else if($privilegio['bodega_edificios_rev'] == true){
        $sql0 .= " AND a.requisicionBodegaId = '09' ";
        $sql0 .=" OR a.requisicionDireccionSolicitante = $dir ";
        $sql0.=" AND ".$sql01.$sql02.$sql03;
      }else if($privilegio['bodega_talleres_rev'] == true){
        $sql0 .= " AND a.requisicionBodegaId = '06' ";
        $sql0 .=" OR a.requisicionDireccionSolicitante = $dir ";
        $sql0.=" AND ".$sql01.$sql02.$sql03;
      }else if($privilegio['bodega_armeria_rev'] == true){
        $sql0 .= " AND a.requisicionBodegaId = '11' ";
        $sql0 .=" OR a.requisicionDireccionSolicitante = $dir ";
        $sql0.=" AND ".$sql01.$sql02.$sql03;
      }
      else if($privilegio['residencias_solicita_cocina'] == true){
        $sql0 .=" AND a.requisicionDireccionSolicitante = $dir ";
      }

      else {

        $sql0 .= ($dir == 667) ? " AND a.requisicionDireccionSolicitante IN (667) " : " AND a.requisicionDireccionSolicitante = $dir ";
        $sql0 .= $sql04.$sql05."AND a.requisicionBodegaId <> '06'";
        $sql0 .= ($_SESSION['id_persona'] == 5449) ? ' AND requisicionUnidad = 7988 ' : ' AND requisicionUnidad <> 7988';

      }
      //fin
    }

    $sql0 .= " ORDER BY a.requisicionId DESC";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array());
    $requisiciones = $q0->fetchAll();
    Database::disconnect_sqlsrv();

    $data = array();

    foreach ($requisiciones as $key => $r) {
      // code...
      $status = self::retornaSeguimientoRequisicion($r['requisicionStatus']);

      $progress = '';
      $progress .= '<div class="text-left" style="margin-top:0px; "><div style="margin-top:0px; "><span class="badge-sm text-' . $status['color'] . '"><i class="fa fa-' . $status['icono'] . '"></i> ' . $status['estado'];
      $progress .= ($status['porcentaje'] > 0) ? '<div class="progress progress-striped skill-bar " style="height:6px">
                <div class="progress-bar progress-bar-striped bg-' . $status['color'] . '" role="progressbar" aria-valuenow="' . $status['porcentaje'] . '" aria-valuemin="' . $status['porcentaje'] . '" aria-valuemax="100" style="width: ' . $status['porcentaje'] . '%">
                </div>
              </div></div>' : '';
      $progressV = '';

      $tipo = ($r['requisicionBodegaId'] == '11') ? 1 : 0;
      $sub_array = array(
        'DT_RowId' => $r['requisicionId'],
        'requisicionId' => $r['requisicionId'],
        'requisicionResolucionId' => $r['requisicionResolucionId'],
        'requisicionNum' => $r['requisicionNum'],
        'requisicionStatus' => $r['requisicionStatus'],
        'requisicionDireccionSolicitante' => $r['requisicionDireccionSolicitante'],
        'requisicionUnidad' => $r['requisicionUnidad'],
        'requisicionUtilizadoEn' => $r['requisicionUtilizadoEn'],
        'requisicionUtilizadoPor' => $r['requisicionUtilizadoPor'],
        'requisicionAutorizadoPor' => $r['requisicionAutorizadoPor'],
        'requisicionAutorizadoEn' => $r['requisicionAutorizadoEn'],
        'requisicionBodegaId' => $r['requisicionBodegaId'],
        'requisicionObservaciones' => $r['requisicionObservaciones'],
        'direccion' => $r['direccion'],
        'unidad' => $r['unidad'],
        'bodega' => $r['bodega'],
        'estado' => $progress,
        'solicitante' => mb_strtoupper($r['solicitante'], 'utf-8'),
        'fecha' => fecha_dmy($r['requisicionUtilizadoEn']),
        'accion' => '<div class="btn-group"><span class="btn btn-sm btn-soft-info" onclick="imprimirRequisicion(' . $r['requisicionId'] . ',' . $tipo . ')"><i class="fa fa-print"></i></span><span class="btn btn-sm btn-soft-info" id="reqDetalleInfo" data-id="' . $r['requisicionId'] . '" data-type="' . $r['requisicionBodegaId'] . '"> <i class="fa-regular fa-file-circle-info"></i></span></div>'
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
    //fin
  }

  //opcion 2
  static function getRequisicionById()
  {
    //inicio
    $tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : $_GET['tipo'];
    $requisicion_id = (!empty($_POST['requisicion_id'])) ? $_POST['requisicion_id'] : $_GET['requisicion_id'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT
              a.requisicionId,
              a.requisicionResolucionId,
              REPLACE(STR(a.requisicionNum, 5), SPACE(1), '0') AS requisicionNum,
              a.requisicionStatus,
              a.requisicionDireccionSolicitante,
              a.requisicionUnidad,
              a.requisicionUtilizadoEn,
              a.requisicionUtilizadoPor,
              a.requisicionAutorizadoPor,
              a.requisicionAutorizadoEn,
              a.requisicionBodegaId,
              a.requisicionObservaciones,
              b.descripcion AS direccion,
              ISNULL(c.Uni_Nom,'') AS unidad,
              d.descripcion AS bodega,
              ISNULL(e.primer_nombre,'')+' '+ISNULL(e.segundo_nombre,'')+' '+ISNULL(e.tercer_nombre,'')+' '+
              ISNULL(e.primer_apellido,'')+' '+ISNULL(e.segundo_apellido,'')+' '+ISNULL(e.tercer_apellido,'') AS solicitante
              FROM APP_POS.dbo.RequisicionEncabezado a
              INNER JOIN rrhh_direcciones b ON a.requisicionDireccionSolicitante = b.id_direccion
              LEFT JOIN APP_POS.dbo.UNIDAD c ON a.requisicionUnidad = c.id_departamento
              INNER JOIN tbl_pantallas d ON a.requisicionBodegaId = d.nombre_archivo COLLATE Modern_Spanish_CI_AS
              LEFT JOIN rrhh_persona e ON a.requisicionSolicitanteId = e.id_persona
              WHERE a.requisicionId = ? ";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($requisicion_id));
    $r = $q0->fetch();
    Database::disconnect_sqlsrv();

    $status = self::retornaSeguimientoRequisicion($r['requisicionStatus']);
    $validarBodega = self::getBodegasParaEvaluar($r['requisicionBodegaId'], 2);

    $progress = '';
    $progress .= '<div class="text-left" style="margin-top:0px; "><div style="margin-top:0px; "><span class="badge-sm text-' . $status['color'] . '"><i class="fa fa-' . $status['icono'] . '"></i> ' . $status['estado'];
    $progress .= ($status['porcentaje'] > 0) ? '<div class="progress progress-striped skill-bar " style="height:6px">
              <div class="progress-bar progress-bar-striped bg-' . $status['color'] . '" role="progressbar" aria-valuenow="' . $status['porcentaje'] . '" aria-valuemin="' . $status['porcentaje'] . '" aria-valuemax="100" style="width: ' . $status['porcentaje'] . '%">
              </div>
            </div></div>' : '';
    $progressV = '';


    $data = array();

    $results = array(
      'DT_RowId' => $r['requisicionId'],
      'requisicionId' => $r['requisicionId'],
      'requisicionResolucionId' => $r['requisicionResolucionId'],
      'requisicionNum' => $r['requisicionNum'],
      'requisicionStatus' => $r['requisicionStatus'],
      'requisicionDireccionSolicitante' => $r['requisicionDireccionSolicitante'],
      'requisicionUnidad' => $r['requisicionUnidad'],
      'requisicionUtilizadoEn' => $r['requisicionUtilizadoEn'],
      'requisicionUtilizadoPor' => $r['requisicionUtilizadoPor'],
      'requisicionAutorizadoPor' => $r['requisicionAutorizadoPor'],
      'requisicionAutorizadoEn' => $r['requisicionAutorizadoEn'],
      'requisicionBodegaId' => $r['requisicionBodegaId'],
      'requisicionObservaciones' => $r['requisicionObservaciones'],
      'direccion' => $r['direccion'],
      'unidad' => empty($r['unidad']) ? NULL : $r['unidad'],
      'bodega' => $r['bodega'],
      'estado' => $progress,
      'fecha' => fecha_dmy($r['requisicionUtilizadoEn']),
      'permisoEdicion' => $validarBodega['bodega'],
      'permisoBodega' => $validarBodega['id_bodega'],
      'solicitante' => mb_strtoupper($r['solicitante'], 'utf-8')
      //'accion'=>'<span class="btn btn-sm btn-soft-info" onclick="imprimirRequisicion('.$r['requisicionId'].')"><i class="fa fa-print"></i></span><span class="btn btn-sm btn-soft-info" id="reqDetalleInfo" data-id="'.$r['requisicionId'].'"> Detalle</span>'
    );

    if ($tipo == 1) {
      echo json_encode($results);
    } else if ($tipo == 2) {
      return $results;
    }

    //fin
    //fin
  }

  //opcion 3
  static function getInsumosByRequisicion()
  {
    //inicio
    $fase = 1;
    $campos = '';
    $condicion = '';
    $fase = (!empty($_POST['fase'])) ? $_POST['fase'] : $_GET['fase'];
    if ($fase == 1) {
      $campos = ', f.Prd_exi AS existencia, ISNULL(g.cant_au,0) AS cantidad_apartada';
    } else if ($fase == 2) {
      //$condicion = ' AND d.productoStatus IN (1,2)';
      $condicion = ' AND ISNULL(d.cantidadAutorizada,0) > 0 || e.requisicionStatus = 5';
    }

    $condicion = '';
    $requisicion_id = (!empty($_POST['requisicion_id'])) ? $_POST['requisicion_id'] : $_GET['requisicion_id'];
    $tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : $_GET['tipo'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT d.requisicionId, a.Pro_idint, a.Ent_id, a.Pro_des, a.Med_id, b.Med_nom,
          UPPER(LEFT((a.Pro_des),1))+LOWER(SUBSTRING((a.Pro_des),2,
          LEN((a.Pro_des)))) AS caracteres,d.cantidadSolicitada,d.cantidadAutorizada,d.cantidadDespachada,
          a.Renglon_PPR AS renglon,
           d.productoStatus $campos
             FROM APP_POS.dbo.PRODUCTO a
             INNER JOIN APP_POS.dbo.MEDIDA b ON a.Med_id=b.Med_id
             --INNER JOIN APP_POS.dbo.PRODUCTO_DETALLE c ON c.Pro_idint = a.Pro_idint
             INNER JOIN APP_POS.dbo.RequisicionDetalle d ON d.productoId = a.Pro_idint ";

    if ($fase == 1) {
      $sql .= "
			 INNER JOIN APP_POS.dbo.RequisicionEncabezado e ON d.requisicionId = e.requisicionId  INNER JOIN APP_POS.dbo.PRODUCTO_DETALLE f ON d.productoId = f.Pro_idint AND e.requisicionBodegaId = f.Bod_id
  			 LEFT JOIN (
  				SELECT productoId, SUM(cantidadAutorizada) AS cant_au
  				FROM APP_POS.dbo.RequisicionDetalle WHERE ISNULL(cantidadDespachada,0) = 0 AND productoStatus = 1 GROUP BY productoId
  			) AS g ON g.productoId = d.productoId ";

    }else if($fase == 2){
      $sql .= "
			 INNER JOIN APP_POS.dbo.RequisicionEncabezado e ON d.requisicionId = e.requisicionId  ";
    }

    $sql .= " WHERE d.requisicionId = ? $condicion ORDER BY d.productoOrden ASC";


    $p = $pdo->prepare($sql);
    $p->execute(array($requisicion_id));
    $insumos = $p->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();
    $data = array();

    foreach ($insumos as $key => $i) {
      // code...
      $original = str_replace("\n", "", $i['caracteres']);

      //$my_wrapped_string = wordwrap($original, 100, );
      $letras = strlen($original);
      $lineas = round(($letras / 70) * 3.4);
      $lines = 0;
      $puntos = ($lineas == 0 || $lineas < 5.5) ? 5 : $lineas;
      $lines += $puntos;

      $sub_array = array(
        'requisicion_id' => $requisicion_id,
        'id_chk_req' => $requisicion_id . '-' . $i['Pro_idint'],
        'checked' => ($i['productoStatus'] == 1 || $i['productoStatus'] == 2) ? true : false,
        'renglon' => $i['renglon'],
        'Pro_idint' => $i['Pro_idint'],
        'cantidadSolicitada' => (!empty($i['cantidadSolicitada'])) ? $i['cantidadSolicitada'] : '',
        'cantidadAutorizada' => (!empty($i['cantidadAutorizada'])) ? $i['cantidadAutorizada'] : '',
        'cantidadDespachada' => (!empty($i['cantidadDespachada'])) ? $i['cantidadDespachada'] : '',
        //'Pedd_can'=>number_format($i['Pedd_can'],0,'',''),
        'cant_au' => '',
        'cant_autorizada_dsp' => '',
        'Ent_id' => $i['Ent_id'],
        'Pro_des' => $i['Pro_des'],
        'Med_id' => $i['Med_id'],
        'Med_nom' => $i['Med_nom'],
        //'Bod_id'=>$i['Bod_id'],
        'lineas' => $lines,
        'existencia' => ($fase == 1) ? $i['existencia'] : '',
        'existencia' => (!empty($i['existencia'])) ? $i['existencia'] : '',
        'cantidad_apartada' => (!empty($i['cantidad_apartada'])) ? $i['cantidad_apartada'] : '',
        'cantidad_disponible' => ($fase == 1) ? ($i['existencia'] - $i['cantidad_apartada']) : ''

      );
      $data[] = $sub_array;
    }

    if ($tipo == 1) {
      echo json_encode($data);
    } else if ($tipo == 2) {
      return $data;
    }

    //fin
  }

  static function getRequisicionInsumos()
  {

  }

  static function dkajldkf()
  {
    /*
    1- solicitante
    2- inspectoria
    3- financiero
    4- residencias
    5- manto edificios
    6- manto vehicular
    7- armeria
    8- academia
    9- traslados-*/

  }

  static function retornaEstadoRequisicion()
  {

  }

  //opcion 4
  static function crearSolicitudRequisicion()
  {
    //inicio
    $creador = $_SESSION['id_persona'];
    $id_direccion = (!empty($_POST['id_direccion'])) ? $_POST['id_direccion'] : NULL;
    $dir = '';
    $tipo = NULL;

    $fecha = date('Y-m-d H:i:s');
    $unidad = (!empty($_POST['unidad'])) ? $_POST['unidad'] : $_POST['id_departamento'];
    $observaciones = $_POST['id_observaciones'];
    $bodega = $_POST['id_bodega'];
    $insumos = $_POST['insumos'];
    $fecha_cre = date('Y-m-d H:i:s');
    $id_solicitante = $_POST['id_solicitante'];
    $privilegio = self::getPrivilegios(2);
    if ($_SESSION['id_persona'] == 5449) {
      $dir = 5;
      $unidad = 7988;
    } else {
      //inicio
      if (empty($id_direccion)) {
        //inicio

        $clase = new empleado;
        $e = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);

        if ($e['id_subdireccion_funcional'] == 34 || $e['id_subdireccion_funcional'] == 37 || $_SESSION['id_persona'] == 8678 || $_SESSION['id_persona'] == 8753 || $_SESSION['id_persona'] == 6584) {
          $dir = 207; // para unir las 2 subdirecciones
        } else {
          if (!empty($e['id_dirf'])) {
            $dir = $e['id_dirf'];
          } else {
            if (!empty($e['id_subsecre'])) {
              $e['id_subsecre'];
            } else {
              $dir = $e['id_secre'];
            }
          }
        }
        //fin
      } else {
        $dir = $id_direccion;

        if ($bodega == '01') {
          $tipo = 1;
        } else if ($bodega == '') {

        }
      }
      //fin
    }

    $yes = '';
    $pdo = Database::connect_sqlsrv();
    try {
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql1 = "UPDATE TOP (1) APP_POS.dbo.RequisicionEncabezado SET
        requisicionStatus = ?, requisicionDireccionsolicitante = ?, requisicionUnidad = ?, requisicionUtilizadoPor = ?,
        requisicionUtilizadoEn = ?, requisicionObservaciones = ?, requisicionBodegaId = ?, requisicionSolicitanteId = ?, requisicionSolicitudRecursos = ? WHERE requisicionStatus = ? ";
      $q1 = $pdo->prepare($sql1);
      $q1->execute(
        array(
          1,
          $dir,
          $unidad,
          $creador,
          $fecha,
          $observaciones,
          $bodega,
          $id_solicitante,
          $tipo,
          0
        )
      );

      $sql2 = "SELECT TOP (1) requisicionId, requisicionNum FROM APP_POS.dbo.RequisicionEncabezado WHERE  requisicionUtilizadoPor = ? AND requisicionStatus = ? ORDER BY requisicionId DESC";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(
        array(
          $creador,
          1
        )
      );
      $req = $q2->fetch();

      if ($bodega == '09' || $bodega == '06' || $bodega == '11') {
        $sql5 = "UPDATE APP_POS.dbo.RequisicionEncabezado SET requisicionStatus = ?, requisicionAutorizadoPor = ?, requisicionAutorizadoEn = ? WHERE requisicionId = ?";
        $q5 = $pdo->prepare($sql5);
        $q5->execute(array(3, $creador, $fecha_cre, $req['requisicionId']));
      } else if ($privilegio['residencias_solicita_recursos'] == 1) {
        $sql5 = "UPDATE APP_POS.dbo.RequisicionEncabezado SET requisicionStatus = ?, requisicionAutorizadoPor = ?, requisicionAutorizadoEn = ? WHERE requisicionId = ?";
        $q5 = $pdo->prepare($sql5);
        $q5->execute(array(6, $creador, $fecha_cre, $req['requisicionId']));
      }


      $x = 0;
      if ($bodega == '09' || $bodega == '06' || $bodega == '11') {
        foreach ($insumos as $i) {
          $x++;
          $sql3 = "INSERT INTO APP_POS.dbo.RequisicionDetalle (requisicionId, productoId, cantidadSolicitada, cantidadAutorizada, productoOrden, productoStatus)
          values(?,?,?,?,?,?)";
          $q3 = $pdo->prepare($sql3);
          $q3->execute(array($req['requisicionId'], $i['Pro_idint'], $i['can_solicitada'], $i['can_solicitada'], $x, 1));
        }
      } else {
        foreach ($insumos as $i) {
          $x++;
          $sql3 = "INSERT INTO APP_POS.dbo.RequisicionDetalle (requisicionId, productoId, cantidadSolicitada, productoOrden, productoStatus)
          values(?,?,?,?,?)";
          $q3 = $pdo->prepare($sql3);
          $q3->execute(array($req['requisicionId'], $i['Pro_idint'], $i['can_solicitada'], $x, 1));
        }
      }


      createLog(364, 8326, 'APP_POS.dbo.RequisicionEncabezado', 'Se generó requisición.', '', ' datos:{ requisionId:' . $req['requisicionId'] . ' requisicionNum:' . $req['requisicionNum'] . ', bodega_id' . $bodega . ',direccion :' . $dir . '}');
      $yes = array('msg' => 'OK', 'id' => '');
      $pdo->commit();
      //echo json_encode($yes);

    } catch (PDOException $e) {

      $yes = array('msg' => 'ERROR', 'id' => $e);
      //echo json_encode($yes);
      try {
        $pdo->rollBack();
      } catch (Exception $e2) {
        $yes = array('msg' => 'ERROR', 'id' => $e2);

      }
    }
    echo json_encode($yes);

    Database::disconnect_sqlsrv();
    //fin
  }

  static function getBodegasPOS()
  {
    $tipo = $_GET['tipo'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "  SELECT * FROM APP_POS.dbo.BODEGA WHERE Bod_est = ?";
    $p = $pdo->prepare($sql);
    $p->execute(array(1));
    $bodegas = $p->fetchAll(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();
    $data = array();

    if ($tipo == 1) {
      $sub_array = array(
        'id_item' => '',
        'item_string' => '-- Seleccionar -- '
      );
      $data[] = $sub_array;
    }

    foreach ($bodegas as $key => $b) {
      // code...
      $sub_array = array(
        'Ent_id' => $b['Ent_id'],
        'Dir_cor' => $b['Dir_cor'],
        'Bod_id' => $b['Bod_id'],
        'Bod_nom' => $b['Bod_nom'],
        'Bod_dir' => $b['Bod_dir'],
        'Bod_tel' => $b['Bod_tel'],
        'Ser_id' => $b['Ser_id'],
        'Bod_par' => $b['Bod_par'],
        'Bod_ppr' => $b['Bod_ppr'],
        'Bod_est' => $b['Bod_est'],

        'id_item' => $b['Bod_id'],
        'item_string' => $b['Bod_nom']
      );

      $data[] = $sub_array;

    }

    echo json_encode($data);
  }

  // opcion 7
  static function getProductoById()
  {
    $bodega_id = $_GET['bodega_id'];
    $Pro_idi = $_GET['Pro_idi'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT a.Pro_idint, a.Ent_id, a.Pro_des, a.Med_id, b.Med_nom, c.Bod_id,
    UPPER(LEFT((a.Pro_des),1))+LOWER(SUBSTRING((a.Pro_des),2,
    LEN((a.Pro_des)))) AS caracteres, c.Prd_exi AS existencia, ISNULL(g.cant_au,0) AS cantidad_apartada,a.Renglon_PPR AS renglon
             FROM APP_POS.dbo.PRODUCTO a
             INNER JOIN APP_POS.dbo.MEDIDA b ON a.Med_id=b.Med_id
             INNER JOIN APP_POS.dbo.PRODUCTO_DETALLE c ON c.Pro_idint = a.Pro_idint
             --INNER JOIN APP_POS.dbo.RequisicionEncabezado e ON d.requisicionId = e.requisicionId  INNER JOIN APP_POS.dbo.PRODUCTO_DETALLE f ON d.productoId = f.Pro_idint AND e.requisicionBodegaId = f.Bod_id
                    LEFT JOIN (
                     SELECT productoId, SUM(cantidadAutorizada) AS cant_au
                     FROM APP_POS.dbo.RequisicionDetalle WHERE ISNULL(cantidadDespachada,0) = 0 AND productoStatus = 1 GROUP BY productoId
                   ) AS g ON g.productoId = a.Pro_idint
             WHERE c.Bod_id = ? AND a.Pro_idint = ? ";
    $p = $pdo->prepare($sql);
    $p->execute(array($bodega_id, $Pro_idi));
    $i = $p->fetch(PDO::FETCH_ASSOC);

    Database::disconnect_sqlsrv();
    $data = array();

    $original = str_replace("\n", "", $i['caracteres']);

    //$my_wrapped_string = wordwrap($original, 100, );
    $letras = strlen($original);
    $lineas = round(($letras / 70) * 3.4);
    $lines = 0;
    $puntos = ($lineas == 0 || $lineas < 5.5) ? 5 : $lineas;
    $lines += $puntos;

    $data = array(
      'renglon' => $i['renglon'],
      'Pro_idint' => $i['Pro_idint'],
      //'Pedd_can'=>number_format($i['Pedd_can'],0,'',''),
      'Ent_id' => $i['Ent_id'],
      'Pro_des' => $i['Pro_des'],
      'Med_id' => $i['Med_id'],
      'Med_nom' => $i['Med_nom'],
      'Bod_id' => $i['Bod_id'],
      'lineas' => $lines,
      'existencia' => $i['existencia'] - $i['cantidad_apartada'],
      'cantidad_apartada' => $i['cantidad_apartada'],

    );


    /*if($_SESSION['id_persona'] == 4792 || $_SESSION['id_persona'] == 8346 || $_SESSION['id_persona'] == 8741){
      echo json_encode($data);
    }else{*/
      //inicio
      if ($i['existencia'] <= 0 && $i['Pro_idint'] != 765) {
        $array = array('msg' => 'ERROR', 'message' => 'El insumo que está intentando seleccionar no cuenta con existencias.');
        echo json_encode($array);
      } else {
        echo json_encode($data);
      }
      //fin
    //}





  }

  //opcion 8
  static function getUnidades()
  {
    ini_set('max_execution_time', 600); // Establece el límite en 60 segundos

    //incio
    $clase = new empleado;
    $e = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);
    //$depto=(!empty($e['id_depto_funcional']))?$e['id_depto_funcional']:0;
    $dir = '';
    if (!empty($e['id_dirf'])) {
      $dir = $e['id_dirf'];
    } else {
      if (!empty($e['id_subsecre'])) {
        $e['id_subsecre'];
      } else {
        $dir = $e['id_secre'];
      }

    }

    $dir = ($_SESSION['id_persona'] == 8678 || $_SESSION['id_persona'] == 8753 || $_SESSION['id_persona'] == 6584) ? 207 : $dir;
    //$dir=(!empty($e['id_dirf']))?$e['id_dirf']:0;
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql0 = "SELECT a.id_departamento, a.descripcion AS nombre
             FROM rrhh_departamentos a
             INNER JOIN rrhh_subdirecciones b ON a.id_subdireccion=b.id_subdireccion
             INNER JOIN rrhh_direcciones c ON b.id_direccion =c.id_direccion
  		       INNER JOIN APP_POS.dbo.UNIDAD d ON a.id_departamento=d.id_departamento
             WHERE c.id_direccion=? AND a.id_departamento NOT IN (96,106,126,134,136,123,148,102,139)
             UNION
             SELECT a.id_subdireccion AS id_departamento, a.descripcion AS nombre
             FROM rrhh_subdirecciones a
             INNER JOIN rrhh_direcciones b ON a.id_direccion= b.id_direccion
             WHERE a.id_direccion=?
             UNION
             SELECT a.id_direccion AS id_departamento, a.descripcion AS nombre
             FROM rrhh_direcciones a
             WHERE a.id_direccion=?";

    //if($e['id_de'])
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($dir, $dir, $dir));
    $unidades = $q0->fetchAll();
    Database::disconnect_sqlsrv();

    $data = array();
    $sub_array = array(
      'id_departamento' => '',
      'nombre' => '-- Seleccionar -- '
    );
    $data[] = $sub_array;
    if ($_SESSION['id_persona'] == 5449) {
      $sub_array = array(
        'id_departamento' => '7898',
        'nombre' => 'HANGAR PRESIDENCIAL'
      );
      $data[] = $sub_array;
    }

    foreach ($unidades as $u) {
      $respuesta = '';
      $sub_array = array(
        'id_departamento' => $u['id_departamento'],
        'nombre' => $u['nombre']
      );
      $data[] = $sub_array;

    }

    echo json_encode($data);
    //fin
  }

  //opcion 9
  static function hojaRequisicion()
  {
    $req = self::getRequisicionById();
    $insumos = self::getInsumosByRequisicion();

    if (
      /*$req['requisicionBodegaId'] == '09' || $req['requisicionBodegaId'] == '06' ||
      $req['requisicionBodegaId'] == '10' || $req['requisicionBodegaId'] == '11' ||*/
      $req['requisicionStatus'] == 3 || $req['requisicionStatus'] == 4 || $req['requisicionStatus'] == 5
    ) {

      if ($req['unidad'] == '') {
        $desDepto = $req['unidad'];
      } else {
        if ($req['requisicionUnidad'] == 7988) {
          $desDepto = 'HANGAR PRESIDENCIAL';
        } else {
          $desDepto = '';
        }
      }
      $desDepto = (!empty($req['unidad'])) ? $req['unidad'] : '';

      if ($req['requisicionUnidad'] == 7988) {
        $desDepto = 'HANGAR PRESIDENCIAL';
      }

      $data = array(
        'insumos' => $insumos,
        'requisicion_num' => $req['requisicionNum'],
        'fecha' => fecha_dmy($req['requisicionUtilizadoEn']),
        'direccion' => $req['direccion'],
        'departamento' => (!empty($desDepto)) ? $desDepto : $req['direccion'],
        /* !empty($req['unidad']) ? $req['unidad'] : '',  */
        'bodega' => $req['bodega'],
        'observaciones' => $req['requisicionObservaciones'],
        'solicitante' => $req['solicitante'],
        'msg_anulado'=>($req['requisicionStatus'] == 5) ? 'ANULADA' : ''
      );
    } else {
      $data = array(
        'msg' => 'ERROR',
        'message' => 'La requisición que está intentando imprimir, no ha sido autorizada en la ' . $req['bodega']
      );
    }
    echo json_encode($data);
  }
  //opcion 10
  static function updateRequisicion()
  {
    //inicio
    $requisicion_id = $_POST['requisicion_id'];
    $creador = $_SESSION['id_persona'];
    $insumos = $_POST['insumos'];
    $estado = $_POST['estado'];

    $fecha = date('Y-m-d H:i:s');
    $fecha_cre = date('Y-m-d H:i:s');

    $yes = '';
    $pdo = Database::connect_sqlsrv();
    try {
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql0 = "SELECT requisicionStatus, requisicionBodegaId, requisicionDireccionSolicitante FROM APP_POS.dbo.RequisicionEncabezado WHERE requisicionId = ?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($requisicion_id));
      $state = $q0->fetch();

      $sql3 = "UPDATE APP_POS.dbo.RequisicionEncabezado SET requisicionStatus = ?, requisicionAutorizadoPor = ?, requisicionAutorizadoEn = ? WHERE requisicionId = ?";
      $q3 = $pdo->prepare($sql3);
      $q3->execute(array($estado, $creador, $fecha, $requisicion_id));


      if ($estado == 3 || $estado == 7) {
        if ($state['requisicionStatus'] == 2) {
          foreach ($insumos as $i) {
            $checked = ($i['checked'] == 'true') ? 1 : 0;
            $cant = ($i['checked'] == 'true') ? floatval($i['cant_au']) : NULL;
            $sql3 = "UPDATE APP_POS.dbo.RequisicionDetalle SET cantidadAutorizada = ?, productoStatus = ? WHERE requisicionId = ? AND productoId = ?";
            $q3 = $pdo->prepare($sql3);
            $q3->execute(array($cant, $checked, $requisicion_id, $i['Pro_idint']));
          }
        }

        $message = 'Requisición autorizada';
      } else if ($estado == 2) {
        $message = 'Requisición en revisión';
      } else if ($estado == 5) {
        $message = 'Requisición anulada';
      } else if ($estado == 6 || $estado == 7) {
        $message = 'Requisición autorizada'; //cuando es autorizda en la dirección
      } else if ($estado == 8 || $estado == 9) {
        $message = 'Requisición rechazada';
      }

      createLog(364, 8326, 'APP_POS.dbo.RequisicionEncabezado', $message, '', ' datos:{ requisionId:' . $requisicion_id . ', bodega_id' . $state['requisicionBodegaId'] . ',direccion :' . $state['requisicionDireccionSolicitante'] . '}');

      $yes = array('msg' => 'OK', 'id' => '', 'message' => $message);
      $pdo->commit();
      //echo json_encode($yes);

    } catch (PDOException $e) {

      $yes = array('msg' => 'ERROR', 'id' => $e);
      //echo json_encode($yes);
      try {
        $pdo->rollBack();
      } catch (Exception $e2) {
        $yes = array('msg' => 'ERROR', 'id' => $e2);

      }
    }
    echo json_encode($yes);

    Database::disconnect_sqlsrv();
    //fin
  }

  //opcion 11
  static function retornaSeguimientoRequisicion($valor)
  {
    // 0 disponible
    // 1 solicitado
    // 2 revision
    // 3 autorizado
    // 4 despachado
    // 5 anulado
    // 6 rechazado

    $color = array(0 => "warning", 1 => "info", 2 => "info", 3 => "info", 4 => "success", 5 => "danger", 6 => "info", 7 => "info", 8 => "warning", 9 => "warning");
    $icono = array(0 => "pause", 1 => "arrow-right", 2 => "check-circle", 3 => "check-circle", 4 => "check-circle", 5 => "times-circle", 6 => "check-circle", 7 => "check-circle", 8 => "times-circle", 9 => "times-circle");
    $porcentaje = array(0 => "0", 1 => "10", 2 => "30", 3 => "75", 4 => "100", 5 => "100", 6 => "40", 7 => "35", 8 => "25", 9 => "25");
    $estado = array(0 => "Disponible", 1 => "Solicitado", 2 => "En revisión", 3 => "Autorizado", 4 => "Despachado", 5 => "Anulado", 6 => "Autorizado Director", 7 => "Autorizado Inspectoria", 8 => "Rechazado en Dirección", 9 => "Rechazado");
    $array = array(
      'icono' => $icono[$valor],
      'color' => $color[$valor],
      'porcentaje' => $porcentaje[$valor],
      'estado' => $estado[$valor]
    );
    return $array;
  }

  //opcion 12
  static function validarBodega()
  {
    $bodega = $_GET['bodega_id'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql3 = "SELECT TOP 1 c.descrip_corta, c.nombre_archivo AS bodega, a.id_pantalla, a.id_acceso, b.id_persona,reng_num,a.flag_es_menu,a.flag_insertar,a.flag_eliminar,
            a.flag_actualizar,a.flag_imprimir,a.flag_acceso,a.flag_autoriza,a.flag_descarga

            FROM tbl_accesos_usuarios_det a
            INNER JOIN tbl_accesos_usuarios b ON a.id_acceso=b.id_acceso
            LEFT JOIN tbl_pantallas c ON a.id_pantalla = c.id_pantalla
            WHERE
            b.id_persona=? AND b.id_sistema=? AND b.id_status = ? AND ISNULL(nombre_archivo,'') <> '' AND a.flag_actualizar = ?
            OR
            b.id_persona=? AND b.id_sistema=? AND b.id_status = ? AND ISNULL(nombre_archivo,'') <> '' AND a.flag_autoriza = ?";
    $q3 = $pdo->prepare($sql3);
    $q3->execute(array($_SESSION['id_persona'], 8326, 1119, 1, $_SESSION['id_persona'], 8326, 1119, 1));
    $bod = $q3->fetch(PDO::FETCH_ASSOC);
    Database::disconnect_sqlsrv();

    if (!empty($bod['bodega'])) {
      if ($bodega == $bod['bodega']) {
        echo 'true';
      } else {
        echo 'false';
      }
    } else {
      echo 'false';
    }
  }

  //opcion 6
  static function getBodegasPermiso()
  {
    //inicio
    $data = array();
    $privilegio = self::getPrivilegios(2);
    if ($privilegio['residencias_solicita_recursos'] == 1) {
      $sub_array = array(
        'id_item' => '',
        'item_string' => '-- SELECCIONAR --'
      );
      $data[] = $sub_array;
      $sub_array = array(
        'id_item' => '01',
        'item_string' => 'BODEGA DE RESIDENCIAS'
      );
      $data[] = $sub_array;
    } else {
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql3 = "SELECT c.descrip_corta AS bodega, c.nombre_archivo AS Bod_id, a.id_pantalla, a.id_acceso, b.id_persona,reng_num,a.flag_es_menu,a.flag_insertar,a.flag_eliminar,
              a.flag_actualizar,a.flag_imprimir,a.flag_acceso,a.flag_autoriza,a.flag_descarga

              FROM tbl_accesos_usuarios_det a
              INNER JOIN tbl_accesos_usuarios b ON a.id_acceso=b.id_acceso
              LEFT JOIN tbl_pantallas c ON a.id_pantalla = c.id_pantalla
              WHERE
              b.id_persona=? AND b.id_sistema=? AND b.id_status = ? AND ISNULL(nombre_archivo,'') <> '' AND a.flag_es_menu = ?";
      $q3 = $pdo->prepare($sql3);
      $q3->execute(array($_SESSION['id_persona'], 8326, 1119, 1));
      $bodegas = $q3->fetchAll(PDO::FETCH_ASSOC);
      Database::disconnect_sqlsrv();

      $sub_array = array(
        'id_item' => '',
        'item_string' => '-- SELECCIONAR --'
      );
      $data[] = $sub_array;

      foreach ($bodegas as $key => $b) {
        // code...
        $sub_array = array(
          'id_item' => $b['Bod_id'],
          'item_string' => $b['bodega']
        );

        $data[] = $sub_array;
      }
    }



    echo json_encode($data);
    //fin
  }

  //opcion 13
  static function getFamiliasBodega()
  {

    //inicio
    $data = array();
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql3 = "  SELECT ssf_id, grupo, ssf_est,
			SUM(residencias) AS residencias, SUM(financiero) AS financiero, SUM(talleres) AS talleres,
			SUM(edificios) AS edificios, SUM(academia) AS academia, SUM(armeria) AS armeria
             FROM (
               SELECT DISTINCT a.ssf_id, d.fam_nom +' - '+ c.sfa_nom +' - '+ a.ssf_nom AS grupo, a.ssf_est,
               CASE WHEN b.bod_id = '01' THEN 1 ELSE 0 END residencias,
			   CASE WHEN b.bod_id = '04' THEN 1 ELSE 0 END financiero,
			   CASE WHEN b.bod_id = '06' THEN 1 ELSE 0 END talleres,
			   CASE WHEN b.bod_id = '09' THEN 1 ELSE 0 END edificios,
			   CASE WHEN b.bod_id = '10' THEN 1 ELSE 0 END academia,
			   CASE WHEN b.bod_id = '11' THEN 1 ELSE 0 END armeria


               FROM
               APP_POS.dbo.PRO_SSFAMILIA AS a
               LEFT JOIN APP_POS.dbo.PRO_SSFAMILIA_BODEGA b ON b.ssf_id = a.ssf_id
			   INNER JOIN APP_POS.dbo.PRO_SFAMILIA c ON a.sfa_id = c.sfa_id
			   INNER JOIN APP_POS.dbo.PRO_FAMILIA d ON a.fam_id = d.fam_id
         WHERE a.ssf_est = 1
             ) AS query
             GROUP BY ssf_id, grupo, ssf_est
    /*SELECT fam_id, fam_nom, fam_est,
			SUM(residencias) AS residencias, SUM(financiero) AS financiero, SUM(talleres) AS talleres,
			SUM(edificios) AS edificios, SUM(academia) AS academia, SUM(armeria) AS armeria
             FROM (
               SELECT DISTINCT a.fam_id, a.fam_nom, a.fam_est,
               CASE WHEN b.bod_id = '01' THEN 1 ELSE 0 END residencias,
			   CASE WHEN b.bod_id = '04' THEN 1 ELSE 0 END financiero,
			   CASE WHEN b.bod_id = '06' THEN 1 ELSE 0 END talleres,
			   CASE WHEN b.bod_id = '09' THEN 1 ELSE 0 END edificios,
			   CASE WHEN b.bod_id = '10' THEN 1 ELSE 0 END academia,
			   CASE WHEN b.bod_id = '11' THEN 1 ELSE 0 END armeria


               FROM
               APP_POS.dbo.PRO_FAMILIA AS a
               LEFT JOIN APP_POS.dbo.PRO_FAMILIA_BODEGA b ON b.fam_id = a.fam_id

             ) AS query
             GROUP BY fam_id, fam_nom, fam_est*/";
    $q3 = $pdo->prepare($sql3);
    $q3->execute(array());
    $bodegas = $q3->fetchAll(PDO::FETCH_ASSOC);
    Database::disconnect_sqlsrv();

    foreach ($bodegas as $key => $b) {
      // code...

      $sub_array = array(
        'DT_RowId' => $b['ssf_id'],
        'familia' => $b['grupo'],
        'estado' => $b['ssf_est'],
        'residencias' => $b['residencias'],
        'financiero' => $b['financiero'],
        'talleres' => $b['talleres'],
        'edificios' => $b['edificios'],
        'academia' => $b['academia'],
        'armeria' => $b['armeria'],
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
    //fin

  }

  //opcion 14
  static function updateFamiliaBodega()
  {
    $bodega = $_POST['bodega'];
    $familia = $_POST['familia'];

    //echo $bodega .' -- '.$familia;
    $yes = '';
    $pdo = Database::connect_sqlsrv();
    try {
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql0 = "IF NOT EXISTS (SELECT * FROM APP_POS.dbo.PRO_SSFAMILIA_BODEGA
                              WHERE bod_id = '$bodega' AND ssf_id = '$familia')
                  BEGIN
                    INSERT INTO APP_POS.dbo.PRO_SSFAMILIA_BODEGA (bod_id, ssf_id)
                    VALUES ('$bodega','$familia');
                  END
                ELSE
                  BEGIN
                    DELETE FROM APP_POS.dbo.PRO_SSFAMILIA_BODEGA
                    WHERE bod_id = '$bodega' AND ssf_id = '$familia'
                  END";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array());
      $pdo->commit();
    } catch (PDOException $e) {

      $yes = array('msg' => 'ERROR', 'id' => $e);
      //echo json_encode($yes);
      try {
        $pdo->rollBack();
      } catch (Exception $e2) {
        $yes = array('msg' => 'ERROR', 'id' => $e2);

      }
      Database::disconnect_sqlsrv();
    }

    echo json_encode($yes);


  }

  //opcion 16
  static function addNewInsumoRequisicion()
  {

    $yes = '';
    $pdo = Database::connect_sqlsrv();
    try {
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql0 = "SELECT TOP 1 productoOrden FROM APP_POS.dbo.RequisicionDetalle
               WHERE requisicionId = ?
               ORDER BY productoOrden DESC";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($_POST['requisicion_id']));
      $reng_num = $q0->fetch();

      $x = $reng_num['productoOrden'] + 1;

      $sql3 = "INSERT INTO APP_POS.dbo.RequisicionDetalle (requisicionId, productoId, cantidadSolicitada, productoOrden, productoStatus)
      values(?,?,?,?,?)";
      $q3 = $pdo->prepare($sql3);
      $q3->execute(array($_POST['requisicion_id'], $_POST['Pro_idint'], $_POST['cantidad'], $x, 1));

      $yes = array('msg' => 'OK', 'message' => 'Insumo agregado');
      $pdo->commit();
      //echo json_encode($yes);

    } catch (PDOException $e) {

      $yes = array('msg' => 'ERROR', 'id' => $e);
      //echo json_encode($yes);
      try {
        $pdo->rollBack();
      } catch (Exception $e2) {
        $yes = array('msg' => 'ERROR', 'id' => $e2);

      }
    }
    echo json_encode($yes);

    Database::disconnect_sqlsrv();
  }

  //opcion 17
  static function deleteInsumoRequisicion()
  {
    $yes = '';
    $requisicion_id = $_POST['requisicion_id'];
    $producto = $_POST['Pro_idint'];
    $pdo = Database::connect_sqlsrv();
    try {
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql3 = "DELETE FROM APP_POS.dbo.RequisicionDetalle WHERE requisicionId = ? AND productoId = ?";
      $q3 = $pdo->prepare($sql3);
      $q3->execute(array($_POST['requisicion_id'], $_POST['Pro_idint']));

      $yes = array('msg' => 'OK', 'message' => 'Insumo eliminado');
      $pdo->commit();
      //echo json_encode($yes);

    } catch (PDOException $e) {

      $yes = array('msg' => 'ERROR', 'id' => $e);
      //echo json_encode($yes);
      try {
        $pdo->rollBack();
      } catch (Exception $e2) {
        $yes = array('msg' => 'ERROR', 'id' => $e2);

      }
    }
    echo json_encode($yes);

    Database::disconnect_sqlsrv();

  }

  static function getPerfilBodega($bodega, $tipo)
  {
    $yes = '';

    $persona = $_SESSION['id_persona'];
    $pdo = Database::connect_sqlsrv();
    try {
      $pdo->beginTransaction();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql3 = "SELECT a.id_persona, a.perfilCodigo
               FROM APP_POS.dbo.RequisicionPerfilEmpleado a
               INNER JOIN APP_POS.dbo.RequisicionPerfil b ON a.perfilCodigo = b.perfilCodigo
               WHERE a.id_persona = ? AND b.bodegaId = ?";
      $q3 = $pdo->prepare($sql3);
      $q3->execute(array($persona, $bodega));
      $perfil = $q3->fetch();

      $profile = (!empty($perfil['perfilCodigo'])) ? $perfil['perfilCodigo'] : 1;

      $yes = array('perfil' => $profile);

      //echo json_encode($yes);

    } catch (PDOException $e) {

      $yes = array('msg' => 'ERROR', 'id' => $e);
      //echo json_encode($yes);
      try {
        $pdo->rollBack();
      } catch (Exception $e2) {
        $yes = array('msg' => 'ERROR', 'id' => $e2);

      }
    }
    if ($tipo == 1) {
      echo json_encode($yes);
    } else if ($tipo == 2) {
      return $yes;
    }

    Database::disconnect_sqlsrv();
  }
}

if (isset($_POST['opcion']) || isset($_GET['opcion'])) {

  $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];
  switch ($opcion) {
    case 0:
      Requisicion::getPrivilegios($_GET['tipo']);
      break;
    case 1:
      Requisicion::getRequisiciones();
      break;
    case 2:
      Requisicion::getRequisicionById();
      break;
    case 3:
      Requisicion::getInsumosByRequisicion();
      break;
    case 4:
      // Requisicion::crearSolicitudRequisicion();
      break;
    case 5:
      Requisicion::retornaDireccionEmpleado();
      break;
    case 6:
      Requisicion::getBodegasPermiso();
      break;
    case 7:
      //opcion 7
      Requisicion::getProductoById();
      break;
    case 8:
      //opcion 8
      Requisicion::getUnidades();
      break;
    case 9:
      Requisicion::hojaRequisicion();
      break;
    //opcion 10
    case 10:
      Requisicion::updateRequisicion();
      break;
    case 11:
      Requisicion::updateVehiculoSolicitud();
      break;
    case 12:
      Requisicion::validarBodega();
      break;
    case 13:
      Requisicion::getFamiliasBodega();
      break;
    case 14:
      Requisicion::updateFamiliaBodega();
      break;
    case 15:
      Requisicion::getBodegasParaEvaluar($_POST['id_bodega']);
      break;
    case 16:
      Requisicion::addNewInsumoRequisicion();
      break;
    case 17:
      Requisicion::deleteInsumoRequisicion();
      break;
    case 18:
      Requisicion::getReporteFormulariosMensual();
      break;
  }
}
