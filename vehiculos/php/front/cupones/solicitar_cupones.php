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
      <script src="vehiculos/js/cupon_solicitud_vue.js"></script>
      <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
      <script src="assets/js/plugins/select2/select2.min.js"></script>

      <style>
        .fixed_header,
        tr td {
          border: 2px solid powderblue
            /*  lightsteelblue steelblue */
          ;
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
    <div id="app_solicitar_cupones" style="-webkit-transform: translateZ(0);">

      <div class="modal-header">
        <h5 class="modal-title">Entrega de cupones </h5>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item">
            <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close" id="cerrarModal">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>

      <div id="" class="modal-body">
        <form-solicitar-cupon></form-solicitar-cupon>
      </div>

    </div>
<?php } else {
    include('inc/401.php');
  }
} else {
  header("Location: index");
}
?>