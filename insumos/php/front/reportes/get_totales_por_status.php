<?php
include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){
    $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
    foreach($datos AS $d){
      $bodega = $d['id_bodega_insumo'];
    }
    /*$tipo='800';
    set_time_limit(0);
    $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
    foreach($datos AS $d){
      $bodega = $d['id_bodega_insumo'];
    }

    $totales = array();
    $totales = insumo::get_totales_marca_estado($bodega,$tipo);
    $data = array();

    foreach ($totales as $t){
      $total=0;
      $sub_array = array(
        'estado'=>$t['estado'],
        'MOTOROLA'=>$t['MOTOROLA'],
        'Chicom'=>$t['Chicom'],
        'HYTERA'=>$t['HYTERA'],
        'HYT'=>$t['HYT'],
        'BOAFENG'=>$t['BOAFENG'],
        'KENWOOD'=>$t['KENWOOD'],
        'VERTEX'=>$t['VERTEX'],
        'TOTAL'=>$total+$t['MOTOROLA']+$t['Chicom']+$t['HYTERA']+$t['HYT']+$t['BOAFENG']+$t['KENWOOD']+$t['VERTEX']
      );

      $data[]=$sub_array;
    }
echo json_encode($data);*/

?>
<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">



<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <script src="insumos/js/source_modal.js"></script>

<div class="">
  <div class="form-group">
    <select class="form-control" id="tipo">
      <option value="0">Todos</option>
      <option value="400">Frecuencia 400</option>
      <option value="800">Frecuencia 800</option>
    </select>
  </div>
  <?php if($bodega==3552):?>
   <table id="tb_totales_insumos" class="table table-striped table-bordered" width="100%">
      <thead>
        <th class="text-center">MARCA</th>
        <th class="text-center">DISPONIBLE</th>
        <th class="text-center">ASIGNADO</th>
        <th class="text-center">ASIGNADO TEMPORAL</th>
        <th class="text-center">EXTRAVIADO</th>
        <th class="text-center">MAL ESTADO</th>
        <!-- <th class="text-center">BAJA</th> -->
        <th class="text-center">TOTAL</th>
      </thead>
      <tbody>
      </tbody>
      <tfoot align="right">
		    <tr><th>TOTAL</th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
	    </tfoot>
      </table>
    <?php endif;?>
    <?php if($bodega==5907):?>
      <table id="tb_totales_insumos_movil" class="table table-striped table-bordered" width="100%">
      <thead>
        <th class="text-center">ESTADO</th>
        <th class="text-center">HUAWEI</th>
        <th class="text-center">IPHONE</th>
        <th class="text-center">SAMSUNG</th>
        <th class="text-center">TOTAL</th>
      </thead>
      <tbody>
      </tbody>
      <tfoot align="right">
        <tr><th>TOTAL</th><th></th><th></th><th></th><th></th></tr>
	    </tfoot>
      </table>
    <?php endif;?>
    <?php if($bodega==5066):?>
      <table id="tb_totales_insumos_armas" class="table table-striped table-bordered" width="100%">
      <thead>
        <th class="text-center">ESTADO</th>
        <th class="text-center">DAEWOO</th>
        <th class="text-center">UZI</th>
        <th class="text-center">KALASHNIKOV</th>
        <th class="text-center">MEPOR21</th>
        <th class="text-center">EAGLE</th>
        <th class="text-center">ROSSI</th>
        <th class="text-center">GENERICO</th>
        <th class="text-center">DESANTIS</th>
        <th class="text-center">GALIL</th>
        <th class="text-center">CAA</th>
        <th class="text-center">VALTRO</th>
        <th class="text-center">FNHERSTAL</th>
        <th class="text-center">JERICHO</th>
        <th class="text-center">TAURUS</th>
        <th class="text-center">IMI</th>
        <th class="text-center">TANFGOLIO</th>
        <th class="text-center">FOBUS</th>
        <th class="text-center">SM</th>
        <th class="text-center">BLACKHAWK</th>
        <th class="text-center">STOEGER</th>
        <th class="text-center">BERETTA</th>
        <th class="text-center">CZ</th>
        <th class="text-center">BUSHNELL</th>
        <th class="text-center">ANAJMAN</th>
        <th class="text-center">SIGPRO</th>
        <th class="text-center">TAVOR</th>
        <th class="text-center">SEGARMOR</th>
        <th class="text-center">COLT</th>
        <th class="text-center">GLOCK</th>
        <th class="text-center">TOTAL</th>
      </thead>
      <tbody>
      </tbody>
      <tfoot align="right">
        <tr><th>TOTAL</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
	    </tfoot>
      </table>
    <?php endif;?>
</div>
<script>

</script>
<?php

}
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
