<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true){
  include_once '../functions.php';

    $year = $_POST["year"];
    $HORARIO = new Horario();
    $total_descansos = $HORARIO->get_total_descansos($year, 1, 0, $year, 1);
    $array_dias = array('Domingo', 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado');
    $body_table = "";
    $dias = array();
    foreach($total_descansos as $descanso){
        $dias[] = $array_dias[date('w', strtotime($descanso['inicio']))]; 
        if($descanso['inicio'] != $descanso['fin']){
            $fecha_actual = $descanso['inicio'];
            while(true){
                $strtime = strtotime($fecha_actual."+ 1 days");
                $fecha_actual = date("d-m-Y", $strtime); 
                if($strtime >  strtotime($descanso['fin']))break;
                $dias[] = $array_dias[date('w', $strtime)];   
            }
            $dia = implode(" - ", $dias);
        }else{
            $dia = $array_dias[date('w', strtotime($descanso['inicio']))];
        }
        $inicio = date("d-m-Y", strtotime($descanso['inicio']));
        $fin = date("d-m-Y", strtotime($descanso['fin']));
        $body_table .= "<tr>";
        $body_table .= "<td >{$dia}</td>";
        $body_table .= "<td>{$inicio}</td>";
        $body_table .= "<td>{$fin}</td>";
        $body_table .= "<td>{$descanso['motivo']}</td>";
        $body_table .= "</tr>";
        $dias = array();
    }
    echo $body_table;
}else{
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
?>