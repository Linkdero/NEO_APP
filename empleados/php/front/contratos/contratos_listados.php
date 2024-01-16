<?php

if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163) || $u != NULL && $u->accesoModulo(8085)){//1163 módulo recursos humanos


?>
<script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
<script src="assets/js/plugins/vue/vue.js"></script>
<script src="assets/js/plugins/jspdf/jspdf.1.5.js"></script>

<script src="assets/js/plugins/jspdf/fonts/Ubuntu-Medium.js"></script>
<script src="assets/js/plugins/jspdf/fonts/Fredoka-One.js"></script>
<script src="assets/js/plugins/jspdf/fonts/Montserrat-Regular.js"></script>
<script src="assets/js/plugins/jspdf/fonts/Montserrat-Semibold.js"></script>
<script src="assets/js/plugins/jspdf/fonts/Montserrat-Light.js"></script>
<script src="assets/js/plugins/jspdf/fonts/Poppins-Bold.js"></script>
<script src="assets/js/plugins/jspdf/fonts/logo_transparente.js"></script>

<script src="assets/js/plugins/jspdf/empleados/ficha.1.2.js"></script>
  <script src="empleados/js/source_table_3.7.js"></script>
<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

  <div class="u-content">
    <div class="u-body">
      <!-- Overall Income -->
      <div class="row">
        <!-- Current Projects -->
        <div class="col-md-12 mb-12 mb-md-0">
          <div class="card h-100">
            <header class="card-header d-flex align-items-center">
              <h2 class="h3 card-header-title">Contratos activos</h2>

              <!-- Card Header Icon -->
              <ul class="list-inline ml-auto mb-0">


                <!-- inicio -->
                <li class="nav-item mr-4">
                  <?php if(usuarioPrivilegiado()->hasPrivilege(93)){?>
                  <li class="list-inline-item" data-toggle="tooltip" title="Filtros">

                    <span class="link-muted h3" href="#!" role="button" id="dropdownMenuLinkBirthday" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                      <i class="fa fa-calendar-check"></i>
                    </span>
                    <div class="dropdown-menu dropdown-menu- dropdown-menu-right border-0 py-0 mt-3" aria-labelledby="dropdownMenuLinkBirthday" style="width: 260px;">
                      <div class="card overflow-hidden">
                        <div class="card-header py-3">
                          <!-- Storage -->
                          <div class="d-flex align-items-center mb-3">
                            <span class="h6 text-muted text-uppercase mb-0">Filtros</span>

                            <div class="ml-auto text-muted">
                              <!--<strong class="text-dark">60gb</strong> / 100gb-->
                            </div>
                          </div>
                        </div>

                        <div class="card-body animacion_right_to_left" style="padding: 0rem;">
                          <div class="">
                              <div class="col-sm-12">
                                  <p id="filter1"><label><b>Estado:</b></label><br></p>
                              </div>
                              <div class="col-sm-12">
                                  <p id="filter2"><label><b>Mes:</b></label><br></p>
                              </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <?php }?>
                </li>

                <!-- fin -->
              </ul>

              <!-- End Card Header Icon -->
            </header>

            <div class="card-body card-body-slide">
            <!--  <input type="text" id="data" placeholder="Ingresa un valor">
          <button type="button" id="generar_barcode">Generar código de barras</button>
          <div id="imagen"></div>
              <script>
            $("#generar_barcode").click(function() {
            var data = $("#data").val();
            $("#imagen").html('<img src="empleados/php/front/empleados/barcode.php?text='+data+'&size=90&codetype=Code39&print=true"/>');
            $("#data").val('');
            });
          </script>-->
              <div class="">
                <iframe id="pdf_preview_e" hidden="hidden"></iframe>
                <input id="tipo_reload_c" value="1" hidden></input>
              <table id="tb_contratos_listado" class="table table-sm table-bordered table-striped  " width="100%">
                <thead>

                  <tr>
                    <th class="text-center">fotografia</th>
                    <th class="text-center">Carné</th>
                    <th class="text-center">Empleado</th>
                    <th class="text-center">Fecha de Nacimiento</th>
                    <th class="text-center">NIT</th>
                    <th class="text-center">IGSS</th>
                    <th class="text-center">Descripcion</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Archivo</th>
                    <th class="text-center">NISP</th>
                    <th class="text-center">Ingreso</th>
                    <th class="text-center">Baja</th>
                    <th class="text-center">Acción</th>
                    <th class="text-center">Dirección</th>

                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- End Card Body -->
          </div>
          <!-- End Overall Income -->
        </div>
      </div>
      </div>
      </div><?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index");
}
?>
