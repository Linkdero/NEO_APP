<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){

?>
<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css">

<script src="assets/js/plugins/jspdf/jspdf.js"></script>
<script src="assets/js/plugins/jspdf/insumos/solvencia.js"></script>
<script src="insumos/js/funciones.js"></script>
<script src="insumos/js/funciones_solvencia.js"></script>
<script src="insumos/js/cargar.js"></script>
<script src="insumos/js/source.js"></script>

<script type="text/javascript">
  //reporte_solvencia(35);
</script>
<iframe id="pdf_preview_estado" hidden></iframe>
<div class="row" style="position:absolute;width:100%">
  <div class="col-sm-4">
    <div class="row">
  <div class="col-sm-2>">
    <div class="form-group ">
      <span class="form-icon-wrapper" style="z-index:55;">
        <span class="form-icon form-icon--left"><i class="far fa-calendar-check form-icon__item"></i>
        </span>
        <input id="ini" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"></input>
      </div>
    </div>
    <div class="col-sm-2>">
      <div class="form-group ">
        <span class="form-icon-wrapper" style="z-index:55;">
          <span class="form-icon form-icon--left"><i class="far fa-calendar-check form-icon__item"></i>
          </span>
          <input id="fin" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value=""  data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"></input>
        </div>
      </div>
    </div>
    </div>
    <div class="col-sm-2 text-left">
      <div class="col-sm-2"style="z-index:55;">
        <button class="btn btn-info " onclick="reload_movimientos_solvencia()"><i class="fa fa-sync"></i></button>
      </div>
    </div>
  </div>
<table id="tb_movimientos_solvencia" class="table table-striped table-hover table-bordered" width="100%">
  <thead>
    <th>Solvencias</th>
    <th>Fecha</th>
    <th>Movimiento</th>
    <th>Cantidad</th>
    <th>Responsable</th>
    <th>Gafete</th>
    <th>Empleado</th>
    <th>Bodega</th>
    <th>Acción</th>
  </thead>
  <tbody>
  </tbody>
</table>
<script>
set_dates();
</script>
<?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
