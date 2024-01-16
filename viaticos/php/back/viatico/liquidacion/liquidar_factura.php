<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../../empleados/php/back/functions.php';
  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');
  $clase = new viaticos;

  $opcion = $_POST['opcion'];
  $vt_nombramiento=$_POST['id_viatico'];
  $id_persona=$_POST['id_persona'];
  $renglon=$_POST['id_renglon'];
  $fecha = (!empty($_POST['fecha'])) ? date('Y-m-d',strtotime($_POST['fecha'])) : NULL;
  $creado_en = date('Y-m-d H:i:s');
  $id_pais = $_POST['id_pais'];
  $anotaciones_alimentacion = (!empty($_POST['anotaciones_alimentacion'])) ? strtoupper($_POST['anotaciones_alimentacion']) : NULL;
  $anotaciones_hospedaje = (!empty($_POST['anotaciones_hospedaje'])) ? strtoupper($_POST['anotaciones_hospedaje']) : NULL;
  //$parametros=str_replace("'","()",$renglon);
  //$bln_confirma=$_POST['confirma'];

  $parametros = substr($id_persona, 1);

  $facturas = $_POST['facturas'];

  $yes = '';

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $factura = $clase->verificarFechaGuardada($vt_nombramiento,$parametros,$fecha);

    if($opcion == 2){
      //inicio
      if(!empty($factura['fecha'])){
        //Actualizar
        $dia_id = "";
        foreach($facturas AS $f){
          $confirma = ($f['bln_confirma'] == 'true') ? 1 : 0;
          $error = ($f['error'] == 'true') ? 1 : 0;
          if($f['bln_confirma'] == 'true'){
            $sql2 = "UPDATE vt_nombramiento_factura SET

              factura_serie=?,factura_numero = ?,
              factura_monto = ?,
              factura_nit = ?,factura_propina = ?,
              factura_status=?,ingresada_por=?,ingresada_en=?,bln_confirma=?,
              factura_tiempo=?,factura_descuento=?,lugarId=?, factura_fecha=?, flag_error = ?

          WHERE factura_id = ?";
            $q2 = $pdo->prepare($sql2);
            $q2->execute(
              array(
                strval(strtoupper($f['serie'])),strval($f['numero']),floatval($f['monto']),strval($f['nit']),floatval($f['propina']),
                1,$_SESSION['id_persona'],$creado_en,$confirma,$f['tiempo'],0,$f['proveedor'],date('Y-m-d',strtotime($f['fecha'])),
                $error,
                $f['factura_id']
              )
            );
          }else{
            $sql2 = "UPDATE vt_nombramiento_factura SET

              factura_serie=NULL,factura_numero = NULL,
              factura_monto = NULL,
              factura_nit = NULL,factura_propina = NULL,
              factura_status=0,ingresada_por=NULL,ingresada_en=NULL,bln_confirma=0,
              factura_descuento=NULL,lugarId=NULL, factura_fecha = NULL, flag_error = 0

          WHERE factura_id = ?";
            $q2 = $pdo->prepare($sql2);
            $q2->execute(
              array(
                $f['factura_id']
              )
            );
          }
          $dia_id = $f['dia_id'];
        }
        $sql = "UPDATE vt_nombramiento_dia_comision SET
                dia_observaciones_al = ?,
                dia_observaciones_hos = ?
        WHERE dia_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($anotaciones_alimentacion,$anotaciones_hospedaje,$dia_id));
        $yes = array('msg'=>'OK','message'=>'Facturas ingresadas');
      }else{
        //crear

        if(!empty($fecha)){
          $sql = "INSERT INTO vt_nombramiento_dia_comision (
                  vt_nombramiento,
                  id_empleado,
                  fecha,
                  dia_status,
                  dia_observaciones_al,
                  dia_observaciones_hos
          ) VALUES (?,?,?,?,?,?)";

          $stmt = $pdo->prepare($sql);
          $stmt->execute(array($vt_nombramiento,$parametros,$fecha,1,$anotaciones_alimentacion,$anotaciones_hospedaje));
          //$response = $stmt->fetch();

          $lastId = $pdo->lastInsertId();
          foreach($facturas AS $f){
            $confirma = ($f['bln_confirma'] == 'true') ? 1 : 0;
            //$x ++;
            if($f['bln_confirma'] == 'true'){
              $sql2 = "INSERT INTO vt_nombramiento_factura (dia_id,factura_tipo,factura_serie,factura_numero,
                factura_monto,
                factura_nit,factura_propina,
                factura_status,ingresada_por,ingresada_en,bln_confirma,factura_tiempo,factura_descuento,lugarId,factura_fecha
              )
              values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
              $q2 = $pdo->prepare($sql2);
              $q2->execute(
                array(
                  $lastId,intval($f['tipo']),strval(strtoupper($f['serie'])),strval($f['numero']),floatval($f['monto']),strval($f['nit']),floatval($f['propina']),
                  1,$_SESSION['id_persona'],$creado_en,$confirma,intval($f['tiempo']),0,$f['proveedor'], date('Y-m-d',strtotime($f['fecha']))
                )
              );
            }else{
              $sql2 = "INSERT INTO vt_nombramiento_factura (dia_id,factura_tipo,
                factura_status,ingresada_por,ingresada_en,bln_confirma,factura_tiempo
              )
              values(?,?,?,?,?,?,?)";
              $q2 = $pdo->prepare($sql2);
              $q2->execute(
                array(
                  $lastId,$f['tipo'],1,$_SESSION['id_persona'],$creado_en,0,$f['tiempo']
                )
              );
            }

          }

          //return $response;

          $yes = array('msg'=>'OK','message'=>'Facturas actualizadas');
        }

      }
      //fin
    }else if($opcion == 3){
      $bugs = 0;
      $e = $clase->get_empleado_datos_por_nombramiento($vt_nombramiento,$parametros);
      $fecha1 = $e['fecha_salida_saas'];
      $fecha2 = $e['fecha_regreso_saas'];

      //echo $fecha1 . ' || '.$fecha2;

      foreach($facturas AS $f){
        $confirma = ($f['bln_confirma'] == 'true') ? 1 : 0;
        if($f['bln_confirma'] == 'true'){
          $date = date('Y-m-d',strtotime($f['fecha']));

          if($date< $fecha1 || $date>$fecha2){
            $bugs ++;
          }
        }
      }

      if($bugs > 0){

        $yes = array('msg'=>'ERROR','message'=>'Está intentando agregar una factura a una fecha que no se encuentra dentro de las fechas de la comisión.');
      }else{
        foreach($facturas AS $f){
          $fac = $clase->get_facturas_by_dia_gastos($vt_nombramiento,$parametros,$f['factura_id'],3);

          if(!empty($fac['factura_id'])){
            //update
            //echo '1';
            if($f['bln_confirma'] == 'true'){
              $confirma = ($f['bln_confirma'] == 'true') ? 1 : 0;
              $date = date('Y-m-d',strtotime($f['fecha']));

              //echo '2';

              $sqlup = "UPDATE vt_nombramiento_factura SET

                factura_serie=?,factura_numero = ?,
                factura_monto = ?,
                factura_nit = ?,factura_propina = ?,
                factura_status=?,ingresada_por=?,ingresada_en=?,bln_confirma=?,
                factura_tiempo=?,factura_descuento=?,lugarId=?, factura_fecha=?, motivo_gastos = ?

            WHERE factura_id = ?";
              $qup = $pdo->prepare($sqlup);
              $qup->execute(
                array(
                  strval(strtoupper($f['serie'])),strval($f['numero']),floatval($f['monto']),strval($f['nit']),floatval($f['propina']),
                  1,$_SESSION['id_persona'],$creado_en,$confirma,5,0,$f['proveedor'],$date,strval(strtoupper($f['motivo_gastos'])),
                  $f['factura_id']
                )
              );
            }else{
              //echo '3';
              $sql2 = "UPDATE vt_nombramiento_factura SET

                factura_serie=NULL,factura_numero = NULL,
                factura_monto = NULL,
                factura_nit = NULL,factura_propina = NULL,
                factura_status=0,ingresada_por=NULL,ingresada_en=NULL,bln_confirma=0,
                factura_descuento=NULL,lugarId=NULL, factura_fecha = NULL, motivo_gastos = NULL

            WHERE factura_id = ?";
              $q2 = $pdo->prepare($sql2);
              $q2->execute(
                array(
                  $f['factura_id']
                )
              );
            }
          }else{
            //insert
            //echo '4';


            if(!empty($f['numero'])){
              //echo '5';
              $sql = "SELECT dia_id FROM vt_nombramiento_dia_comision
              WHERE vt_nombramiento = ? AND id_empleado = ? AND fecha = ?";

              $stmt = $pdo->prepare($sql);
              $stmt->execute(array($vt_nombramiento, $parametros, $f['fecha']));
              $datos = $stmt->fetch();

              $diaId = $datos['dia_id'];

              if(empty($datos['dia_id'])){
                //inicio
                $sql = "INSERT INTO vt_nombramiento_dia_comision (
                        vt_nombramiento,
                        id_empleado,
                        fecha,
                        dia_status,
                        dia_observaciones_al,
                        dia_observaciones_hos
                ) VALUES (?,?,?,?,?,?)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute(array($vt_nombramiento,$parametros,$fecha,1,$anotaciones_alimentacion,$anotaciones_hospedaje));
                //$response = $stmt->fetch();

                $diaId = $pdo->lastInsertId();
                //fin
              }

              $sql2 = "INSERT INTO vt_nombramiento_factura (dia_id,factura_tipo,factura_serie,factura_numero,
                factura_monto,
                factura_nit,factura_propina,
                factura_status,ingresada_por,ingresada_en,bln_confirma,factura_tiempo,factura_descuento,lugarId,factura_fecha, motivo_gastos
              )
              values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
              $q2 = $pdo->prepare($sql2);
              $q2->execute(
                array(
                  $diaId,3,strval(strtoupper($f['serie'])),strval($f['numero']),floatval($f['monto']),strval($f['nit']),floatval($f['propina']),
                  1,$_SESSION['id_persona'],$creado_en,$confirma,intval($f['tiempo']),0,$f['proveedor'], date('Y-m-d',strtotime($f['fecha'])),strval(strtoupper($f['motivo_gastos']))
                )
              );
            }

          }
        }
        $yes = array('msg'=>'OK','message'=>'Facturas ingresadas');

      }

    }

    if($opcion == 4){
      //inicio
      foreach($facturas AS $f){
        $fac = $clase->get_facturas_by_dia_gastos($vt_nombramiento,$parametros,$f['factura_id'],1);

        if(!empty($fac['factura_id'])){
          //update
          //echo '1';
          //if($f['error'] == 'true'){
            $confirma = ($f['error'] == 'true') ? 1 : 0;

            $sqlup = "UPDATE vt_nombramiento_factura SET
            factura_aprobada = ?

            WHERE factura_id = ?";
            $qup = $pdo->prepare($sqlup);
            $qup->execute(
              array(
                $confirma,
                $f['factura_id']
              )
            );
          }

        //}
      }
      $yes = array('msg'=>'OK','message'=>'Facturas aprobadas');
      //fin
    }

    $pdo->commit();
  }catch (PDOException $e){
    $yes = array('msg'=>'ERROR','message'=>$e);
    try{ $pdo->rollBack();}catch(Exception $e2){
      $yes = array('msg'=>'ERROR','message'=>$e2);
    }
  }

  Database::disconnect_sqlsrv();

  echo json_encode($yes);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
