<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(3549)) {
    switch ($url) {
        case '_200':
            include('insumos/php/front/insumos/principal.php');
            break;
        case '_201':
            include('insumos/php/front/reportes/principal.php');
            break;
        case '_202':
            include('insumos/php/front/solvencia/principal.php');
            break;
        case '_210':
            include('insumos/php/front/bodegas/index_bodega.php');
            break;
    }
} else {
    //include('../inc/401.php');
}
?>
