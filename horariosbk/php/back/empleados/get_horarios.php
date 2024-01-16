<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../horarios/php/back/functions.php';
    $id_persona = $_POST['id_persona'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $month1 = $_POST['month1'];
    $year1 = $_POST['year1'];
    $datei = strtotime($year . "-" . $month . "-01");
    $datef = strtotime($year1 . "-" . $month1 . "-" . cal_days_in_month(CAL_GREGORIAN, $month1, $year1));
    $sdatei = $year . "-" . $month . "-01";
    $sdatef = $year1 . "-" . $month1 . "-" . cal_days_in_month(CAL_GREGORIAN, $month1, $year1);
    $datediff = abs($datei - $datef);
    $td = round($datediff / (60 * 60 * 24)) + 1;
    $total_dias = $td;
    $HORARIO = new Horario();
    $horarios = $HORARIO->get_horario_empleado(2, $id_persona, $month, $year, $month1, $year1);
    $descansos = $HORARIO->get_total_descansos($year, 2, $month, $year1, $month1);
    $permisos = $HORARIO->get_permisos_empleado($id_persona, $year,  $month, $year1, $month1);
    $vacaciones = $HORARIO->get_vacaciones_empleado($id_persona, $month, $year, $month1, $year1);
    $comision = $HORARIO->get_comision_empleado($id_persona, $month, $year, $month1, $year1);
    $turnos = $HORARIO->get_turno_empleado($id_persona, $month, $year, $month1, $year1);
    $array_dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $array_meses = array();
    $data = array();
    $fecha_actual = date("d-m-Y", strtotime("01-" . $month . "-" . $year));
    $dia_actual = $array_dias[date('w', strtotime("01-" . $month . "-" . $year))];
    $array_meses[$fecha_actual] = array(
        "dia" => $dia_actual,
        'fecha' => $fecha_actual,
        'entrada' => "00:00:00",
        'almuerzo' => "00:00:00" . " - " . "00:00:00",
        'salida' => "00:00:00",
        'horas' => "0",
        'control' => "",
        'correlativo' => 1,
        'permisos' => '',
        'observaciones' => '',
        'horarios' => '',
        'dechoras' => "0"
    );
    for ($i = 1; $i < $total_dias; $i++) { //GENERA TODA EL MES 
        $strtime = strtotime($fecha_actual . "+ 1 days");
        $fecha_actual = date("d-m-Y", $strtime);
        $dia_actual = $array_dias[date('w', $strtime)];
        $array_meses[$fecha_actual] = array(
            "dia" => $dia_actual,
            'fecha' => $fecha_actual,
            'entrada' => "00:00:00",
            'almuerzo' => "00:00:00" . " - " . "00:00:00",
            'salida' => "00:00:00",
            'horas' => "0",
            'control' => "",
            'correlativo' => $i,
            'permisos' => '',
            'observaciones' => '',
            'horarios' => '',
            'dechoras' => "0"
        );
    }
    $control = array();
    foreach ($horarios as $key => $row) { //BUSCA LOS DIAS QUE TENGAN DATOS
        $dia = $array_dias[date('w', strtotime($row["fecha"]))];
        $fecha = date('d-m-Y', strtotime($row["fecha"]));
        $entrada = date('H:i:s', strtotime($row["entrada"]));
        $entra_alm = date('H:i:s', strtotime($row["entra_alm"]));
        $sale_alm = date('H:i:s', strtotime($row["sale_alm"]));
        $salida = date('H:i:s', strtotime($row["salida"]));
        $horas = number_format(((strtotime($row["salida"]) - strtotime($row["entrada"])) / 3600), 2, '.', '');
        $control = $array_meses[$fecha]["control"];
        $observaciones = $array_meses[$fecha]["observaciones"];
        $gcontrol = $HORARIO->get_tarde_by_empleado($id_persona, $entrada, $fecha);
        $tarde = ($gcontrol['tarde'] == 1) ? '<span class="badge badge-danger">Tarde</span>' : '<span class="badge badge-success">A tiempo</span>';
        $hif = $gcontrol['horas'];
        $array_meses[$fecha] = array(
            'dia' => $dia,
            'fecha' => $fecha,
            'entrada' => $entrada,
            'almuerzo' => $entra_alm . " - " . $sale_alm,
            'salida' => $salida,
            'horas' => floor($horas) . " horas " . (floor($horas * 60) % 60) . " minutos",
            'dechoras' => $horas,
            'control' => $tarde,
            'permisos' => $control,
            'observaciones' => $observaciones,
            'horarios' => $hif
        );
    }
    foreach ($descansos as $valueD) { //INGRESA LOS DESCANSOS OFICIALES
        if ($valueD['inicio'] != $valueD['fin']) {
            $fecha_actual = $valueD['inicio'];
            while (true) {
                $array_meses[$fecha_actual]["control"] = "<span title='{$valueD['motivo']}' class='badge badge-warning'>{$valueD['motivo']}</span>";
                $strtime = strtotime($fecha_actual . "+ 1 days");
                $fecha_actual = date("d-m-Y", $strtime);
                if ($strtime >  strtotime($valueD['fin'])) break;
            }
        } else {
            $array_meses[$valueD["inicio"]]["control"] = "<span title='{$valueD['motivo']}' class='badge badge-warning'>{$valueD['motivo']}</span>";
        }
    }
    foreach ($vacaciones as $valueV) { //INGRESA LAS VACACIONES
        if (strtotime($valueV['vac_fch_ini']) >= $datei) {
            $fecha_actual =  $valueV['vac_fch_ini'];
            while (true) {
                $strtime = strtotime($fecha_actual . "+ 1 day");
                $fecha_actual = date("d-m-Y", $strtime);
                $fecha_actual1 = date('d-m-Y', strtotime('-1 day', strtotime($fecha_actual)));
                $array_meses[$fecha_actual1]["control"] = "<span title='VACACIONES' class='badge badge-info'>VACACIONES</span>";
                if (strtotime($fecha_actual) >=  strtotime($valueV['vac_fch_pre']) || strtotime($fecha_actual) > $datef) break;
            }
        } else if (strtotime($valueV['vac_fch_ini']) < $datei && strtotime($valueV['vac_fch_fin']) <= $datef) {
            $fecha_actual =  $sdatei;
            while (true) {
                $strtime = strtotime($fecha_actual . "+ 1 day");
                $fecha_actual = date("d-m-Y", $strtime);
                $fecha_actual1 = date('d-m-Y', strtotime('-1 day', strtotime($fecha_actual)));
                $array_meses[$fecha_actual1]["control"] = "<span title='VACACIONES' class='badge badge-info'>VACACIONES</span>";
                if (strtotime($fecha_actual) >=  strtotime($valueV['vac_fch_pre']) || strtotime($fecha_actual) > $datef) break;
            }
        }
    }
    foreach ($comision as $valueC) { //INGRESA LAS COMISIONES
        if (strtotime($valueC['fecha_salida']) >= $datei) {
            $fecha_actual =  $valueC['fecha_salida'];
            while (true) {
                $strtime = strtotime($fecha_actual . "+ 1 day");
                $fecha_actual = date("d-m-Y", $strtime);
                $fecha_actual1 = date('d-m-Y', strtotime('-1 day', strtotime($fecha_actual)));
                $array_meses[$fecha_actual1]["control"] = "<span title='COMISION' class='badge badge-primary'>COMISION</span>";
                if (strtotime($fecha_actual) >=  strtotime($valueC['fecha_regreso']) || strtotime($fecha_actual) > $datef) break;
            }
        } else if (strtotime($valueC['fecha_salida']) < $datei && strtotime($valueC['fecha_regreso']) <= $datef) {
            $fecha_actual =  $sdatei;
            while (true) {
                $strtime = strtotime($fecha_actual . "+ 1 day");
                $fecha_actual = date("d-m-Y", $strtime);
                $fecha_actual1 = date('d-m-Y', strtotime('-1 day', strtotime($fecha_actual)));
                $array_meses[$fecha_actual1]["control"] = "<span title='COMISION' class='badge badge-primary'>COMISION</span>";
                if (strtotime($fecha_actual) >=  strtotime($valueC['fecha_regreso']) || strtotime($fecha_actual) > $datef) break;
            }
        }
    }
    foreach ($turnos as $valueT) { //INGRESA LOS TURNOS
        $bturno = ($valueT['id_catalogo'] == 63) ? "<span title='TURNO' class='badge badge-soft-secondary'>TURNO</span>" : "<span title='TURNO' class='badge badge-soft-secondary'>SALE DE TURNO</span>";
        if (strtotime($valueT['inicio']) >= $datei) {
            $fecha_actual =  $valueT['inicio'];
            while (true) {
                $strtime = strtotime($fecha_actual . "+ 1 day");
                $fecha_actual = date("d-m-Y", $strtime);
                $fecha_actual1 = date('d-m-Y', strtotime('-1 day', strtotime($fecha_actual)));
                $array_meses[$fecha_actual1]["control"] = $bturno;
                if (strtotime($fecha_actual) >  strtotime($valueT['fin']) || strtotime($fecha_actual) > $datef) break;
            }
        } else if (strtotime($valueT['inicio']) < $datei && strtotime($valueT['fin']) <= $datef) {
            $fecha_actual =  $sdatei;
            while (true) {
                $strtime = strtotime($fecha_actual . "+ 1 day");
                $fecha_actual = date("d-m-Y", $strtime);
                $fecha_actual1 = date('d-m-Y', strtotime('-1 day', strtotime($fecha_actual)));
                $array_meses[$fecha_actual1]["control"] = $bturno;
                if (strtotime($fecha_actual) >  strtotime($valueT['fin']) || strtotime($fecha_actual) > $datef) break;
            }
        }
    }
    foreach ($permisos as $valueP) { //INGRESA LOS PERMISOS
        if (strtotime($valueP['inicio']) >= $datei) {
            $fecha_actual =  $valueP['inicio'];
            while (true) {
                $strtime = strtotime($fecha_actual . "+ 1 day");
                $fecha_actual = date("d-m-Y", $strtime);
                $fecha_actual1 = date('d-m-Y', strtotime('-1 day', strtotime($fecha_actual)));
                $array_meses[$fecha_actual1]["observaciones"] =  "<b>" . $valueP['motivo'] . "<br>" . $valueP['observaciones'] . "</b> AUTORIZA: <b>" . $valueP['nombre'] . "</b>";
                if (strtotime($fecha_actual) >  strtotime($valueP['fin']) || strtotime($fecha_actual) >= $datef) break;
            }
        } else if (strtotime($valueP['inicio']) < $datei && strtotime($valueP['fin']) <= $datef) {
            $fecha_actual =  $sdatei;
            while (true) {
                $strtime = strtotime($fecha_actual . "+ 1 day");
                $fecha_actual = date("d-m-Y", $strtime);
                $fecha_actual1 = date('d-m-Y', strtotime('-1 day', strtotime($fecha_actual)));
                $array_meses[$fecha_actual1]["observaciones"] =  "<b>" . $valueP['motivo'] . "<br>" . $valueP['observaciones'] . "</b> AUTORIZA: <b>" . $valueP['nombre'] . "</b>";
                if (strtotime($fecha_actual) >  strtotime($valueP['fin']) || strtotime($fecha_actual) > $datef) break;
            }
        }
    }
    foreach ($array_meses as $fecha => $datos) $data[] = $datos;
    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data,
        "total" => $array_meses,
        "horarios" => $horarios
    );
    echo json_encode($results);
else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
