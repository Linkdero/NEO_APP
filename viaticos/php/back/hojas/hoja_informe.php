
<?php
include_once '../../../../inc/functions.php';
include_once '../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();
  $id_nombramiento=$_POST['id_nombramiento'];
  $id_empleado = (!empty($_POST['id_empleado'])) ? $_POST['id_empleado'] : NULL;
  $dia=$_POST['dia'];
  $mes=$_POST['mes'];
  $year=$_POST['year'];


  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $clase1 = new viaticos;
  $sql0 = "EXEC sp_sel_valida_exterior ?";
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array($id_nombramiento));
  $pais = $q0->fetch();

  $sql='';
  $sql = "EXEC sp_sel_informe_fecha_anterior @correlativo=?, @dia=?,@mes=?,@anio=?";

  $p = $pdo->prepare($sql);
  $p->execute(array($id_nombramiento,$dia,$mes,$year));
  $empleados = $p->fetchAll();

  $data = array();
  $data2 = array();
  $data_f1 = array();
  $data_f2 = array();
  $data_f3 = array();
  $data_f4 = array();
  $data_h1 = array();
  $data_h2 = array();
  $data_h3 = array();
  $data_h4 = array();

  foreach($empleados as $e){
    $data_f1 []= (date('d-m-Y', strtotime($e['fecha_salida'])));
    $data_f2 []= (date('d-m-Y', strtotime($e['fecha_llegada_lugar'])));
    $data_f3 []= (date('d-m-Y', strtotime($e['fecha_salida_lugar'])));
    $data_f4 []= (date('d-m-Y', strtotime($e['fecha_regreso'])));

    $data_h1 []= ($e['hora_salida_saas']);
    $data_h2 []= ($e['hora_llegada_lugar']);
    $data_h3 []= ($e['hora_salida_lugar']);
    $data_h4 []= ($e['hora_regreso_saas']);
  }

  foreach($empleados as $e){
    $tipo=1;
    if($e['fecha']>=date('Y-m-d',strtotime('2021-05-17'))){
      $tipo=2;
    }
    $lugar = ($e['pais']!='GUATEMALA')?$e['lugar'].'-'.$e['pais']:$e['lugar'];
    $dep='';
    $descripcionL = (!empty($e['descripcion_lugar']))?$e['descripcion_lugar']:0;
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


    $array_destinos = array();
    if($e['descripcion_lugar']==2)
    {
      $valores = $clase1->get_historial_viatico_destinos($id_nombramiento);
      foreach($valores as $valor){

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

    $destino = (!empty($e['destino'])) ? 2 : '';
    $dL = (!empty($e['descripcion_lugar'])) ? $e['descripcion_lugar'] : 0;
    $descripcionlugar = (!empty($destino)) ? $destino : $dL;

    $sub_array = array(
      'id_pais'=>$pais['id_pais'],
      'dia'=>$e['dia'],
      'mes'=>$e['mes'],
      'year'=>$year,//$e['anio'],
      'director'=>$e['pn'].' '.$e['sn'].' '.$e['pa'].' '.$e['sa'],
      'direccion'=>$e['descripcion'],
      'nombramiento'=>$e['nombramiento_direccion'],
      'dia_nombramiento'=>$e['dia_nombramiento'],
      'mes_nombramiento'=>$e['mes_nombramiento'],
      'year_nombramiento'=>$e['anio_nombramiento'],
      'lugar'=>$lugar,
      'dia_salida'=>$e['dia_salida'],
      'mes_salida'=>$e['mes_salida'],
      'year_salida'=>date('Y',strtotime($e['fecha_salida'])),//$e['anio'],
      'dia_regreso'=>$e['dia_regreso'],
      'mes_regreso'=>$e['mes_regreso'],
      'year_regreso'=>date('Y',strtotime($e['fecha_regreso'])),//$e['anio'],
      'motivo'=>$e['motivo'],
      'hora_salida_saas'=>($tipo==1)?min($data_h1):$e['hora_salida_saas'],
      'fecha_salida'=>($tipo==1)?min($data_f1):fecha_dmy($e['fecha_salida']),
      'hora_llegada_lugar'=>($tipo==1)?min($data_h2):$e['hora_llegada_lugar'],
      'fecha_llegada_lugar'=>($tipo==1)?min($data_f2):fecha_dmy($e['fecha_llegada_lugar']),
      'hora_salida_lugar'=>($tipo==1)?max($data_h3):$e['hora_salida_lugar'],
      'fecha_salida_lugar'=>($tipo==1)?max($data_f3):fecha_dmy($e['fecha_salida_lugar']),
      'hora_regreso_saas'=>($tipo==1)?max($data_h4):$e['hora_regreso_saas'],
      'fecha_regreso'=>($tipo==1)?max($data_f4):fecha_dmy($e['fecha_regreso']),
      'empleado'=>$e['pn1'].' '.$e['sn1'].' '.$e['pa1'].' '.$e['sa1'],
      'tipo'=>$tipo,
      'destinos'=>$array_destinos,
      'descripcion_lugar'=>$descripcionlugar
    );
    if($id_empleado == $e['id_empleado']){
      $data[]=$sub_array;
    }else{
      $data2[]=$sub_array;
    }

  }
  $output = array(
    "data"    => (!empty($id_empleado)) ? $data : $data2
  );
  echo json_encode($output);




else:
header("Location: ../index.php");
endif;

?>
