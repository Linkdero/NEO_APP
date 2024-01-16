<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';

  $id_persona=$_GET['id_persona'];
  $tipo = (!empty($_GET['tipo'])) ? $_GET['tipo'] : NULL;
  $e = array();
  $clase = new empleado;
  $e = $clase->get_empleado_by_id($id_persona);
  $ficha = $clase->get_empleado_by_id_ficha($id_persona);
  $d = $clase->get_direccion_armada($id_persona);
  $data = array();

  $accion = "";
  $status = '';
  $igss='--';
  $nisp='--';
  $observaciones='--';
  $tipo_contrato='--';
  $sueldo = '--';
  if($e['afiliacion_IGSS']!=''){
    $igss=$e['afiliacion_IGSS'];
  }
  if($e['NISP']!=''){
    $nisp=$e['NISP'];
  }
  if($e['emp_observaciones']!=''){
    $observaciones=$e['emp_observaciones'];
  }
  if($e['tipo_contrato']!=''){
    $tipo_contrato=$e['tipo_contrato'];
  }


  $data = array(
    'id_persona'=>$e['id_persona'],
    'nombres'=>ucwords($e['primer_nombre']).' '.ucwords($e['segundo_nombre']).' '.ucwords($e['tercer_nombre']),
    'apellidos'=>ucwords($e['primer_apellido']).' '.ucwords($e['segundo_apellido']).' '.ucwords($e['tercer_apellido']),
    'nombres_apellidos'=>ucwords($e['primer_nombre']).' '.ucwords($e['segundo_nombre']).' '.ucwords($e['tercer_nombre']).' '.ucwords($e['primer_apellido']).' '.ucwords($e['segundo_apellido']).' '.ucwords($e['tercer_apellido']),
    //'profesion'=>$e['profesion'],
    'primer_nombre'=>strtoupper($e['primer_nombre']),
    'segundo_nombre'=>strtoupper($e['segundo_nombre']),
    'tercer_nombre'=>strtoupper($e['tercer_nombre']),
    'primer_apellido'=>strtoupper($e['primer_apellido']),
    'segundo_apellido'=>strtoupper($e['segundo_apellido']),
    'tercer_apellido'=>strtoupper($e['tercer_apellido']),
    'nit'=>$e['nit'],
    'cui'=>str_replace(' ', '', $ficha['dpi']),
    'cui_ven'=>date('Y-m-d', strtotime($ficha['dpi_ven'])),
    'igss'=>$igss,
    'nisp'=>$nisp,
    'email'=>$e['correo_electronico'],
    'descripcion'=>$e['descripcion'],
    'estado_civil'=>$e['estado_civil'],
    'procedencia'=>$e['procedencia'],
    'tipo_personal'=>$e['tipo_personal'],
    'religion'=>$e['religion'],
    'fecha_nacimiento'=>fecha_dmy($e['fecha_nacimiento']),
    'fecha_denacimiento'=>date('Y-m-d', strtotime($e['fecha_nacimiento'])),
    'municipio'=>$e['municipio'],
    'departamento'=>$e['departamento'],
    'id_municipio'=>$e['id_muni_nacimiento'],
    'id_departamento'=>$e['id_depto_nacimiento'],
    'id_lugar'=>$e['id_aldea_nacimiento'],
    'id_procedencia'=>$e['id_procedencia'],
    'id_estado_civil'=>$e['id_estado_civil'],
    'id_profesion'=>$e['id_profesion'],
    'id_tipo_servicio'=>$e['id_tipo_servicio'],
    'id_genero'=>$e['id_genero'],
    'id_tipo_curso'=>$e['id_tipo_curso'],
    'id_promocion'=>$e['id_promocion'],
    'id_religion'=>$e['id_religion'],

    'tipo_contrato'=>$tipo_contrato,
    'observaciones'=>$observaciones,
    'status'=>$status,
    'status_empleado'=>$e['status_empleado'],
    'genero'=>$e['genero'],
    'accesos'=>0,
    'accion'=>$accion,
    'edad'=>$e['edad'],

    'licencia'=>'--',//$ficha['licencia'],
    'tsangre'=>$ficha['tsangre'],
    'telefono'=>$ficha['telper'],
    'direccion'=>$d['dir_armada'].', '.$d['lugar_armado'].', '.$d['muni_armado'],//(!empty($ficha['direccion']))?$ficha['direccion']:' -- ',

    'sueldo'=>$ficha['id_sueldo_plaza'],
    'p_nominal'=>$ficha['p_nominal'],
    'p_funcional'=>$ficha['p_funcional'],
    'd_nominal'=>$ficha['dir_nominal'],
    'd_funcional'=>$ficha['dir_funcional'],
    'id_tipo_sangre'=>$ficha['id_Sangre']
  );

  if(!empty($tipo)){
    $empleado = ucwords($e['primer_nombre']).' '.ucwords($e['segundo_nombre']).' '.ucwords($e['tercer_nombre']).' '.ucwords($e['primer_apellido']).' '.ucwords($e['segundo_apellido']).' '.ucwords($e['tercer_apellido']);
    createLog(84, 1163, 'rrhh_persona','Visualizando el Perfil del empleado: - codigo: '.$e['id_persona'].' - '.$empleado,'', '');
  }



    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
