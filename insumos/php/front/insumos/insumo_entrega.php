<?php
include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(3549)) {

?>
    <!DOCTYPE html>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="assets/js/plugins/datatables/new/dataTables.rowsGroup.js"></script>
      <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
      <script type="text/javascript">




      </script>
      <script src="assets/js/plugins/jspdf/jspdf.js"></script>
      <script src="assets/js/plugins/jspdf/insumos/insumo_movimiento.js"></script>
      <script src="insumos/js/funciones_resguardo.js"></script>
      <script src="insumos/js/cargar.js"></script>
      <script src="insumos/js/source_modal.js"></script>

    </head>

    <body>


      <div class="row">
        <div class="col-sm-3">
          <div class="input-group">
            <input id="id_persona" maxlength="4" class="form-control form-corto" onkeyup="onkeyup_enter(event,4353,2)" autocomplete="off" autofocus placeholder="No. Gafete"></input>
            <div class="input-group-append">
              <span class="btn btn-info" data-toggle="modal" data-target="#modal-remoto-lg" href="insumos/php/front/empleados/get_empleados.php"><i class="fa fa-plus-circle"></i></span>
            </div>
          </div>
          <!--<button id="btn_save_i__" style="float:right;" class="btn btn-info" onclick="crear_ingreso()"><i class="fa fa-save"></i></button>-->
        </div>
      </div>
      <br>
      <div id="tran_form">

        <div class="row">


          <div class="col-sm-12">
            <div class="card shadow-none">
              <div class="card-body">
                <div Class="row" style="height:110px">

                  <div class="col-sm-12 ">
                    <div id="datos" class="form-group">
                      Escriba el Número de Gafete del Empleado
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>


        <table id="tb_movimientos_empleado_resguardo" class="table  table-hover table-bordered" width="100%">
          <thead>
            <th>Transacción</th>
            <th>Anotaciones</th>
            <th>Producto</th>
            <th>Serie</th>
            <th>Movimiento</th>
            <th>Resguardo</th>
            <th>Entregado</th>
            <th>Entregar</th>
            <th>Acción</th>

          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <div class="">
        <span class="btn btn-block btn-info" id="btn_save_en" style="" onclick="crear_entrega()"><i class="fa fa-save"></i> Guardar</span>
      </div>


      <script>


      </script>
  <?php } else {
    include('inc/401.php');
  }
} else {
  header("Location: index.php");
}
  ?>