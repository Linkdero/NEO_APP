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
  $parametros=str_replace("'","()",$renglon);
  //$bln_confirma=$_POST['confirma'];

  $fecha_salida_saas=date('Y-m-d', strtotime($_POST['fecha_salida_saas']));
  $hora_salida_saas=$_POST['hora_salida_saas'];
  $fecha_llegada_lugar=date('Y-m-d', strtotime($_POST['fecha_llegada_lugar']));
  $hora_llegada_lugar=$_POST['hora_llegada_lugar'];

  $fecha_salida_lugar=date('Y-m-d', strtotime($_POST['fecha_salida_lugar']));
  $hora_salida_lugar=$_POST['hora_salida_lugar'];
  $fecha_regreso_saas=date('Y-m-d', strtotime($_POST['fecha_regreso_saas']));
  $hora_regreso_saas=$_POST['hora_regreso_saas'];

  $transporte_salida=$_POST['transporte_salida'];
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




  Database::disconnect_sqlsrv();


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
