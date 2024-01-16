<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_persona = $_POST['id_persona'];
    $descripcion = $_POST['descripcion'];
    $tipo_movimiento = $_POST['tipo_movimiento'];
    $solvente = $_POST['solvente'];
    //$tipo=$_POST['tipo'];
    if (is_numeric($id_persona)) {

      $bodega_accesos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
      foreach ($bodega_accesos as $bodega_acceso) {
        $bodega = $bodega_acceso['id_bodega_insumo'];
        $movimientos = array();
        $movimientos = insumo::get_all_productos_asignados_actual_by_bodega($id_persona, $bodega);
        $movimientos_total = 0;
        foreach ($movimientos as $movimiento) {
          $movimientos_total++;
        }
      }
      $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
      $id_bodega = 0;

      foreach ($datos as $d) {
        $id_bodega = $d['id_bodega_insumo'];

      }

      if ($movimientos_total === 0 || (($id_bodega == 5907 || $id_bodega == 3552 || $id_bodega == 5066) && $tipo_movimiento == 1) ){
        $e = array();
        $e = empleado::get_empleado_by_id_ficha($id_persona);
        //$tipos = insumo::get_tipos_movimientos($tipo);//Ingreso a Bodega
        $estado = empleado::get_empleado_estado_by_id($id_persona);

        $data = array();
        $correlativo = 0;
        if (date('Y') == 2018) {
          $correlativo = 740;
        } else {
          $sql2 = "SELECT MAX(id_doc_solvencia) AS id FROM inv_solvencia_encabezado
               WHERE year=? AND id_bodega_insumo=?";
          $q2 = $pdo->prepare($sql2);
          $q2->execute(array(date('Y'), $id_bodega));
          $codigo = $q2->fetch();

          $sql2 = "SELECT COUNT(id_doc_solvencia) AS conteo FROM inv_solvencia_encabezado
               WHERE year=? AND id_bodega_insumo=?";
          $q2 = $pdo->prepare($sql2);
          $q2->execute(array(date('Y'), $id_bodega));
          $correlativo_ = $q2->fetch();


          if ($correlativo_['conteo'] == '') {
            $correlativo += 1;
          } else {
            $correlativo = $correlativo_['conteo'] + 1;
          }
        }

        $direccion = $e['id_dirf'];
        if ($e['id_tipo'] == 2) {
          $direccion = $e['id_dirs'];
        } else
          if ($e['id_tipo'] == 4) {
            $direccion = $e['id_dirapy'];
          }


        if ($e['primer_nombre'] != '') {
          if ($estado['estado_persona'] == 1) {
            //echo $id_bodega;

            $sql = "INSERT INTO inv_solvencia_encabezado
                (id_bodega_insumo,
                 year,
                 correlativo_solvencia,
                 id_persona_solvencia,
                 id_direccion_solvencia,
                 id_encargado,
                 id_tipo_solvencia,
                 id_estado,
                 fecha_solvencia,
                 observaciones,
                 id_solvente
               )VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            $q = $pdo->prepare($sql);
            $q->execute(array(
                $id_bodega,
                date('Y'),
                $correlativo,
                $id_persona,
                $direccion,
                $_SESSION['id_persona'],
                $tipo_movimiento,
                1,// establecer el circulo
                date('Y-m-d H:i:s'),
                $descripcion,
                $solvente
            ));

            $sql2 = "SELECT MAX(id_doc_solvencia) AS id FROM inv_solvencia_encabezado
                 WHERE id_encargado=?";
            $q2 = $pdo->prepare($sql2);
            $q2->execute(array($_SESSION['id_persona']));
            $codigo = $q2->fetch();


            $code = $codigo['id'];

            echo $code;
          } else {
            echo 'error1';
          }
        } else {
          echo 'error2';
        }
      }else{
        echo 'insolvente';
      }
    } else {
        echo 'error3';
    }

    Database::disconnect_sqlsrv();
//}
//echo $correlativo;


else:
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
