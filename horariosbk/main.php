<?php
if (isset($u) && $u->accesoModulo(7852)){
    switch($url){
        case '_800':
          include('horarios/php/front/empleados/empleados.php');
        break;
        case '_801':
            include('horarios/php/front/empleados/empleados_diario.php');
        break;
        case '_802':
            include('horarios/php/front/boletas/empleados.php');
        break;
        case '_803':
            include('horarios/php/front/boletas/boletas_listado.php');
        break;
        case '_804':
            include('horarios/php/front/calendario/principal.php');
        break;
    }
}else{
    include('./inc/401.php');
}
?>
