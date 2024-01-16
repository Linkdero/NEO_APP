
<?php
include_once '../../../../inc/functions.php';
include_once '../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();
  $id_nombramiento=$_POST['id_nombramiento'];
  $dia=$_POST['dia'];
  $mes=$_POST['mes'];
  $year=$_POST['year'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql0 = "EXEC sp_sel_valida_exterior ?";
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array($id_nombramiento));
  $pais = $q0->fetch();

  $sql='';
  /*if($pais['id_pais']=='GT')
  {
    $sql = "EXEC sp_sel_informe_fecha_anterior @correlativo=?, @dia=?,@mes=?,@anio=?";
  }else{
    $sql = "EXEC sp_sel_datos_para_liquidacion_exterior @correlativo=?";
  }*/

  $sql = "EXEC sp_sel_informe_fecha_anterior @correlativo=?, @dia=?,@mes=?,@anio=?";



  $p = $pdo->prepare($sql);
  $p->execute(array($id_nombramiento,$dia,$mes,$year));
  $empleados = $p->fetchAll();

  $data = array();
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
  /*$f1= max($data_f1);
  echo json_encode($data_f1);
  echo '<br><br>'.min($data_f1).'<br><br>';*/
  foreach($empleados as $e){

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
      'lugar'=>$e['lugar'],
      'dia_salida'=>$e['dia_salida'],
      'mes_salida'=>$e['mes_salida'],
      'year_salida'=>$e['anio'],
      'dia_regreso'=>$e['dia_regreso'],
      'mes_regreso'=>$e['mes_regreso'],
      'year_regreso'=>$e['anio'],
      'motivo'=>$e['motivo'],
      'hora_salida_saas'=>min($data_h1),//$e['hora_salida_saas'],
      'fecha_salida'=>min($data_f1),//fecha_dmy($e['fecha_salida']),
      'hora_llegada_lugar'=>min($data_h2),//$e['hora_llegada_lugar'],
      'fecha_llegada_lugar'=>min($data_f2),//fecha_dmy($e['fecha_llegada_lugar']),
      'hora_salida_lugar'=>max($data_h3),//$e['hora_salida_lugar'],
      'fecha_salida_lugar'=>max($data_f3),//fecha_dmy($e['fecha_salida_lugar']),
      'hora_regreso_saas'=>max($data_h4),//$e['hora_regreso_saas'],
      'fecha_regreso'=>max($data_f4),//fecha_dmy($e['fecha_regreso']),
      'empleado'=>$e['pn1'].' '.$e['sn1'].' '.$e['pa1'].' '.$e['sa1']
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
