<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../empleados/php/back/functions.php';
?>
  <?php

  $id_persona = $_POST['id_persona'];
  $clase_e = new empleado;

  if (is_numeric($id_persona)) {
    $empleado = $clase_e->get_empleado_by_id_ficha($id_persona);
    $estado = $clase_e->get_empleado_estado_by_id($id_persona);
    $data = array();
    $direccion = $empleado['dir_funcional'];
    if ($empleado['id_tipo'] == 2) {
      $direccion = $empleado['dir_nominal'];
    } else {
      if ($empleado['id_tipo'] == 4) {
        $dir_ = $clase_e->get_direcciones_saas_by_id($empleado['id_dirapy']);
        $direccion = $dir_['descripcion'];
      }
    }

    if (!empty($empleado['primer_nombre'])) {
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT id_persona, id_vacuna, id_vacuna_dosis, id_vacuna_tipo, fecha_vacunacion,
              CASE
              WHEN id_vacuna_tipo = 1 THEN 'ASTRAZENECA'
              WHEN id_vacuna_tipo = 2 THEN 'J&J'
              WHEN id_vacuna_tipo = 3 THEN 'JANSSEN'
              WHEN id_vacuna_tipo = 4 THEN 'MODERNA'
              WHEN id_vacuna_tipo = 5 THEN 'PFIZER'
              WHEN id_vacuna_tipo = 6 THEN 'SPUTNIK V' ELSE
              'SIN VACUNA' END AS tipo_vacuna
              FROM rrhh_persona_vacuna_covid WHERE id_persona = ?
              ORDER BY id_vacuna ASC";
      $p = $pdo->prepare($sql);
      $p->execute(array($id_persona));
      $vacunas = $p->fetchAll();
      Database::disconnect_sqlsrv();
      $nombre = $empleado['primer_nombre'] . ' ' . $empleado['segundo_nombre'] . ' ' . $empleado['tercer_nombre'] . ' ' .
        $empleado['primer_apellido'] . ' ' . $empleado['segundo_apellido'] . ' ' . $empleado['tercer_apellido'];
      $foto = $clase_e->get_empleado_fotografia($id_persona);
      $encoded_image = base64_encode($foto['fotografia']);
      $Hinh = "<img class='img-fluid mb-3' src='data:image/jpeg;base64,{$encoded_image}' > ";
      $resultado = '';
      if ($estado['estado_persona'] == 1) {
        $status = '<span class="badge badge-success">Activo</span>';
      } else {
        $status = '<span class="badge badge-danger">Inactivo</span>';
      }

      $resultado .= '<div class="row card-body-slide"><div class="col-sm-3 ">';
      $resultado .= '<div class="img-contenedor_profile border-md-right border-light" style="border-radius:50%">';
      $resultado .= $Hinh;
      $resultado .= '</div>';
      $resultado .= '</div><div class="col-sm-6">';
      $resultado .= '<h1>Gafete: ' . $empleado['id_persona'] . '</h1>';
      $resultado .= '<h3>' . $nombre . ' ' . $status . '</h3>';
      $resultado .= '<h5 class="text-muted">' . $direccion . '</h5>';

      $resultado .= '</div><div class="col-sm-3"><h2> Control de Vacunas </h2>';
      if (count($vacunas) > 0) {
        $resultado .= '<table class="table table-sm table-striped">
        <thead>
        <th class="text-center">Dosis</th>
        <th class="text-center">Tipo</th>
        <th class="text-center">Fecha</th>
        </thead>
        ';

        foreach ($vacunas as $v) {
          $tipo = 0;

          $resultado .= '<tr>';
          $resultado .= '<td>';
          $resultado .= ($v['id_vacuna_dosis'] == 1) ? 'Primera Dosis' : 'Segunda Dosis';
          $resultado .= '</td>';
          $resultado .= '<td>' . $v['tipo_vacuna'] . '</td>';
          $resultado .= '<td>' . fecha_dmy($v['fecha_vacunacion']) . '</td>';
          $resultado .= '</tr>';
        }
      } else {
        $resultado .= '<h5 class="text-danger">Empleado sin vacunas</h5>';
      }

      $resultado .= '</div>';

      $clase_e->save_employee_income($id_persona);
      echo $resultado;
    } else {
      echo 'No existe este empleado';
    }
  } else {
    echo 'Debe ingresar un valor numérico';
  }






else :
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


  ?>
