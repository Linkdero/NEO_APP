<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../horarios/php/back/functions.php';
    $id_persona = $_POST['id_persona'];
    $horarios = array();
    $horarios = Horario::get_horarios_by_empleado($id_persona);
    $data = array();
    $array_dias = array("", 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
    $dds = "";
    foreach ($horarios as $d) {
        $dias = '';
        $dds = '';
        $fijo = '';
        if ($d['flag_fijo'] == 1) {
            $fijo = 'Fijo';
            $entrada = date('H:i', strtotime($d['entrada']));
            $salida = date('H:i', strtotime($d['salida']));
            $fini = date('d-m-Y', strtotime($d['fecha_ini']));
            $ffin = 'A la fecha';
        } else if ($d['flag_fijo'] == 63 || $d['flag_fijo'] == 64) {
            $fijo = ($d['flag_fijo'] == 63) ? 'Turno' : 'Sale de Turno';
            $entrada = "";
            $salida = "";
            $fini = date('d-m-Y', strtotime($d['fecha_ini']));
            $ffin = date('d-m-Y', strtotime($d['fecha_fin']));
        } else {
            $fijo = 'Temporal';
            $entrada = date('H:i', strtotime($d['hora_ini']));
            $salida = date('H:i', strtotime($d['hora_fin']));
            $fini = date('d-m-Y', strtotime($d['fecha_ini']));
            $ffin = date('d-m-Y', strtotime($d['fecha_fin']));

            $dias = (str_replace(']', '', str_replace('[', '', str_replace('"', '', str_replace(",", " ", $d['dia'])))));
            $dias = explode(" ", $dias);
            foreach ($dias as $i) {
                $dds = $dds . " " . $array_dias[(int)$i];
            }
        }

        $sub_array = array(
            'id_control' => $d['id_control'],
            'horario' => $fijo,
            'entrada' => $entrada,
            'salida' => $salida,
            'desde' => $fini,
            'hasta' => $ffin,
            'dias' => $dds,
            'accion' => "<button type='button' class='btn btn-sm btn-personalizado outline' onclick='eliminar_cambio(" . $d['id_control'] . ", " . $d['flag_fijo'] . ")'><i class='fa fa-trash' title='Eliminar cambio'></i></button>"
        );
        $data[] = $sub_array;
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
