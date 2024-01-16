<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';
  include_once '../functions_plaza.php';
  include_once '../functions_contratos.php';
  date_default_timezone_set('America/Guatemala');

  $id_persona = $_POST['id_persona'];
  $e = array();
  $clase = new empleado;
  $f = $clase->get_empleado_fotografia($id_persona);
  $c = array();

  $e = $clase->get_empleado_by_id($id_persona);
  $ficha = $clase->get_empleado_by_id_ficha($id_persona);

  $d = $clase->get_direccion_armada($id_persona);

  $data1 = array();
  $data2 = array();

  $encoded_image = base64_encode($f['fotografia']);
  $partidas = array();
  $meses = 0;
  $sueldo = 0;

  //$clase = new empleado;
  $filtro = '';

  $vacunas = $clase->get_vacunas_by_persona($id_persona);
  $estudios = $clase->get_nivel_academico($id_persona);
  $familia = $clase->get_familia_by_empleado($id_persona);
  $cursos = $clase->get_cursos_by_empleado($id_persona,$filtro);
  $vacaciones = $clase->get_vacaciones_pendientes($id_persona);
  $licenciaarma = $clase->getLicenciaTipo($id_persona,931);
  $licenciaconducir = $clase->getLicenciaTipo($id_persona,1152 );
  $armas = $clase->getArmasByPersona($id_persona);
  $cont = '';

  $array_vacunas = array();
  foreach($vacunas as $v){

    $id_vacuna_dosis = '';
    if($v['id_vacuna_dosis'] == 1){
      $id_vacuna_dosis = 'Primera Dosis';
    }else if($v['id_vacuna_dosis'] == 2){
      $id_vacuna_dosis = 'Segunda Dosis';
    }else if($v['id_vacuna_dosis'] == 3){
      $id_vacuna_dosis = 'Refuerzo';
    }
    $sub_array = array(
      'id_dosis'=>$id_vacuna_dosis,//($v['id_vacuna_dosis'] == 1) ? 'Primera Dosis' : 'Segunda Dosis',
      'tipo_vacuna'=>$v['tipo_vacuna'],
      'fecha_vacuna'=>fecha_dmy($v['fecha_vacunacion']),
      //'arreglo'=>$arreglo
    );

    $array_vacunas[]=$sub_array;
  }

  $dlaborales = array();
  $tipo = $clase->get_tipo_contrato_by_empleado($id_persona);
  $tpersona =$clase->get_tipo_persona($id_persona);
  $renglon = '';
  if($tipo['id_contrato']==7 || $tpersona['id_status']==2312 || $tpersona['id_status']==5611){
    if(empty($tipo['id_empleado'])){
      $dlaborales = $clase->get_apoyo_actual_by_persona($id_persona);
    }else{
      $dlaborales = $clase->get_empleado_puesto_actual($id_persona);

      $renglon = '011';

    }
  }else{
    $cont = $clase->get_contrato_actual_by_persona($id_persona);
      if($cont['tipo_contrato'] == 9){
        $date1=$cont['fecha_inicio'];
        $date2=$cont['fecha_finalizacion'];
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $meses = (($year2 - $year1) * 12) + ($month2 - $month1) + 1;
        $sueldo = $cont['monto_contrato'] / $meses;
      }
      if($cont['tipo_contrato'] == 8){
        $sueldo = $cont['monto_contrato'] * 30;
      }

      $puestofunc = (!empty($cont['puestofunc'])) ? $cont['puestofunc'] : $cont['puestof'];
      $data2 = array(
        'meses'=>$meses,
        'id_secretaria_funcional'=>(!empty($cont['id_secretaria_funcional']))?$cont['id_secretaria_funcional']:'Sin asignación',
    	  'id_subsecretaria_funcional'=>(!empty($cont['id_subsecretaria_funcional']))?$cont['id_subsecretaria_funcional']:'-- -- -- --',
    	  'id_direccion_funcional'=>(!empty($cont['id_direccion_funcional']))?$cont['id_direccion_funcional']:'-- -- -- --',
    		'secretariaf'=>(!empty($cont['secretariaf']))?$cont['secretariaf']:'-- -- -- --',
    		'subsecretariaf'=>(!empty($cont['subsecretariaf']))?$cont['subsecretariaf']:'-- -- -- --',
    		'direccionfun'=>(!empty($cont['direccionf'])) ? $cont['direccionf'] : '',//(!empty($c['direccionf']))?$c['direccionf']:'adfadsf',
    		'subdireccionf'=>(!empty($cont['subdireccionf']))?$cont['subdireccionf']:'-- -- -- --',
    		'departamentof'=>(!empty($cont['departamentof']))?$cont['departamentof']:'-- -- -- --',
    		'seccionf'=>(!empty($cont['seccionf']))?$cont['seccionf']:'-- -- -- --',
    		'puestof'=>(!empty($cont['puestof']))?$cont['puestof']:'-- -- -- --',
        //'estado_empleado'=>'1',
        'tipo_contrato'=>$tipo['id_contrato'],
        'fecha_contrato'=>fecha_dmy($cont['fecha_contrato']),
        'fecha_inicio'=>fecha_dmy($cont['fecha_inicio']),
        'fecha_fin'=>fecha_dmy($cont['fecha_finalizacion']),
        'acuerdo'=>$cont['nro_acuerdo_aprobacion'],
        'fecha_acuerdo_aprobacion'=>fecha_dmy($cont['fecha_acuerdo_aprobacion']),
        //'nro_acuerdo_resicion'=>$c['nro_acuerdo_resicion'],
        'nro_contrato'=>$cont['nro_contrato'],
        'fecha_acuerdo_resicion'=>fecha_dmy($cont['fecha_acuerdo_resicion']),
        'fecha_efectiva_resicion'=>fecha_dmy($cont['fecha_efectiva_resicion']),
        'monto_contrato'=>number_format($cont['monto_contrato'],2,'.',','),
        'monto_mensual'=>number_format($cont['monto_mensual'],2,'.',','),
        'id_puesto_servicio'=>$cont['id_puesto_servicio'],
        'cargo'=>($cont['tipo_contrato'] == 8) ? 'TALLERISTA' : 'ASESOR',
        'renglon'=>($cont['tipo_contrato'] == 8) ? '031' : '029'
      );
  }

  //contratos

  $contratos=contrato::get_contratos_por_empleado($id_persona,2);
  foreach($contratos as $c){
    if(!empty($c['nro_contrato'])){
      $sub_array = array(
        'renglon'=>$c['Renglon'],
        'id_plaza'=>'',
        'id_asignacion'=>$c['reng_num'],
        'cod_plaza'=>'',
        'partida'=>'',
        'plaza'=>'',
        'puesto'=>($c['tipo_contrato'] == 8) ? 'TALLERISTA' : 'ASESOR',
        'inicio'=>fecha_dmy($c['fecha_inicio']),
        'final'=>(!empty($c['fecha_efectiva_resicion'])) ? fecha_dmy($c['fecha_efectiva_resicion']) : 'Actualidad',
        'sueldo'=>$c['monto_mensual'],
        'status'=>$c['id_status'],
        'estado'=>$c['estado'],
        //'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido']
      );
      $partidas[]=$sub_array;
    }
  }
  //partidas
  $y = 0;
  $plazas = plaza::get_plazas_por_empleado($id_persona,2);
  $total = count($plazas);
  $totally = $total - 1;
  foreach($plazas as $key => $p){

    /*$estado = '';
    $estado2 = '';

    if ($total > 0) {
      $total = $total -1;
    } else if($total == 0) {
      $total = 0;
    }

    if($y < $totally-1){
      $y = $key + 1;
    }else if($y == $totally - 1){
      $y = 0;
    }*/

    $estado = '';
    $estado2 = '';

    if ($total > 0) {
      $total = $total -1;
    } else if($total == 0) {
      $total = 0;
    }

    if($y < $totally){
      $y = $key - 1;
    }else if($y == $totally){
      $y = 0;
    }


    if($y < 0){
      $y = 0;
    }

    /*if($plazas[$key]['validacion']=='asc' && $total == 0){
      //$plazas[$y]['estado'] != 'PRIMER INGRESO';
      //$estado = 'PRIMER INGRESO';
    }else if($plazas[$key]['validacion']!='asc' && $total == 0){
      //$plazas[$y]['estado'] != 'PRIMER INGRESO';
      //$estado = 'PRIMER INGRESO';
    }*/
    //echo $y;
    if($plazas[$key]['validacion']=='asc' && $key == 0){
      //$plazas[$y]['estado'] != 'PRIMER INGRESO';
      $estado = 'PRIMER INGRESO';
    }else if($plazas[$key]['validacion']!='asc' && $totally == 0){
      //$plazas[$y]['estado'] != 'PRIMER INGRESO';
      $estado = 'PRIMER INGRESO';
    }
    else if($plazas[$key]['validacion']=='fin' && $key == 0){
      //$plazas[$y]['estado'] != 'PRIMER INGRESO';
      $estado = 'PRIMER INGRESO';
    }
    else if($plazas[$y]['validacion'] == 'asc'){
      $estado = 'ASCENSO';
    }else if($plazas[$y]['validacion'] == 'fin'){
      $estado = 'REINGRESO';
    }else if($plazas[$y]['validacion'] == 'fin' && $total == 0){
      $estado = 'REINGRESO';
    }
    else{
      $estado = $p['estado'];
    }



    if($plazas[$key]['validacion']=='asc' && $key == 0){
      //$plazas[$y]['estado'] != 'PRIMER INGRESO';
      $estado2 = '- ASCENSO';
    }else if($plazas[$key]['validacion'] == 'asc'){
      $estado2 = '';// $y.' || '.$total.'ASCENSO';
    }else if($plazas[$key]['validacion'] == 'act'){
      $estado2 = '- ACTIVO';
    }else if($plazas[$key]['validacion'] == 'fin'){
      $estado2 = '- FINALIZADO';
    }else if($plazas[$key]['validacion'] == 'fin' && $y == $totally){
      $estado2 = '- REINGRESO';
    }
    else{
      //$estado2 = $y.' || '.$total.$p['estado'];
    }


    //$estado = $y.' || '.$total.$p['estado'];
    $sub_array = array(
      //'plaza'=>$e['partida_presupuestaria'],
      //'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
      'renglon'=>'011',
      'id_plaza'=>$p['id_plaza'],
      'id_asignacion'=>$p['id_asignacion'],
      'cod_plaza'=>$p['cod_plaza'],
      'partida'=>$p['partida_presupuestaria'],
      'plaza'=>$p['id_plaza'],
      'puesto'=>$p['puesto'],
      'inicio'=>fecha_dmy($p['fecha_toma_posesion']),
      'final'=>(!empty($p['fecha_efectiva_resicion'])) ? fecha_dmy($p['fecha_efectiva_resicion']) : 'Actualidad',
      'sueldo'=>$p['SUELDO'],
      'sueldo_base'=>$p['SUELDOB'],
      'status'=>$p['id_status'],
      'estado'=>$estado.' '.$estado2,//$p['estado'],//($e['id_status']==891)?'<span class="text-info">'.$e['estado'].'</span>':'<span class="text-danger">'.$e['estado'].'</span>',

    );
    $sueldo = $p['SUELDOB'];
    $partidas[]=$sub_array;
  }

  //fin partidas
  $tn = (!empty($e['tercer_nombre']))? ' '.strtoupper($e['tercer_nombre']).' ': ' ';
  $ta = (!empty($e['tercer_apellido'])) ? ' DE '.strtoupper($e['tercer_apellido']) : '';
  $direccionf = (!empty($dlaborales['direccionfficha'])) ? $dlaborales['direccionfficha'] : $cont['direccionf'];

  $original = str_replace("\n","",rtrim(($d['dir_armada'].' '.$d['lugar_armado'].' '.$d['muni_armado']),' '));
  $letras = strlen($original);
  $division = $letras/55;
  $lineas = round(($letras/55) * 3);
  $lines = ($division < 1) ? 0 : $lineas;
  $arma = (count($armas) > 0) ? 'SI' : 'NO';
  $armaregistro = (count($armas) > 0) ? ', No. Registro: '.$armas[0]['nro_registro'] : '';

  $dependenciaa = (!empty($dlaborales['direccionnficha'])) ? $dlaborales['direccionnficha'] : $cont['direccionf'];
  $data1 = array(
    'fotografia'=>$encoded_image,
    //'foto'=>'data:image/jpeg;base64,'.$encoded_image,
    'id_persona'=>str_pad($e['id_persona'],5,"0", STR_PAD_LEFT),
    'nombres'=>strtoupper(sanear_string($e['primer_nombre'])).' '.strtoupper(sanear_string($e['segundo_nombre'])).''.$tn,//strtoupper($e['primer_nombre']).' '.strtoupper($e['segundo_nombre']).' '.strtoupper($e['tercer_nombre']),//
    'apellidos'=>strtoupper(sanear_string($e['primer_apellido'])).' '.strtoupper(sanear_string($e['segundo_apellido'])).''.$ta,//strtoupper($e['primer_apellido']).' '.strtoupper($e['segundo_apellido']).' '.strtoupper($e['tercer_apellido']),//
    'nombres_apellidos'=>strtoupper($e['primer_nombre']).' '.strtoupper($e['segundo_nombre']).''.strtoupper($tn).''.strtoupper($e['primer_apellido']).' '.strtoupper($e['segundo_apellido']).''.strtoupper($ta),
    'profesion'=>(!empty($ficha['profesion'])) ? $ficha['profesion'] : '',
    'primer_nombre'=>strtoupper($e['primer_nombre']),
    'segundo_nombre'=>strtoupper($e['segundo_nombre']),
    'tercer_nombre'=>strtoupper($e['tercer_nombre']),
    'primer_apellido'=>strtoupper($e['primer_apellido']),
    'segundo_apellido'=>strtoupper($e['segundo_apellido']),
    'tercer_apellido'=>strtoupper($e['tercer_apellido']),
    'nit'=>$e['nit'],
    'cui'=>$e['cui'],
    'igss'=>(!empty($e['afiliacion_IGSS']))?$e['afiliacion_IGSS']:'',
    //'nisp'=>$nisp,
    'edad'=>$e['edad'],
    'email'=>$e['correo_electronico'],
    'descripcion'=>$e['descripcion'],
    'estado_civil'=>$e['estado_civil'],
    'procedencia'=>$e['procedencia'],
    'tipo_personal'=>$e['tipo_personal'],
    'religion'=>$e['religion'],
    'fecha_nacimiento'=>fecha_dmy($e['fecha_nacimiento']),
    'fecha_denacimiento'=>date('Y-m-d', strtotime($e['fecha_nacimiento'])),
    'municipio'=>ucwords($e['municipio']),
    'departamento'=>ucwords($e['departamento']),
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

    //'tipo_contrato'=>$tipo_contrato,
    //'observaciones'=>$observaciones,
    //'status'=>$status,
    'status_empleado'=>$e['status_empleado'],
    'genero'=>$e['genero'],
    'accesos'=>0,
    //'accion'=>$accion,
    'edad'=>$e['edad'],

    'licencia'=>'--',//$ficha['licencia'],
    'tsangre'=>$ficha['tsangre'],
    'telefono'=>(!empty($ficha['telper'])) ? $ficha['telper'] : '',
    'direccion'=>$d['dir_armada'].', '.$d['lugar_armado'].', '.$d['muni_armado'],//$original,

    'sueldo'=>$ficha['id_sueldo_plaza'],
    'p_nominal'=>$ficha['p_nominal'],
    'p_funcional'=>$ficha['p_funcional'],
    'd_nominal'=>$ficha['dir_nominal'],
    'd_funcional'=>$ficha['dir_funcional'],
    'renglon'=>$renglon,
    'e_acuerdo'=>(!empty($dlaborales['nro_acuerdo'])) ? $dlaborales['nro_acuerdo'] : '',
    'e_acuerdo_fecha'=>(!empty($dlaborales['fecha_acuerdo'])) ? fecha_dmy($dlaborales['fecha_acuerdo']) : '',
    'e_acuerdo_fecha_efe'=>(!empty($dlaborales['fecha_toma_posesion'])) ? fecha_dmy($dlaborales['fecha_toma_posesion']) : '',
    'e_partida'=>(!empty($dlaborales['partida_presupuestaria'])) ? substr($dlaborales['partida_presupuestaria'],23) : '',
    'dependencia'=>(!empty($dependenciaa)) ? $dependenciaa : '',
    'cargo'=>(!empty($dlaborales['pueston'])) ? $dlaborales['pueston'] : '',
    'dep'=>(!empty($direccionf)) ? $direccionf : '',//(!empty($dlaborales['departamentof'])) ? $dlaborales['departamentof'] : $direccionf,
    'puesto'=>(!empty($dlaborales['puestof'])) ? $dlaborales['puestof'] : $puestofunc,
    'dep_padre'=>(!empty($dlaborales['subsecretariaf'])) ? $dlaborales['subsecretariaf'] : '',
    'f_inicio'=>(!empty($dlaborales['fecha_toma_posesion'])) ? fecha_dmy($dlaborales['fecha_toma_posesion']) : '',
    'f_destitucion'=>(!empty($dlaborales['fecha_efectiva_resicion'])) ? fecha_dmy($dlaborales['fecha_efectiva_resicion']) : '',
    'sueldo'=>number_format($sueldo,2,'.',','),
    //'arma'=>$//(!empty($partidas[0]['sueldo_base'])) ? number_format($partidas[0]['sueldo_base'],2,'.',',') : number_format($sueldo,2,'.',','),
    'plazas'=>$partidas,
    'lineas'=>$lines,
    'vacunas'=>$array_vacunas,
    'estudios'=>$estudios,
    'familia'=>$familia,
    'cursos'=>$cursos,
    'vacaciones'=>$vacaciones,
    'licencia_arma'=>(!empty($licenciaarma['fecha_vencimiento'])) ? $licenciaarma['nro_registro'].' - Vence: '.fecha_dmy($licenciaarma['fecha_vencimiento']) : '',
    'licenciaconducir'=>(!empty($licenciaconducir['fecha_vencimiento'])) ? $licenciaconducir['nro_registro'].' - Vence: '.fecha_dmy($licenciaconducir['fecha_vencimiento']) : '',
    'armas'=>$arma.''.$armaregistro
    //arma
  );

  $valor_anterior = array(
    'id_persona'=>$_SESSION['id_persona'],
    //'estado'=>1051
  );

  $valor_nuevo = array(
    'id_persona'=>$_SESSION['id_persona'],
    'descripcion'=>'generó ficha de la persona',
    'empleado'=>$e['id_persona']
  );
  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $log = "VALUES(82, 1163, 'rrhh_persona', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
  $sql2="INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
  $q2 = $pdo->prepare($sql2);
  $q2->execute(array());
  Database::disconnect_sqlsrv();
  $data = array_merge($data1,$data2);
  echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
