<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $vt_nombramiento=$_POST['vt_nombramiento'];
  $id_empleado=$_POST['id_persona'];
  $renglon=$_POST['id_renglon'];
  //$parametros=str_replace("'","()",$renglon);
  //$bln_confirma=$_POST['confirma'];
  $parametros = substr($renglon, 0, -1);

  $fecha_salida_saas=date('Y-m-d', strtotime($_POST['fecha_salida_saas']));
  $hora_salida_saas=$_POST['hora_salida_saas'];
  $fecha_llegada_lugar=date('Y-m-d', strtotime($_POST['fecha_llegada_lugar']));
  $hora_llegada_lugar=$_POST['hora_llegada_lugar'];

  $fecha_salida_lugar=date('Y-m-d', strtotime($_POST['fecha_salida_lugar']));
  $hora_salida_lugar=$_POST['hora_salida_lugar'];
  $fecha_regreso_saas=date('Y-m-d', strtotime($_POST['fecha_regreso_saas']));
  $hora_regreso_saas=$_POST['hora_regreso_saas'];

  $transporte_salida=(!empty($_POST['transporte_salida'])) ? $_POST['transporte_salida'] : 0;
  $empresa_salida=$_POST['empresa_salida'];
  $nro_vuelo_salida=$_POST['nro_vuelo_salida'];
  $transporte_regreso=$_POST['transporte_regreso'];
  $empresa_regreso=$_POST['empresa_regreso'];
  $nro_vuelo_regreso=$_POST['nro_vuelo_regreso'];

  //echo $id_empleado;


  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if($transporte_salida==0){
    $sql = "UPDATE vt_nombramiento_detalle
            SET
              fecha_salida=?,
              hora_salida=?,
              fecha_llegada_lugar=?,
              hora_llegada_lugar=?,
              fecha_salida_lugar=?,
              hora_salida_lugar=?,
              fecha_regreso=?,
              hora_regreso=?,

              bln_confirma=?
            WHERE vt_nombramiento=? AND reng_num IN ($parametros) AND bln_confirma=?";
            echo $sql;
    $q = $pdo->prepare($sql);
    $q->execute(array(
      $fecha_salida_saas,
      $hora_salida_saas,
      $fecha_llegada_lugar,
      $hora_llegada_lugar,
      $fecha_salida_lugar,
      $hora_salida_lugar,
      $fecha_regreso_saas,
      $hora_regreso_saas,

      1,
      $vt_nombramiento,
      1

    ));
  }else{
    $sql = "UPDATE vt_nombramiento_detalle
            SET
              fecha_salida=?,
              hora_salida=?,
              fecha_llegada_lugar=?,
              hora_llegada_lugar=?,
              fecha_salida_lugar=?,
              hora_salida_lugar=?,
              fecha_regreso=?,
              hora_regreso=?,
              id_tipo_transporte_salida=?,
              id_emp_transporte_salida=?,
              nro_vuelo_salida=?,
              id_tipo_transporte_regreso=?,
              id_emp_transporte_regreso=?,
              nro_vuelo_regreso=?,
              bln_confirma=?
            WHERE vt_nombramiento=? AND reng_num IN ($parametros) AND bln_confirma=?";
            echo $sql;
    $q = $pdo->prepare($sql);
    $q->execute(array(
      $fecha_salida_saas,
      $hora_salida_saas,
      $fecha_llegada_lugar,
      $hora_llegada_lugar,
      $fecha_salida_lugar,
      $hora_salida_lugar,
      $fecha_regreso_saas,
      $hora_regreso_saas,

      $transporte_salida,
      $empresa_salida,
      $nro_vuelo_salida,
      $transporte_regreso,
      $empresa_regreso,
      $nro_vuelo_regreso,

      1,
      $vt_nombramiento,
      1

    ));
  }

  // el estado 39 es el estado futuro
  $sql = "EXEC sp_sel_valida_exterior ?";
  $q = $pdo->prepare($sql);
  $q->execute(array($vt_nombramiento));
  $pais = $q->fetch();

  $tipo_documento;
  if($pais['id_pais']<>'GT'){
    $tipo_documento=1016;
    //echo $tipo_nombramiento;
  }else{
    $tipo_documento=1015;
  }




//explode function breaks an string into array
$arr=explode(",",$parametros);

//print_r($arr);
if(viaticos::validar_fecha_forms_unificados($vt_nombramiento) == true){
  //forms unificados va, vc, vl

  foreach ($arr as $key => $value) {
    echo $value;
    $sql2 = "UPDATE vt_nombramiento_detalle
             SET nro_frm_vt_cons = vt_nombramiento_detalle.nro_frm_vt_ant
             FROM vt_nombramiento
             INNER JOIN vt_nombramiento_detalle
             ON (vt_nombramiento.vt_nombramiento = vt_nombramiento_detalle.vt_nombramiento)
             WHERE vt_nombramiento.vt_nombramiento=? AND vt_nombramiento_detalle.reng_num = ?";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array($vt_nombramiento,$value));
    echo $value;
  }

  $sql55 = "UPDATE vt_nombramiento SET id_status = ? WHERE vt_nombramiento = ?";
    $q55 = $pdo->prepare($sql55);
    $q55->execute(array(939,$vt_nombramiento));

}else{
  //forms distinto
  foreach ($arr as $key => $value) {
    echo $value;
    $sql2 = "EXEC procesa_formulario_liquidacion @correlativo=?, @tipo_documento=?, @renglon=?";
    $q2 = $pdo->prepare($sql2);
    $q2->execute(array($vt_nombramiento,$tipo_documento,$value));
    echo $value;
    // code...

  }

}

$valor_anterior = array(
  'vt_nombramiento'=>'',
  'id_status'=>''
);

$valor_nuevo = array(
  'vt_nombramiento'=>$vt_nombramiento,
  'id_status'=>'Se generó constancia',
  'empleados'=>$parametros,
  'fecha'=>date('Y-m-d H:i:s')
);

$log = "VALUES(5, 1121, 'vt_nombramiento', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
$sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
$q = $pdo->prepare($sql);
$q->execute(array());

/*



*/


  Database::disconnect_sqlsrv();


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
