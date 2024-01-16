<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../horarios/php/back/functions.php';
    set_time_limit(100000);
    ini_set("memory_limit", "300M");
    $month = $_POST['month'];
    $year = $_POST['year'];
    $month1 = $_POST['month1'];
    $year1 = $_POST['year1'];
    $dir = $_POST['dir'];
    $HORARIO = new Horario();

    $datei = strtotime($year . "-" . $month . "-01");
    $datef = strtotime($year1 . "-" . $month1 . "-" . cal_days_in_month(CAL_GREGORIAN, $month1, $year1));
    $datediff = abs($datei - $datef);
    $td = round($datediff / (60 * 60 * 24)) + 1;

    $total_dias = $td;


    $rdata = [];
    $rmeses = [];
    $rhorarios = [];


    $depipol = $HORARIO::get_horario_general($month, $year, $dir, $month1, $year1);

    $id_persona = 0;
    foreach ($depipol as $id) {

        $id_persona = $id['id_persona'];

        $horarios = $HORARIO::get_horario_empleado(2, $id_persona, $month, $year, $month1, $year1);
        $descansos = $HORARIO::get_total_descansos($year, 2, $month, $year1, $month1);
        $permisos = $HORARIO::get_permisos_empleado($id_persona, $year,  $month, $year1, $month1);

        $array_dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
        $array_meses = array();
        $data = array();
        $fecha_actual = date("d-m-Y", strtotime("01-" . $month . "-" . $year));
        $dia_actual = $array_dias[date('w', strtotime("01-" . $month . "-" . $year))];
        $limitei = date('H:i:s', strtotime('07:00:00'));
        $limites = date('H:i:s', strtotime('11:00:00'));
        $array_meses[$fecha_actual] = array(
            'id' => $id['nombre'],
            "dia" => $dia_actual,
            'fecha' => $fecha_actual,
            'entrada' => "00:00:00",
            'almuerzo' => "00:00:00" . " - " . "00:00:00",
            'salida' => "00:00:00",
            'horas' => "0",
            'control' => "",
        );
        for ($i = 1; $i < $total_dias; $i++) { //GENERA TODA EL MES 
            $strtime = strtotime($fecha_actual . "+ 1 days");
            $fecha_actual = date("d-m-Y", $strtime);
            $dia_actual = $array_dias[date('w', $strtime)];
            $array_meses[$fecha_actual] = array(
                'id' => $id['nombre'],
                "dia" => $dia_actual,
                'fecha' => $fecha_actual,
                'entrada' => "00:00:00",
                'almuerzo' => "00:00:00" . " - " . "00:00:00",
                'salida' => "00:00:00",
                'horas' => "0",
                'control' => "",

            );
        }
        foreach ($descansos as $valueD) { //INGRESA LOS DESCANSOS OFICIALES
            if ($valueD['inicio'] != $valueD['fin']) {
                $fecha_actual = $valueD['inicio'];
                while (true) {
                    $array_meses[$fecha_actual]["control"] = "<span class='badge badge-warning'>{$valueD['motivo']}</span>";
                    $strtime = strtotime($fecha_actual . "+ 1 days");
                    $fecha_actual = date("d-m-Y", $strtime);
                    if ($strtime >  strtotime($valueD['fin'])) break;
                }
            } else {
                $array_meses[$valueD["inicio"]]["control"] = "<span class='badge badge-warning'>{$valueD['motivo']}</span>";
            }
        }

        foreach ($permisos as $valueP) { //INGRESA LOS PERMISOS POR PERSONA
            if ($valueP['inicio'] != $valueP['fin']) {
                $fecha_actual = $valueP['inicio'];
                while (true) {
                    $array_meses[$fecha_actual]["control"] = "<span class='badge badge-warning'>{$valueP['motivo']}</span>";
                    $strtime = strtotime($fecha_actual . "+ 1 days");
                    $fecha_actual = date("d-m-Y", $strtime);
                    if ($strtime >  strtotime($valueP['fin'])) break;
                }
            } else {
                $array_meses[$valueP["inicio"]]["control"] = "<span class='badge badge-warning'>{$valueP['motivo']}</span>";
            }
        }

        foreach ($horarios as $key => $row) { //BUSCA LOS DIAS QUE TENGAN DATOS

            $dia = $array_dias[date('w', strtotime($row["fecha"]))];
            $fecha = date('d-m-Y', strtotime($row["fecha"]));
            $entrada = date('H:i:s', strtotime($row["entrada"]));
            $entra_alm = date('H:i:s', strtotime($row["entra_alm"]));
            $sale_alm = date('H:i:s', strtotime($row["sale_alm"]));
            $salida = date('H:i:s', strtotime($row["salida"]));
            $horas = number_format(((strtotime($row["salida"]) - strtotime($row["entrada"])) / 3600), 2, '.', '');

            $gcontrol = $HORARIO::get_tarde_by_empleado($id_persona, $entrada, $fecha);
            $tarde = ($gcontrol['tarde'] == 1) ? '<span class="badge badge-danger">Tarde</span>' : '<span class="badge badge-success">A tiempo</span>';
            // $tarde = $gcontrol['tarde'];
            $array_meses[$fecha] = array(
                'id' => $id['nombre'],
                'dia' => $dia,
                'fecha' => $fecha,
                'entrada' => $entrada,
                'almuerzo' => $entra_alm . " - " . $sale_alm,
                'salida' => $salida,
                'horas' =>  floor($horas) . " horas " . (floor($horas * 60) % 60) . " minutos",
                'control' => $tarde
            );
        }



        foreach ($array_meses as $fecha => $datos) $data[] = $datos;

        $rdata = array_merge($rdata, $data);
        $rmeses = array_merge($rmeses, $array_meses);
        $rhorarios = array_merge($rhorarios, $horarios);
    }


    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($rdata),
        "iTotalDisplayRecords" => count($rdata),
        "aaData" => $rdata,
        "total" => $rmeses,
        "horarios" => $rhorarios
    );

    echo json_encode($results);
else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
