<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';
  date_default_timezone_set('America/Guatemala');

  $id_persona = $_POST['id_persona'];
  $id_permiso = $_POST['id_permiso'];
  $ffijo = ($_POST['ffijo'] == 'true') ? 1 : 0;

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "UPDATE [SAAS_APP].[dbo].[tbl_persona_horario]
            SET id_horario =" . $id_permiso . " 
            WHERE id_persona =" . $id_persona;

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  Database::disconnect_sqlsrv();

else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;
