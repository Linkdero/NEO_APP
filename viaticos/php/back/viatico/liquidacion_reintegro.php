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
  $parametros = substr($renglon, 0, -1);
  //$bln_confirma=$_POST['confirma'];

  $reintegro_hospedaje=$_POST['id_reintegro_hospedaje'];
  $reintegro_alimentacion=$_POST['id_reintegro_alimentacion'];
  $otros_gastos=$_POST['id_otros_gastos'];
  $fecha_liquidacion=date('Y-m-d', strtotime($_POST['id_fecha_liquidacion']));

  $clase= new viaticos;

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "UPDATE vt_nombramiento_detalle
          SET
            hospedaje=?,
            reintegro_alimentacion=?,
            otros_gastos=?,
            fecha_liquidacion=?
          WHERE vt_nombramiento=? AND reng_num IN ($parametros)";
  $q = $pdo->prepare($sql);
  $q->execute(array(
    $reintegro_hospedaje,
    $reintegro_alimentacion,
    $otros_gastos,
    $fecha_liquidacion,
    $vt_nombramiento
  ));

  $sql2 = "EXEC sp_sel_valida_exterior ?";
  $q2 = $pdo->prepare($sql2);
  $q2->execute(array($vt_nombramiento));
  $pais = $q2->fetch();

  $tipo_documento;
  if($pais['id_pais']<>'GT'){
    $tipo_documento=1016;
    //echo $tipo_nombramiento;
  }else{
    $tipo_documento=1015;
  }

  /*$sql3 = "EXEC sp_sel_datos_para_reintegro_complemento ?";
  $q3 = $pdo->prepare($sql3);
  $q3->execute(array($vt_nombramiento));
  $empleados = $q3->fetchAll();*/

  $empleados = $clase->get_personas_para_liquidar($vt_nombramiento,$parametros);

  foreach ($empleados as $e){
    //echo $e['dias_nombramiento'];
    $correlativo=$e["vt_nombramiento"];
    $dias=$e["dias_nombramiento"];
    $hs1=$e["hora_salida_nombramiento"];
    $hr1=$e["hora_regreso_nombramiento"];
    $hsp=$e["bln_hospedaje"];
    $alm=$e["bln_alimentacion"];
    $ext=$e["id_pais"];
    $tipo=$e["id_tipo_nombramiento"];
    $dia_inicio=$e["dia_inicio"];

    if ($ext=="GT")  //porcentaje locales
    {

      $sql = "EXEC sp_sel_devuelve_cuota_local ?";

      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($e['sueldo']));
      $quote = $stmt->fetch();

        $cuota= $quote["monto"];
        $desayuno= $quote["porcentaje_1"];
        $almuerzo= $quote["porcentaje_2"];
        $cena    = $quote["porcentaje_3"];
        $hospedaje=$quote["porcentaje_4"];
        $moneda="Q.";

    }
    else   // valores para viatico fuera del pais
    {

      $sql = "EXEC sp_sel_devuelve_cuota_exter ?, ?";

      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($e['id_grupo'],$e['id_categoria']));
      $quote = $stmt->fetch();



        $cuota=$quote["monto"];
        $desayuno=15;
        $almuerzo=20;
        $cena=15;
        $hospedaje=50;
        $moneda="USD$";

    }
    //$porcentaje_entregar=$e["porcentaje_entregar"];
    $dias_real=$e['dias_real'];
    $hs2=$e['hora_real_salida'];
    $hr2=$e['hora_real_regreso'];
    $porcentaje_proyectado=$clase->devuelve_porcentaje($dias,$hs1,$hr1,$hsp,$desayuno,$almuerzo,$cena,$hospedaje,$alm,$tipo,$dia_inicio);
    //$porcentaje_real=$clase->devuelve_porcentaje($dias_real,$hs2,$hr2,$hsp,$desayuno,$almuerzo,$cena,$hospedaje,$alm,$tipo,$dia_inicio);
    $porcentaje_real = $clase->porcentaje($e['id_pais'],$e['Salida_real_detalle'],$e['entrada_real_detalle'],$e['hora_i'],$e['hora_f']);

    $cuota_diaria_real=$cuota*$porcentaje_real;

    /*if($renglon==$e['reng_num']){
      $sql2 = "UPDATE vt_nombramiento_detalle SET monto_asignado=? WHERE vt_nombramiento=? AND reng_num=?";
      $q2 = $pdo->prepare($sql2);
      $q2->execute(array($cuota_diaria_real,$vt_nombramiento,$e['reng_num']));
    }*/

    if($e['bln_confirma']==1){
      if($e['bln_cheque']==1){
        $sql2 = "UPDATE vt_nombramiento_detalle SET otros_gastos=?, porcentaje_real=? WHERE vt_nombramiento=? AND reng_num =?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($otros_gastos,$porcentaje_real,$vt_nombramiento,$e['reng_num']));
      }else{
        $sql2 = "UPDATE vt_nombramiento_detalle SET porcentaje_real=? WHERE vt_nombramiento=? AND reng_num =?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($porcentaje_real,$vt_nombramiento,$e['reng_num']));
      }

      $sql = "EXEC sp_sel_valida_exterior ?";
      $q = $pdo->prepare($sql);
      $q->execute(array($vt_nombramiento));
      $pais = $q->fetch();

      if($clase->validar_fecha_forms_unificados($vt_nombramiento) == true){
        $sql2 = "UPDATE vt_nombramiento_detalle
                 SET nro_frm_vt_liq = vt_nombramiento_detalle.nro_frm_vt_ant
                 FROM vt_nombramiento
                 INNER JOIN vt_nombramiento_detalle
                 ON (vt_nombramiento.vt_nombramiento = vt_nombramiento_detalle.vt_nombramiento)
                 WHERE vt_nombramiento.vt_nombramiento=? AND vt_nombramiento_detalle.reng_num = ?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($vt_nombramiento,$e['reng_num']));
      }else{
        $tipo_documento=1017;

        $sql2 = "EXEC procesa_formulario_liquidacion @correlativo=?, @tipo_documento=?, @renglon=?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($vt_nombramiento,$tipo_documento,$e['reng_num']));
        echo $e['reng_num'];
      }



    }

      /*$sub_array = array(
        'id'=>$e['id_empleado'],
        'vt_nombramiento'=>$correlativo,
        'id_empleado'=>$e['id_empleado'],
        'dias'=>$dias,
        'porcentaje'=>$porcentaje,
        'moneda'=>$moneda,
        'cuota_diaria'=>number_format(($cuota*$porcentaje), 2, ".", ""),
        'empleado'=>$e['nombre'],
        'sueldo'=>number_format($e['sueldo'], 2, ".", "")
      );
      $data[] = $sub_array;
      //$data[]=$e;*/

  }

  $valor_anterior = array(
    'vt_nombramiento'=>'',
    'id_status'=>''
  );

  $valor_nuevo = array(
    'vt_nombramiento'=>$vt_nombramiento,
    'id_status'=>'Se generó VL',
    'empleados'=>$parametros,
    'fecha'=>date('Y-m-d H:i:s')
  );

  $log = "VALUES(5, 1121, 'vt_nombramiento', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
  $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
  $q = $pdo->prepare($sql);
  $q->execute(array());

  /*echo $tipo_documento;
  $sql3 = "EXEC procesa_formulario @correlativo=?, @tipo_documento=?, @renglon=?";
  $q3 = $pdo->prepare($sql3);
  $q3->execute(array($vt_nombramiento,$tipo_documento,$renglon));*/

  /*$sql = "UPDATE vt_nombramiento_detalle
          SET
            fecha_salida=?,
            hora_salida=?,
            fecha_llegada_lugar=?,
            hora_llegada_lugar=?,
            fecha_salida_lugar=?,
            hora_salida_lugar=?,
            fecha_regreso=?,
            hora_regreso=?,
            bln_confirma=?,
          WHERE vt_nombramiento=? AND id_empleado=?";
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
    $id_empleado
  ));
*/
  Database::disconnect_sqlsrv();


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
