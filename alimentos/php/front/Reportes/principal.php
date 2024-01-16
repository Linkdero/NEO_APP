<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php

if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7844)){

?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<meta charset="ISO-8859-1">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">


<!-- <script src="alimentos/js/cargar.js"></script> -->

<script src="alimentos/js/source_reporte.js"></script>
<script src="alimentos/js/functions.js"></script>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modalBody" class="modal-body">
                </div>
            </div>
        </div>
    </div>


<div class="u-content">

  <div class="u-body">
    <!-- Doughnut Chart -->

    <!-- End Doughnut Chart -->

    <!-- Overall Income -->
    <div class="card mb-4">

      <!-- Card Header -->
      <header class="card-header d-md-flex align-items-center">
        <h2 class="h3 card-header-title" >Reporte de Alimentos</h2>

        <!-- Nav Tabs -->
        <ul id="overallIncomeTabsControl" class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
          <li class="nav-item mr-4">
            <a class="nav-link active" href="#overallIncomeTab1" onclick="get_alimentos_por_fecha()" role="tab" aria-selected="true" data-toggle="tab">
              <span class="d-none d-md-inline">Por Fecha</span>

            </a>
          </li>
          <li class="nav-item mr-4">
            <a class="nav-link" href="#overallIncomeTab1" onclick="get_alimentos_por_direccion()" role="tab" aria-selected="true" data-toggle="tab">
              <span class="d-none d-md-inline">Por Dirección</span>

            </a>
          </li>
          <li class="nav-item mr-4">
            <a class="nav-link" href="#overallIncomeTab1" onclick="get_alimentos_por_empleado()" role="tab" aria-selected="true" data-toggle="tab">
              <span class="d-none d-md-inline">Por Empleado</span>

            </a>
          </li>

        </ul>
        <!-- End Nav Tabs -->
      </header>
      <!-- End Card Header -->


      <div class="card-body card-body-slide">
                  
                  <div class="row" style="position:absolute;width:100%">
                    <div class="col-sm-12">
                      <div class="row">
                        <div class="col-sm-2>">
                          <div class="form-group ">
                            <label for="oficina_visita">Inicio:</label>
                            <span class="form-icon-wrapper" style="z-index:55;">
                              <span class="form-icon form-icon--left"><i class="far fa-calendar-check form-icon__item"></i>
                              </span>
                              <input id="ini" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"></input>
                          </div>
                        </div>
                        <div class="col-sm-2>">
                          <div class="form-group ">
                            <label for="oficina_visita">Final:</label>
                            <span class="form-icon-wrapper" style="z-index:55;">
                              <span class="form-icon form-icon--left"><i class="far fa-calendar-check form-icon__item"></i>
                              </span>
                              <input id="fin" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value=""  data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"></input>
                          </div>
                        </div>
                        <div class="col-sm-6" style="z-index:55;">
                          <div class="form-group">
                            <div class="">
                              <label for="direccion_rrhh">Direccion:</label>
                              <select id="direccion_rrhh" class="form-control" >
                                <!-- <option value="0"></option> -->
                              </select>
                            </div>
                          </div>
                        <!-- <div class="col-sm-2" style="z-index:55;"></div> -->
                        <div class="col-sm-1"style="z-index:55;">
                          <button class="btn btn-info " style="margin-top:30px" onclick="reload_reporte_alimentacion()"><i class="fa fa-sync"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br><br><br><br>


                  <table id="tb_reporte_alimentacion" class="table table-striped table-hover table-bordered" width="100%">
                    <thead>
                      <tr>
                        <th class=" text-center">--Fecha</th>
                        <th class=" text-center">Desayuno</th>
                        <th class=" text-center">Almuerzo</th>
                        <th class=" text-center">Cena</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot align="right">
                      <tr><th>TOTALES</th><th></th><th></th><th></th></tr>
                      </tfoot>
                  </table> 

              </div>


      <!-- Card Body -->
      <iframe id="pdf_preview_solvencia" hidden></iframe>
      <!-- <div class="block ">
          <div class="block-header bg-muted" >

              <ul class="block-options slide_up_anim" id="b_1">

                <li>
                    <button type="button" onclick="reload_totales_insumos()" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="fa fa-sync  text-white"></i></button>
                </li>

              </ul>

              <h3 class="block-title text-white" id="titulo"></h3>
          </div>
        </div> -->
      <div class="card-body card-body-slide">

        <div id="__data">
        </div>
      </div>
      <!-- End Card Body -->
    </div>

  </div>
  <script>
    get_alimentos_por_fecha();
  </script>

<script>
          set_dates();
          function set_dates(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            firstDay = '01-' + mm + '-' + yyyy;
            today = dd + '-' + mm + '-' + yyyy;

            $('#ini').val(firstDay);
            $('#fin').val(today);

          }

          $.ajax({
              type: "POST",
              url: "alimentos/php/back/alimento/get_direcciones.php",
              dataType: 'html',
              data: { id_dir: 0 },
              success:function(data) {
                $("#direccion_rrhh").html(data);
              }
          });
</script>



    <!-- End Overall Income -->

    <!-- Current Projects -->
  <?php }
  else{
    include('inc/401.php');
  }
  }
  else{
    header("Location: index.php");
  }
  ?>
