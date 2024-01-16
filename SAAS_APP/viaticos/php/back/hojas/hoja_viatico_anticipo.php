
<?php
include_once '../../../../inc/functions.php';
include_once '../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();
  $id_nombramiento=$_POST['nombramiento'];
  $dia=$_POST['dia'];
  $mes=$_POST['mes'];
  $year=$_POST['year'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //$sql = "EXEC sp_sel_imprimible_viatico_anticipo @correlativo=?";
  $sql = "EXEC sp_sel_imprimible_viatico_anticipo_anterior ?, ?, ?, ?";

  $p = $pdo->prepare($sql);
  $p->execute(array($id_nombramiento,$dia,$mes,$year));
  $empleados = $p->fetchAll();

  $data = array();

  foreach($empleados as $e){
    $sub_array = array(
      'formulario'=>number_format($e['nro_frm_vt_ant'], 0, ".", ","),
      'destino'=>$e['lugar'],
      'monto_num'=>($e['bln_anticipo']==1)?number_format($e['monto_asignado'], 2, ".", ","):'0.00',
      'monto_letras'=>($e['bln_anticipo']==1)?$e['monto_en_letras']:'CERO CON 00/100',
      'num_dias'=>$e['dias'],
      'nombramiento'=>$e['nro_nombramiento'],
      'porcentaje_proyectado'=>number_format($e['porcentaje_proyectado'], 2, ".", ","),
      'fecha_solicitud'=>$e['fecha'],
      'emp'=>$e['nombre_completo'],
      'cargo'=>$e['descripcion'],
      'director'=>$e['nombre_emite'],
      'director_puesto'=>$e['nombre_puesto'],
      'hoy'=>$e['hoy'],
      'tipo_comision'=>($e['tipo_comision']!='')?$e['tipo_comision']:'',
      'resolucion'=>$e['resolucion']

          /*'nombramiento'=> '# '.$e['correlativo'].' / '.$e['year'],
          'emp'=>mb_strtoupper($e['nombre'], 'UTF-8'),

          'f_i'=>$fecha_i,
          'f_f'=>$fecha_f,
          'motivo'=>$e['motivo'],


          'fecha_autorizacion_financiero'=>fecha_dmy($e['f_financiero']),
          'sueldo'=>number_format($sueldo['sueldo'], 2, ".", ","),
          'partida'=>$persona['partida'],
          'cargo'=>mb_strtoupper($persona['user_nom'], 'UTF-8'),
          'secr_nom'=>mb_strtoupper($secretary, 'UTF-8'),
          'secr_cargo'=>mb_strtoupper($secretario['user_nom'], 'UTF-8'),
          'fecha_autorizacion_secretaria'=>$fecha_entregado*/

                          );
        $data[]=$sub_array;
      }

      $output = array(
        "data"    => $data
      );

echo json_encode($output);




else:
header("Location: ../index.php");
endif;

?>
