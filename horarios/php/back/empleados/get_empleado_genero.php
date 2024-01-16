<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true){
  include_once '../functions.php';

    $genero = $_POST["genero"];
    $HORARIO = new Horario();
    $direccion = $HORARIO::get_direccion_empleado($_SESSION["id_persona"]);
    $empleados = $HORARIO::get_empleado_genero($genero, $direccion[0]["id_dirf"]);
    $select = "<div class='col-sm-12'>
                <label for='tipo' class='control-label mr-sm-2'>Personas:</label>
                    <select id='select_employee' class='form-control mb-2 mr-sm-2'>";
    $dias = array();
    foreach($empleados as $empleado){
        $select .= "<option value={$empleado['id_persona']}>{$empleado['nombre']}</option>";
    }
    $select .= "</select></div>";
    echo $select;
}else{
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
?>