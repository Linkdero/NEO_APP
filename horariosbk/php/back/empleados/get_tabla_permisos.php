<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) {
    include_once '../functions.php';

    $year = $_POST["year"];
    $month = $_POST["month"];
    $HORARIO = new Horario();
    $BOLETA = new Boleta();
    // $direccion = $HORARIO::get_direccion_empleado($_SESSION["id_persona"]);
    // $total_descansos = $HORARIO::get_total_descansos($year, 1, 0);
    // $total_descansos = $HORARIO::get_empleado_permiso($direccion[0]["id_dirf"],0);
    $total_descansos = $HORARIO::get_empleado_permiso(0, 0, $month, $year);
    $array_dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $body_table = "";
    $dias = array();
    foreach ($total_descansos as $descanso) {
        $accion = '<div class="btn-group"><button class="btn btn-sm btn-personalizado outline" onclick="aprobar_boleta(' . $descanso['id_control'] . ', 5)"><i class="fa fa-check"></i></button>' . '<button class="btn btn-sm btn-personalizado outline" onclick="anular_boleta(' . $descanso['id_control'] . ', 7)"><i class="fa fa-times"></i></button></span></div>';

        $estado = $BOLETA::set_estado_badge($descanso['est_id'], $descanso['est_des']);
        $id_persona = $HORARIO::get_name($descanso['id_persona']);
        $persona = $id_persona['nombre'];
        $dias[] = $array_dias[date('w', strtotime($descanso['fecha_inicio']))];
        // echo date('H:i', strtotime($descanso['fecha_inicio']));
        if (date('H:i', strtotime($descanso['fecha_inicio'])) == '00:00' | (date('H:i', strtotime($descanso['fecha_inicio'])) == date('H:i', strtotime($descanso['fecha_fin'])))) {
            $hora_inicio = "";
            $hora_fin = "";
        } else {
            $hora_inicio = date('H:i', strtotime($descanso['fecha_inicio']));
            $hora_fin = date('H:i', strtotime($descanso['fecha_fin']));
        }

        if ($descanso['fecha_inicio'] != $descanso['fecha_fin']) {
            $fecha_actual = $descanso['fecha_inicio'];
            while (true) {
                $strtime = strtotime($fecha_actual . "+ 1 days");
                $fecha_actual = date("d-m-Y", $strtime);
                if ($strtime >  strtotime($descanso['fecha_fin'])) break;
                $dias[] = $array_dias[date('w', $strtime)];
            }
            $dia = implode(" - ", $dias);
        } else {
            $dia = $array_dias[date('w', strtotime($descanso['fecha_inicio']))];
        }
        $inicio = date("d-m-Y", strtotime($descanso['fecha_inicio']));
        $fin = date("d-m-Y", strtotime($descanso['fecha_fin']));
        $body_table .= "<tr>";
        $body_table .= "<td >{$persona}</td>";
        $body_table .= "<td >{$dia}</td>";
        $body_table .= "<td>{$inicio}</td>";
        $body_table .= "<td>{$fin}</td>";
        $body_table .= "<td>{$hora_inicio}</td>";
        $body_table .= "<td>{$hora_fin}</td>";
        $body_table .= "<td>{$descanso['nombre']}</td>";
        $body_table .= "<td>{$descanso['observaciones']}</td>";
        $body_table .= "<td>{$estado}</td>";
        $body_table .= "<td>{$accion}</td>";
        $body_table .= "</tr>";
        $dias = array();
    }
    echo $body_table;
} else {
    echo "<script type='text/javascript'> window.location='principal'; </script>";
}
