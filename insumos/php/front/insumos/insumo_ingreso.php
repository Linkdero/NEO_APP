<?php
include_once '../../../../inc/functions.php';
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

      <script src="assets/js/plugins/jspdf/jspdf.js"></script>
      <script src="assets/js/plugins/jspdf/insumos/insumo_movimiento.js"></script>
      <script src="insumos/js/funciones_ingreso.js"></script>
      <script src="insumos/js/cargar.js"></script>
      <script src="insumos/js/source_modal.js"></script>
    </head>

    <body>

      <div class="">
        <div class="row">
          <div class="col-sm-3">
            <div class="input-group">
              <input id="id_persona" maxlength="4" class="form-control form-corto" onkeyup="onkeyup_enter(event, 4351,1)" autocomplete="off" autofocus placeholder="No. Gafete"></input>
              <div class="input-group-append">
                <span class="btn btn-info" data-toggle="modal" data-target="#modal-remoto-lg" href="insumos/php/front/empleados/get_empleados.php"><i class="fa fa-plus-circle"></i></span>
              </div>
            </div>
            <!--<button id="btn_save_i__" style="float:right;" class="btn btn-info" onclick="crear_ingreso()"><i class="fa fa-save"></i></button>-->
          </div>
          <div class="col-sm-3">
            <div class="input-group">
              <input id="nserie" class="form-control form-corto" onkeyup="get_empleado_xnserie(event)" autocomplete="off" autofocus placeholder="Número de Serie"></input>
              <div class="input-group-append">
                <!-- <span  class="btn btn-info" data-toggle="modal" data-target="#modal-remoto-lg" href="insumos/php/front/empleados/get_empleados.php"><i class="fa fa-plus-circle"></i></span> -->
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


          <table id="tb_movimientos_empleado" class="table  table-hover table-bordered" width="100%">
            <thead>
              <th>Transacción</th>
              <th>Anotaciones</th>
              <th>Producto</th>
              <th>Serie</th>
              <th>Movimiento</th>

              <th>Entregado</th>
              <th>Devuelto</th>
              <th>Entregar</th>
              <th>Acción</th>

            </thead>
            <tbody>
            </tbody>
          </table>
        </div><br>
        <div class="row">
          <div class="col-sm-12">
            <label class="css-input switch switch-success"><input name="check" id="chk_otro_empleado" data-id="" onchange="mostrar_empleado_diferente()" data-name="" type="radio" value="1" /><span></span> Persona diferente que Entrega</label>
            <br><br>
          </div>
        </div>
        <div id="datos_empleado_entrega" style="display:none">
          <div class="row">
            <div class="col-sm-3">
              <div class="input-group">
                <input id="id_persona_diferente" maxlength="4" class="form-control form-corto" onkeyup="onkeyup_enter(event, 5555,1)" autocomplete="off" autofocus placeholder="No. Gafete"></input>
                <div class="input-group-append">
                  <span class="btn btn-info" data-toggle="modal" data-target="#modal-remoto-lg" href="insumos/php/front/empleados/get_empleados.php"><i class="fa fa-plus-circle"></i></span>
                </div>
              </div>
              <!--<button id="btn_save_i__" style="float:right;" class="btn btn-info" onclick="crear_ingreso()"><i class="fa fa-save"></i></button>-->
            </div>
          </div>
          <br>
          <div id="tran_form_diferente">

            <div class="row">


              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <div Class="row" style="height:110px">

                      <div class="col-sm-12 ">
                        <div id="datos_diferente" class="form-group">
                          Escriba el Número de Gafete del Empleado
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>
        </div>

        <div class="">
          <span class="btn btn-block btn-info" id="btn_save_in" onclick="crear_ingreso()"><i class="fa fa-save"></i> Guardar</span>
        </div>

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