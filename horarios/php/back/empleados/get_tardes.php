<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../horarios/php/back/functions.php';
    set_time_limit(300);

    $month = $_POST['month'];
    $year = $_POST['year'];
    $id_persona = $_POST['id_persona'];


    $total_dias = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $HORARIO = new Horario();
    $counts = 0;


    $horarios = $HORARIO->get_horario_empleado(2, $id_persona, $month, $year, $month, $year);

    foreach ($horarios as $key => $row) {

        $fecha = date('d-m-Y', strtotime($row["fecha"]));
        $entrada = date('H:i:s', strtotime($row["entrada"]));

        $gcontrol = $HORARIO->get_tarde_by_empleado($id_persona, $entrada, $fecha);
        $counts = ($gcontrol['tarde'] == 1) ? $counts + 1 : $counts;
    }

    echo $counts;
else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
