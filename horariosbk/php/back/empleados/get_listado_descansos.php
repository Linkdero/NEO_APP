<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../horarios/php/back/functions.php';

    $HORARIO = new Horario();
    $listado = $HORARIO->get_listado_descansos();
    $select_tipo = "<option selected>Seleccione una opción</option>";
    foreach($listado as $value){
        $mes = $value['mes'];        
        $dia = $value['dia'];
        $select_value = $value['id_descanso']."-".$dia."-".$mes."-".$value["tipo"]."-".$value["id_tipo_ausencia"];
        $select_tipo .= "<option value={$select_value}>{$value['nombre']}</option>";
    }
    echo $select_tipo;

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
