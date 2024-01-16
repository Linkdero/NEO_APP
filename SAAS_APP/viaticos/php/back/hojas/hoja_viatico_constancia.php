
<?php
include_once '../../../../inc/functions.php';
include_once '../functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $response = array();
  $id_nombramiento=$_POST['nombramiento'];

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql0 = "EXEC sp_sel_valida_exterior ?";
  $q0 = $pdo->prepare($sql0);
  $q0->execute(array($id_nombramiento));
  $pais = $q0->fetch();

  $sql='';
  if($pais['id_pais']=='GT')
  {
    $sql = "EXEC sp_sel_imprimible_viatico_constancia @correlativo=?";
  }else{
    $sql = "EXEC sp_sel_datos_para_liquidacion_exterior @correlativo=?";
  }



  $p = $pdo->prepare($sql);
  $p->execute(array($id_nombramiento));
  $empleados = $p->fetchAll();

  $data = array();
  if($pais['id_pais']=='GT'){
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
        'resolucion'=>$e['resolucion']
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
        'resolucion'=>$e['resolucion']
      );
      $data[]=$sub_array;
    }
  }


      $output = array(
        "data"    => $data
      );

echo json_encode($output);




else:
header("Location: ../index.php");
endif;

?>
