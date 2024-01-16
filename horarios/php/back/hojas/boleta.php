<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  //if (usuarioPrivilegiado_acceso()->accesoModulo(7847)) {
    date_default_timezone_set('America/Guatemala');
    include_once '../../back/functions.php';
    $vac_id = $_POST['vac_id'];
    $BOLETA = new Boleta();
    $s = $BOLETA->get_boleta_by_id($vac_id);
    /*$asignaciones = $BOLETA->get_asignacion_by_empleado($s['id_persona'],1);
    $contratos = $BOLETA->get_contratos_by_empleado($s['id_persona'],1);*/

    $puestoComisionado = getPuestoEmpleado($s['id_persona'],$s['vac_fch_ini']);
    $contratoComisionado = getContratoEmpleado($s['id_persona'],$s['vac_fch_ini']);
    //$apoyoEmpleado = $clasei->getApoyoEmpleado($s['id_persona']);




    $data = [];
    $superior = "";
    //echo $id_dir;



    $x = 1;
    $y = 0;
    $arreglo = array();
    $arreglo2 = array();
    //$total = count($asignaciones);
    //$totally = $total;
    $direccion = "";
    $f_ini = '';
    $f_fin = '';
    $dir_nom = '';
    $puesto_n = '';
    $id_direccion1 = "";

    //puestos nominales
    /*if($total > 0){
      foreach($asignaciones AS $key => $a){
        $y += 1;
        if($x == $total){
          $f_fin = date('Y-m-d');
          $f_ini = $asignaciones[$key]['fecha_inicio'];
        }else{
          $f_fin = $asignaciones[$y]['fecha_inicio'];
          $f_ini = $asignaciones[$key]['fecha_inicio'];
        }

        if($s['vac_fch_ini']>=$f_ini && $s['vac_fch_ini']<= $f_fin){
          $id_direccion1 = $a['direccion_f'];
          $dir_nom = $a['direccion'];
          $puesto_n = $a['pueston'];
        }
        //$monto_global=$viaticos[$key]['valor_mayor'];

        $sub_array = array(
          'id_asignacion'=>$a['id_asignacion'],
          'fecha_ini'=>$a['fecha_inicio'],
          'f_ini'=>$f_ini,
          'f_fin'=>$f_fin,
          'f_sol'=>$s['vac_fch_ini'],
          'id_dirf'=>$a['direccion_f'],
          'direccion'=>$id_direccion1
        );
        $arreglo[] = $sub_array;

        $x++;
      }
    }

    $x = 1;
    $y = 0;
    $total2 = count($contratos);

    $dir_nom2 = '';
    $puesto_n2 = '';
    $id_direccion2 = '';
    if($total2 > 0){
      //puestos de contrato
      foreach($contratos AS $key => $c){
        $y += 1;
        if($x == $total2){
          $f_fin = date('Y-m-d');
          $f_ini = date('Y-m-d', strtotime($contratos[$key]['fecha_inicio']));
        }else{
          $f_ini = date('Y-m-d', strtotime($contratos[$y]['fecha_inicio']));
          $f_fin = date('Y-m-d', strtotime($contratos[$key]['fecha_inicio']));
        }
        if($s['vac_fch_ini']>=$f_ini && $s['vac_fch_ini']<= $f_fin){
          $id_direccion2 = $c['id_direccion_servicio'];
          $dir_nom2 = $c['direccion'];
          $puesto_n2 = $c['pueston'];
        }
        //$monto_global=$viaticos[$key]['valor_mayor'];
        $sub_array = array(
          'fecha_ini'=>$c['fecha_inicio'],
          'f_ini'=>$f_ini,
          'f_fin'=>$f_fin,
          'f_sol'=>$s['vac_fch_ini'],
          'id_dirf'=>$id_direccion2,
          'direccion'=>$dir_nom2,
          'puesto'=>$puesto_n2
        );
        $arreglo2[] = $sub_array;
        $x++;
      }
    }*/

    $dirnom = (!empty($puestoComisionado['direccion'])) ? $puestoComisionado['direccion'] : $contratoComisionado['direccion'];
    $pueston = ($puestoComisionado['fecha_inicio'] > $contratoComisionado['fecha_inicio']) ? $puestoComisionado['pueston'] : $contratoComisionado['pueston'];
    /*$pueston = (!empty($puesto_n)) ? $puesto_n : $puesto_n2;
    $dirnom = (!empty($dir_nom)) ? $dir_nom : $dir_nom2;*/

    //$id_dir = (!empty($id_direccion1)) ? $id_direccion1 : '';//$id_direccion2;
    $id_dir = (!empty($puestoComisionado['id_direccion'])) ? $puestoComisionado['id_direccion'] : $contratoComisionado['id_direccion'];//$id_direccion2;
    $id_superior = '';
    if($s['id_nivel'] == 3 || $id_dir == 14 || $id_dir == 15 ||(($id_dir == 7 || $id_dir == 1 || $id_dir == 653
    || $id_dir == 657 || $id_dir == 669 || $id_dir == 655) && $s['id_nivel'] == 4)){
      $id_superior = 4;
    }else if((($id_dir == 5 || $id_dir == 6 || $id_dir == 8 || $id_dir == 9 || $id_dir == 10
    || $id_dir == 11 || $id_dir == 12 || $id_dir == 207) && $s['id_nivel'] == 4)){
      $id_superior = $s['id_superior'];
    }

    $renglon = $BOLETA->validarRenglonEmpleado($s['id_persona'],date('Y-m-d', strtotime($s['vac_fch_ini'])));

    $per_ini = '';
    //$BOLETA->full_fecha(strtotime($s['periodo_inicio']))
    if($renglon['renglon'] == '031'){
      $per_ini = $BOLETA->full_fecha(strtotime($renglon['f_ini']));
    }else if($renglon['renglon'] == '011'){
      $per_ini = $BOLETA->full_fecha(strtotime($s['periodo_inicio']));
      /*if($s['vac_dia'] == 20){
        $per_ini = $BOLETA->full_fecha(strtotime($s['periodo_inicio']));
      }else{
        if(date('Y',strtotime($renglon['f_ini'])) == date('Y',strtotime($s['periodo_fin'])))
        $per_ini = $BOLETA->full_fecha(strtotime($renglon['f_ini']));
      }*/
    }

    $data = array(
      'boleta' => $s['vac_id'],
      'per_ini'=>$per_ini,
      'per_fin'=>$BOLETA->full_fecha(strtotime($s['periodo_fin'])),
      'fsol' => $BOLETA->full_fecha(strtotime($s['vac_fch_sol'])),
      'fini' => $BOLETA->full_fecha(strtotime($s['vac_fch_ini'])),
      'ffin' => $BOLETA->full_fecha(strtotime($s['vac_fch_fin'])),
      'fpre' => $BOLETA->full_fecha(strtotime($s['vac_fch_pre'])),
      'vdia' => $BOLETA->dias_horas($s['vac_dia'], 0),
      'vgoz' => $BOLETA->dias_horas($s['vac_dia_goz'], 0),
      'vsub' => $BOLETA->dias_horas($s['vac_sub'], 0),
      'vds' => $BOLETA->dias_horas($s['vac_sub'], 2),
      'vhs' => $BOLETA->dias_horas($s['vac_sub'], 3),
      'vsol' => $BOLETA->dias_horas($s['vac_sol'], 0),
      'vpen' => $BOLETA->dias_horas($s['vac_pen'], 0),
      'est_id' => $s['est_id'],
      'vobs' => $s['vac_obs'],
      'vestado' => $s['est_des'],
      'nombre' => $s['nombre'] ,
      'puesto' => $pueston,//strval($s['p_funcional']),
      'dir_funcional' => $dirnom,
      'id_secre' => $s['id_secre'],
      'id_subsecre' => $s['id_subsecre'],
      'id_superior' => $id_superior,
      'asignaciones'=>$arreglo,
      'contratos'=>$arreglo2

    );

    echo json_encode($data);
//  }
}
