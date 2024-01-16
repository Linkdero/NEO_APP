<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true):
    include_once '../../../../horarios/php/back/functions.php';
    date_default_timezone_set('America/Guatemala');
    set_time_limit(0);
    $fecha = $_POST["fecha"];
    $HORARIO = new Horario();
    $direccion = $HORARIO->get_direccion_empleado($_SESSION["id_persona"]);
    if (usuarioPrivilegiado()->hasPrivilege(299)) { //SI TIENE ACCESO AL REPORTE GENERAL
        $empleados = $HORARIO->get_horario_diario($fecha, null);
    } else { //ACCESO UNICAMENTE POR DIRECCION
        $empleados = $HORARIO->get_horario_diario($fecha, $direccion[0]["id_dirf"]);
    }

    $data = array();
    $cor = 0;
    foreach ($empleados as $empleado) {
        if ($empleado['entra_alm'] != null && $empleado['sale_alm'] != null) {
            $almuerzo = date('H:i:s', strtotime($empleado['entra_alm'])) . " - " . date('H:i:s', strtotime($empleado['sale_alm']));
        } else {
            $almuerzo = "00:00:00 - 00:00:00";
        }


        $gcontrol = $HORARIO->get_tarde_by_empleado($empleado['id_persona'], date('H:i:s', strtotime($empleado['entrada'])), $fecha);
        $tarde = ($gcontrol['tarde'] == 1) ? 'Tarde' : 'A tiempo';

        $hcontrol = $HORARIO->get_horarios_by_empleado_by_fecha($empleado['id_persona'], $fecha);

        // if(date('H:i:s', strtotime($empleado['entrada']))>=date('H:i:s', strtotime('07:00:00')) && date('H:i:s', strtotime($empleado['entrada']))<=date('H:i:s', strtotime('11:00:00'))){
        //     $tarde = 'Tarde';
        // }else{
        //     $tarde = 'A tiempo';
        // }

        $cuenta = ($empleado['cuenta'] % 2 == 0) ? 'Dos' : 'Solo uno';
        $cor += 1;

        $data[] = array(
            'correlativo' => $cor,
            'id' => $empleado['id_persona'],
            'foto' => "",
            'empleado' => $empleado['nombre'],
            'dfuncional' => $empleado['dfuncional'],
            'pfuncional' => $empleado['pfuncional'],
            'entrada' => (!empty($empleado['entrada'])) ? '<span class="text-info fa fa-mobile-alt"> ' . $tipo_control[$empleado['punto_entrada']] . '</span>' . date('H:i:s', strtotime($empleado['entrada'])) : '',
            'almuerzo' => $almuerzo,
            'salida' => (!empty($empleado['salida'])) ? '<span class="text-info fa fa-mobile-alt"> ' . $tipo_control[$empleado['punto_salida']] . '</span>' . date('H:i:s', strtotime($empleado['salida'])) : '',
            'tarde' => $tarde,
            'cuenta' => $cuenta,
            'horas' => $gcontrol['horas']
        );
    }

    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
    );

    echo json_encode($results);
else:
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;

/*<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../horarios/php/back/functions.php';
    date_default_timezone_set('America/Guatemala');
    $fecha = $_POST["fecha"];
    $HORARIO = new Horario();
    $direccion = $HORARIO->get_direccion_empleado($_SESSION["id_persona"]);
    if (usuarioPrivilegiado()->hasPrivilege(299)) { //SI TIENE ACCESO AL REPORTE GENERAL
        $empleados = $HORARIO->get_horario_diario($fecha, null);
    } else { //ACCESO UNICAMENTE POR DIRECCION
        $empleados = $HORARIO->get_horario_diario($fecha, $direccion[0]["id_dirf"]);
    }

    $data = array();
    $cor = 0;
    foreach ($empleados as $empleado) {
        if ($empleado['entra_alm'] != null && $empleado['sale_alm'] != null) {
            $almuerzo = date('H:i:s', strtotime($empleado['entra_alm'])) . " - " . date('H:i:s', strtotime($empleado['sale_alm']));
        } else {
            $almuerzo = "00:00:00 - 00:00:00";
        }


        $gcontrol = $HORARIO->get_tarde_by_empleado($empleado['id_persona'], date('H:i:s', strtotime($empleado['entrada'])), $fecha);
        $tarde = ($gcontrol['tarde'] == 1) ? 'Tarde' : 'A tiempo';

        $hcontrol = $HORARIO->get_horarios_by_empleado_by_fecha($empleado['id_persona'], $fecha);

        // if(date('H:i:s', strtotime($empleado['entrada']))>=date('H:i:s', strtotime('07:00:00')) && date('H:i:s', strtotime($empleado['entrada']))<=date('H:i:s', strtotime('11:00:00'))){
        //     $tarde = 'Tarde';
        // }else{
        //     $tarde = 'A tiempo';
        // }

        $cuenta = ($empleado['cuenta'] % 2 == 0) ? 'Dos' : 'Solo uno';
        $cor += 1;

        $data[] = array(
            'correlativo' => $cor,
            'id' => $empleado['id_persona'],
            'foto' => "",
            'empleado' => $empleado['nombre'],
            'dfuncional' => $empleado['dfuncional'],
            'pfuncional' => (!empty($empleado['p_nominal']))?$empleado['p_nominal'] : $empleado['pfuncional'],//$empleado['pfuncional'],
            'entrada' => date('H:i:s', strtotime($empleado['entrada'])), //(!empty($empleado['entrada'])) ? '<span class="text-info fa fa-mobile-alt"> '.$tipo_control[$empleado['punto_entrada']].'</span>'.date('H:i:s', strtotime($empleado['entrada'])) : '',
            'almuerzo' => $almuerzo,
            'salida' => date('H:i:s', strtotime($empleado['salida'])),//(!empty($empleado['salida'])) ? '<span class="text-info fa fa-mobile-alt"> '.$tipo_control[$empleado['punto_salida']].'</span>'.date('H:i:s', strtotime($empleado['salida'])) : '',
            'tarde' => $tarde,
            'cuenta' => $cuenta,
            'horas'=>$gcontrol['horas']
        );
    }

    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data
    );

    echo json_encode($results);
else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
*/