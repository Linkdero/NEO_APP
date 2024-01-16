<?php include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(1162)) {


?>
    <!DOCTYPE html>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">

      <script src="assets/js/pages/components.js"></script>
      <script src="vehiculos/js/components.js"></script>
      <script src="vehiculos/js/validaciones_vue.js"></script>
      <script src="vehiculos/js/cupon_ingreso_vue.js"></script>
      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>

      <style>
        .fixed_header,
        tr td {
          border: 2px solid powderblue
            /*  lightsteelblue steelblue */
        }

        .fixed_header>tbody {
          display: block;
          height: 300px;
          overflow: auto;
        }

        .fixed_header>thead,
        .fixed_header>tbody tr {
          display: table;
          width: 100%;
          table-layout: fixed;
          /* even columns width , fix width of table too*/
        }

        .fixed_header>thead {
          width: calc(100% - 1em)
            /* scrollbar is average 1em/16px width, remove it from thead width */
        }
      </style>

    </head>
    <div id="app_ingreso_cupones">

      <div class="modal-header bg-info">
        <h3 class="modal-title text-white font-weight-bold"> <i class="fa fa-plus-circle" aria-hidden="true"></i> Ingreso de cupones </h3>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item">
            <span class="link-muted h3 text-white" class="close" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>

      <div class="modal-body mx-3">
        <div class="row">
          <form>
            <div class="form-row">
              <div class="form-group col-md">
                <label for="inputEmail4">No.Documento</label>
                <input type="text" class="form-control" id="documento" placeholder="No.Doc" required>
              </div>

              <div class="form-group col-md">
                <label>Cupon #</label>
                <input type="number" class="form-control" id="inicial" placeholder="Inicial" required>
              </div>

              <div class="form-group col-md">
                <label for="inputAddress">al Cupon #</label>
                <input type="number" class="form-control" id="final" placeholder="Final" required>
              </div>

              <div class="form-group col-md">
                <label for="inputAddress2">Con monto de Q.</label>
                <input type="number" class="form-control" id="monto" placeholder="Q.Monto" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col">
                <label for="inputEmail4">Observaciones</label>
                <textarea class="form-control" id="observaciones" rows="1" required></textarea>
              </div>

              <div class="form-group col">
                <br>
                <button type="button" class="btn btn-outline-secondary" @click="validarForm(1)"><i class="fa fa-check"> </i> Procesar</button>
              </div>
            </div>
          </form>
        </div>

        <div class="container-sm">
          <table class="table table-sm table-bordered table-striped fixed_header text-center" id="cantCupones">
            <thead>
              <tr>
                <th scope="col">Cant</th>
                <th scope="col">No.Cupon</th>
                <th scope="col">Q.Monto</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="bandera == 0">
                <th scope="row">No hay datos disponibles</th>
              </tr>
              <tr v-else v-for="c in contarCupones">
                <th scope="row">{{c.id}}</th>
                <th scope="row">{{c.cupon}}</th>
                <th scope="row">{{c.monto}}</th>
              </tr>

            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-info" @click="ingresar()">Ingresar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>

    </div>

    <script>
      $('#cantCupones').DataTable({
        "lengthChange": false,
        "searching": false,
        buttons: [{
          exportOptions: {
            modifier: {
              page: 'current'
            }
          }
        }]
      });
    </script>
<?php } else {
    include('inc/401.php');
  }
} else {
  header("Location: index");
}
?>