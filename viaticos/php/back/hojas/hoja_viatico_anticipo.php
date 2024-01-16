
<?php
include_once '../../../../inc/functions.php';
include_once '../functions.php';
include_once 'individual/function_individual.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();
  $id_nombramiento=$_POST['nombramiento'];
  $id_empleado = (!empty($_POST['id_empleado'])) ? $_POST['id_empleado'] : NULL;
  $dia=$_POST['dia'];
  $mes=$_POST['mes'];
  $year=$_POST['year'];
  $empleados = array();
  $data = array();
  $clasei = new hoja;
  if($id_nombramiento > 0){
    //inicio
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$sql = "EXEC sp_sel_imprimible_viatico_anticipo @correlativo=?";

    $empleados = $clasei->get_anticipo_individual($id_nombramiento,$id_empleado);

    foreach($empleados as $e){
      $puestoComisionado = $clasei->getPuestoEmpleado($e['id_empleado'],$e['fecha_nom']);
      $contratoComisionado = $clasei->getContratoEmpleado($e['id_empleado'],$e['fecha_nom']);
      $apoyoEmpleado = $clasei->getApoyoEmpleado($e['id_empleado']);

      $puestoAutorizador = $clasei->getPuestoEmpleado($e['usr_autoriza'],$e['fecha_nom']);
      $cargo = (!empty($apoyoEmpleado['puesto'])) ? $apoyoEmpleado['puesto'] : '';
      $cargoN = ($puestoComisionado['fecha_inicio'] > $contratoComisionado['fecha_inicio']) ? $puestoComisionado['pueston'] : $contratoComisionado['pueston'];
      $cargoN = (!empty($cargoN)) ? $cargoN : $cargo;


      //$contratoAutorizador = $clasei->getContratoEmpleado($e['usr_autoriza'],$e['fecha_nom']);

      //echo json_encode($puestoAutorizador);

      $director_puesto=$puestoAutorizador['pueston'];
      if($e['nro_frm_vt_ant']>0){
        $sub_array = array(
          'bln_confirma'=>$e['bln_confirma'],
          'formulario'=>number_format($e['nro_frm_vt_ant'], 0, ".", ","),
          'destino'=>$e['lugar'],
          'monto_num'=>($e['bln_anticipo']==1)?number_format($e['monto_asignado'], 2, ".", ","):'0.00',
          'monto_letras'=>($e['bln_anticipo']==1)?$e['monto_en_letras']:'CERO CON 00/100',
          'num_dias'=>$e['dias'],
          'nombramiento'=>$e['nro_nombramiento'],
          'porcentaje_proyectado'=>number_format($e['porcentaje_proyectado'], 2, ".", ","),
          'fecha_solicitud'=>$e['fecha'],
          'emp'=>$e['nombre_completo'],
          'cargo'=>$cargoN,//(!empty($e['descripcion']))?$e['descripcion']:'d',
          'director'=>$e['nombre_emite'],
          'director_puesto'=>$director_puesto,//(!empty($e['nombre_puesto']))?$e['nombre_puesto']:$e['puesto_director'],
          'hoy'=>fechaCastellano($e['today']),
          'tipo_comision'=>($e['tipo_comision']!='')?$e['tipo_comision']:'',
          'resolucion' => $clasei->retornaResolucion($e['nro_frm_vt_ant'],1),
          'estado_viatico' => $e['estado_viatico']
        );
        $data[]=$sub_array;
      }
    }

    //fin
  }else{
    //inicio
    $sub_array = array(
      'bln_confirma'=> '',
      'formulario'=>number_format($id_empleado, 0, ".", ","),
      'destino'=> '',
      'monto_num'=> '',
      'monto_letras'=> 'FORMULARIO NO UTILIZADO',
      'num_dias'=> '',
      'nombramiento'=> '',
      'porcentaje_proyectado'=> '',
      'fecha_solicitud'=> '',
      'emp'=> '',
      'cargo'=> '',
      'director'=> '',
      'director_puesto'=> '',
      'hoy'=> '',
      'tipo_comision'=> '',
      'resolucion' =>  $clasei->retornaResolucion($id_empleado,1),
      'estado_viatico' =>  '',
    );
    $data[]=$sub_array;
    //fin
  }

  $output = array(
    "data"    => $data
  );
  echo json_encode($output);




else:
header("Location: ../index.php");
endif;

?>
