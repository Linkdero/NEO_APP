<?php

include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
if (usuarioPrivilegiado_acceso()->accesoModulo(3549)){

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script type="text/javascript">
    </script>
    <script src="insumos/js/funciones_egreso.js"></script>
    <script src="insumos/js/cargar.js"></script>
    <script src="insumos/js/source_modal.js"></script>
    <script src="assets/js/plugins/jspdf/jspdf.js"></script>
    <script src="assets/js/plugins/jspdf/insumos/insumo_movimiento.js"></script>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>

</head>

<div id="tran_form">
    <br>
    <table id="tb_bodegas" class="table  table-hover table-bordered" width="100%">
        <thead>
            <th class="text-center">Bodega</th>
            <th class="text-center">Descripción</th>
            <th class="text-center">Usuarios</th>
            <th class="text-center">Activos</th>
            <th class="text-center">Inactivos</th>
            <th class="text-center">Accion</th>
            <!--<th class="text-center">Anotaciones</th>-->
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<?php }
else {
    include('inc/401.php');
}
}
else {
    header("Location: index.php");
}
?>
