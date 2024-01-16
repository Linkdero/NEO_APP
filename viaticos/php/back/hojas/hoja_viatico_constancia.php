
<?php
include_once '../../../../inc/functions.php';
include_once '../functions.php';
include_once 'individual/function_individual.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();
  $empleados = array();
  $id_nombramiento=$_POST['nombramiento'];
  $id_empleado = (!empty($_POST['id_empleado'])) ? $_POST['id_empleado'] : NULL;

  $clasei = new hoja;
  if($id_nombramiento > 0){
    //inicio
    $clase1 = new viaticos;
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql0 = "EXEC sp_sel_valida_exterior ?";
    $q0 = $pdo->prepare($sql0);
    $q0->execute(array($id_nombramiento));
    $pais = $q0->fetch();

    $sql='';


    $empleados = $clasei->get_constancia_individual($id_nombramiento,$id_empleado);


    $data = array();
    if($pais['id_pais']=='GT'){
      foreach ($empleados as $key => $e) {

        $lugar = $e['lugar'];
        $dep='';
        if($e['descripcion_lugar']==1){
          $valor = $clase1->get_historial_viatico($e['vt_nombramiento']);
          $va = json_decode($valor['val_nuevo'], true);
          $keys = array_keys($va);

          $deptos=$clase1->get_departamentos($pais['id_pais']);
          $munis=$clase1->get_municipios($va['id_departamento']);

          foreach($munis["data"] as $m){
            $dep.=($va['id_municipio']==$m['id_municipio'])?$m['nombre']:'';
          }
          foreach($deptos["data"] as $d){
            $dep.=($va['id_departamento']==$d['id_departamento'])?'-'.$d['nombre']:'';
          }
          $lugar=$dep.'-'.$e['pais'];
        }

        $array_destinos=array();

        if($e['descripcion_lugar']==2)
        {
        	$valores = $clase1->get_historial_viatico_destinos($id_nombramiento);

        	foreach($valores as $valor){
            if($valor['bln_confirma']==1){
              $sub_array = array(
                'dep'=>$valor['muni'].'-'.$valor['depto'].'-'.$e['pais'],
                'f_ini'=>fecha_dmy($valor['fecha_ini']),
                'f_fin'=>fecha_dmy($valor['fecha_fin']),
                'h_ini'=>$valor['h_ini'],
                'h_fin'=>$valor['h_fin']
              );
              $array_destinos[] = $sub_array;
            }
          }
        }

        if(!empty($e['destino'])){
          $valores = $clase1->getDestinosByEmpleado($id_nombramiento,$e['id_empleado']);
          foreach($valores as $valor){

            $sub_array = array(
              'dep'=>$valor['municipio'].'-'.$valor['departamento'].'-'.$e['pais'],
              'f_ini'=>fecha_dmy($valor['fecha_ini']),
              'f_fin'=>fecha_dmy($valor['fecha_fin']),
              'h_ini'=>$valor['h_ini'],
              'h_fin'=>$valor['h_fin']
            );
            $array_destinos[] = $sub_array;
          }
        }

        $destino = (!empty($e['destino'])) ? $e['destino'] : '';
        $descripcionlugar = (!empty($destino)) ? 2 : $e['descripcion_lugar'];

        $puestoComisionado = $clasei->getPuestoEmpleado($e['id_empleado'],$e['fecha_nom']);
        $contratoComisionado = $clasei->getContratoEmpleado($e['id_empleado'],$e['fecha_nom']);
        $apoyoEmpleado = $clasei->getApoyoEmpleado($e['id_empleado']);

        $cargo = (!empty($apoyoEmpleado['puesto'])) ? $apoyoEmpleado['puesto'] : '';
        $cargoN = ($puestoComisionado['fecha_inicio'] > $contratoComisionado['fecha_inicio']) ? $puestoComisionado['pueston'] : $contratoComisionado['pueston'];

        $sub_array = array(
          'formulario'=>number_format($e['nro_frm_vt_cons'], 0, "", ","),
          'emp'=>$e['nombre_completo'],//.' || '.$destino,
          'cargo' => (!empty($cargoN)) ? $cargoN : $cargo,//$e['plaza'],
          'direccion'=>$e['descripcion_direccion'],
          'destino'=>$lugar,
          'hora_llegada'=>(!empty($empleados[$key][5])) ? $empleados[$key][5] : '',
          'hospedaje'=>(!empty($e['hospedaje']))?$e['hospedaje']:'',
          'alimentacion'=>(!empty($e['alimentacion']))?$e['alimentacion']:'',
          'fecha_llegada_lugar'=>(!empty($e['fecha_llegada_lugar'])) ? $e['fecha_llegada_lugar'] : '',
          'hora_salida'=>(!empty($e['descripcion_corta'])) ? $e['descripcion_corta'] : '',
          'fecha_salida_lugar'=>(!empty($e['fecha_salida_lugar'])) ? $e['fecha_salida_lugar'] : '',
          'resolucion' => $clasei->retornaResolucion($e['nro_frm_vt_cons'],2),
          'descripcion_lugar'=>$descripcionlugar,//$e['descripcion_lugar'],
          'estado_viatico'=>$e['estado_viatico'],
          'destinos'=>$array_destinos
        );
        $data[]=$sub_array;
      }
    }else{
      foreach($empleados as $e){
        $sub_array = array(
          'formulario'=>number_format($e['nro_frm_vt_cons'], 0, "", ","),
          'emp'=>$e['nombre_completo'],
          'cargo'=>$e['descripcion'],
          'direccion'=>$e['descripcion_direccion'],
          'destino'=>$e['lugar'],
          'hora_llegada'=>$e['descripcion_corta'],
          'hospedaje'=>(!empty($e['hospedaje']))?$e['hospedaje']:'',
          'alimentacion'=>(!empty($e['alimentacion']))?$e['alimentacion']:'',
          'fecha_llegada_lugar'=>$e['fecha_llegada_lugar'],
          'hora_salida'=>$e['descripcion_corta'],
          'fecha_salida_lugar'=>$e['fecha_salida_lugar'],
          'resolucion'=>$clasei->retornaResolucion($e['nro_frm_vt_cons'],2),
          'descripcion_lugar'=>$e['descripcion_lugar'],
          'estado_viatico'=>$e['estado_viatico'],
        );
        $data[]=$sub_array;
      }
    }
    //fin
  }else{
    //inicio
    $sub_array = array(
      'formulario'=>number_format($id_empleado, 0, "", ","),
      'emp'=>'FORMULARIO NO UTILIZADO',
      'cargo' => '',
      'direccion'=>'',
      'destino'=>'',
      'hora_llegada'=>'',
      'hospedaje'=>'',
      'alimentacion'=>'',
      'fecha_llegada_lugar'=>'',
      'hora_salida'=>'',
      'fecha_salida_lugar'=>'',
      'resolucion' => $clasei->retornaResolucion($id_empleado,2),
      'descripcion_lugar'=>'',
      'estado_viatico'=>'',
      'destinos'=>'',
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
