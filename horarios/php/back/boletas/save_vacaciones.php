<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');
  $dia_id = $_POST['dia_id'];
  $ddias = $_POST['ddias'];
  $udias = $_POST['udias'];
  $fsol = $_POST['fsol'];
  $fini = $_POST['fini'];
  $ffin = $_POST['ffin'];
  $fpre = $_POST['fpre'];
  $vobs = $_POST['vobs'];
  $id_persona = $_SESSION['id_persona'];
  $error = '';
  $tipo = $_POST['tipo'];
  $daysdisponibles = $_POST['daysdisponibles'];
  $daysapartados = $_POST['daysapartados'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if ($tipo == 'c') {
    $sql = "SELECT * FROM [app_vacaciones].[dbo].[VACACIONES]
          WHERE emp_id = (SELECT emp_id FROM [app_vacaciones].[dbo].[DIAS_ASIGNADOS] WHERE dia_id=?)
          AND (est_id=1 OR est_id=2 OR est_id=5)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($dia_id));
    $vacs = $stmt->fetchAll();

    $sql = "SELECT *
          FROM  [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA
          INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON DA.emp_id = F.id_persona
          INNER JOIN [SAAS_APP].[dbo].[rrhh_direcciones] D ON DA.emp_dir=D.id_direccion
          WHERE dia_id=" . $dia_id;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $empleado = $stmt->fetch();
    $vac_sub = $empleado['dia_asi'] - $empleado['dia_goz'];
    $vac_pen = $daysdisponibles - $udias;
    $vac_goz = $empleado['dia_goz'] + $udias;
    $sqlv = "SELECT
        CASE
        WHEN (b.dia_asi - b.dia_goz ) - d.dias_solicitados < 11 THEN 'errordias'
        ELSE '0' END AS validacion_dias,
        d.dias_solicitados,
        b.dia_asi, b.dia_goz,
        (b.dia_asi - b.dia_goz ) - d.dias_solicitados

        FROM APP_VACACIONES.dbo.VACACIONES a
        INNER JOIN APP_VACACIONES.dbo.DIAS_ASIGNADOS b ON a.emp_id = b.emp_id AND ISNULL(b.dia_liq,0) = 0 AND b.anio_id = a.anio_id
        INNER JOIN (SELECT emp_id, CONVERT(VARCHAR, vac_fch_ini, 23) AS f_ini,
        		   CONVERT(VARCHAR, vac_fch_fin,23) AS f_fin FROM APP_VACACIONES.dbo.VACACIONES
        		   WHERE est_id IN (1,2)) AS c ON a.emp_id = c.emp_id
        INNER JOIN (SELECT anio_id, emp_id, SUM(vac_sol) AS dias_solicitados FROM APP_VACACIONES.dbo.VACACIONES
        		   WHERE est_id IN (1,2)
        		   GROUP BY anio_id, emp_id) AS d ON a.emp_id = d.emp_id AND d.anio_id = ?
        WHERE a.emp_id = ?";
        $stmtv = $pdo->prepare($sqlv);
        $stmtv->execute(array($dia_id,$empleado['emp_id']));
        $vacsv = $stmtv->fetch();

        $error = (!empty($vacsv['validacion_dias']) == "errordias") ? 'Tiene una boleta de vacaciones generada en este período y el total excede a lo solicitado' : '';
    foreach ($vacs as $v) {
      if (($v['vac_fch_ini'] >= $fini) && ($v['vac_fch_ini'] <= $ffin)) {
        $error = "Las fechas solicitadas ya fueron apartadas "; //.$empleado['emp_id'];
      }
      /*if (($v['vac_fch_fin'] >= $fini) && ($v['vac_fch_fin'] <= $ffin)) {
        $error = "Las fechas solicitadas ya fueron apartadas 2 ".$empleado['emp_id'];
      }*/
    }

    if ($error == '') {
      $pfuncional = (!empty($empleado['p_contrato']) == "TALLERISTA") ? 7089 : $empleado['id_pfuncional'];
      $sql = "INSERT INTO [app_vacaciones].[dbo].[VACACIONES] (dia_id, emp_id, emp_dir, emp_pue, anio_id, est_id, vac_fch_tra,
        vac_fch_sol, vac_fch_ini, vac_fch_fin, vac_fch_pre, vac_dia, vac_dia_goz, vac_sub, vac_sol, vac_pen, vac_obs, vac_sol_asi,iddir_funcional)
          VALUES (?,?,?,?,?,?,GETDATE(),?,?,?,?,?,?,?,?,?,?,?,?);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array($dia_id, $empleado['emp_id'], $empleado['id_direc'], $pfuncional,
      $empleado['anio_id'], 1, $fsol, $fini, $ffin, $fpre, $empleado['dia_asi'], $empleado['dia_goz'], $vac_sub, $udias, $vac_pen, $vobs, $id_persona,$empleado['id_dirf']));

      echo json_encode(array(
        "status" => "200",
        "msg" => "Ok"
      ));
    } else {
      echo json_encode(array(
        "status" => false,
        "msg" => $error
      ));
    }
  } else if ($tipo == 'u') {

    $sql = "SELECT *
          FROM  [app_vacaciones].[dbo].[DIAS_ASIGNADOS] DA
          INNER JOIN [SAAS_APP].[dbo].[xxx_rrhh_Ficha] F ON DA.emp_id = F.id_persona
          INNER JOIN [SAAS_APP].[dbo].[rrhh_direcciones] D ON DA.emp_dir=D.id_direccion
          WHERE dia_id=(SELECT dia_id
          FROM [APP_VACACIONES].[dbo].[VACACIONES]
          WHERE vac_id=?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($dia_id));
    $empleado = $stmt->fetch();

    $vac_sub = $empleado['dia_asi'] - $empleado['dia_goz'];
    $vac_pen = $ddias - $udias;
    $vac_goz = $empleado['dia_goz'] + $udias;


    if ($error == '') {
      $vac_id = $dia_id;
      $sql = "UPDATE [APP_VACACIONES].[dbo].[VACACIONES]
              SET vac_fch_sol=?, vac_fch_ini=?, vac_fch_fin=?, vac_fch_pre=?, vac_dia=?, vac_dia_goz=?, vac_sub=?, vac_sol=?, vac_pen=?, vac_obs=?, vac_sol_asi=?
              WHERE vac_id=?;
              UPDATE [APP_VACACIONES].[dbo].[DIAS_ASIGNADOS]
              SET dia_goz=?
              WHERE dia_id=?";
      $stmt = $pdo->prepare($sql);

      // echo json_encode(array($fsol, $fini, $ffin, $fpre, $ddias, $empleado['dia_goz'], $vac_sub, $udias, $vac_pen, $vobs, $id_persona, $vac_id));
      $stmt->execute(array($fsol, $fini, $ffin, $fpre, $ddias, $empleado['dia_goz'], $vac_sub, $udias, $vac_pen, $vobs, $id_persona, $vac_id, $empleado['dia_goz'], $empleado['dia_id']));


      echo json_encode(array(
        "status" => "200",
        "msg" => "Ok"
      ));
    } else {
      echo json_encode(array(
        "status" => false,
        "msg" => $error
      ));
    }
  }

  Database::disconnect_sqlsrv();
else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
