<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(7846)) {
    $visita = new visita;
    $datos_puerta = $visita->get_data_by_ip($_SERVER["REMOTE_ADDR"]);
    $puertas = $visita->get_puertas();
?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <script src="quinta/js/source_reporte.js"></script>
    <script src="quinta/js/functions.js"></script>
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
        <div class="card mb-4">
          <header class="card-header d-md-flex align-items-center">
            <h2 class="h3 card-header-title">Reporte Visitas</h2>
            (<?php echo $datos_puerta['nombre_puerta'] ?>)
          </header>
          <div class="card-body card-body-slide">
            <?php if ($datos_puerta['id_puerta'] == null) { ?>
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
                          <input id="fin" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"></input>
                      </div>
                    </div>
                    <div class="col-sm-2" style="z-index:55;">
                      <div class="form-group">
                        <div class="">
                          <label for="oficina_visita">Oficina que Visita:</label>
                          <select id="oficina_visita_" class="form-control">

                          </select>

                        </div>
                      </div>
                    </div>
                    <div class="col-sm-2" style="z-index:55;">
                      <div class="form-group">
                        <div class="">
                          <label for="puerta_">Puerta:</label>
                          <select id="puerta_" class="form-control">
                            <option value="0">Todos</option>
                            <?php
                            foreach ($puertas as $p) {
                              echo '<option value="' . $p['id_puerta'] . '"';

                              echo '>' . $p['nombre_puerta'] . '</option>';
                            }
                            ?>
                          </select>

                        </div>
                      </div>
                    </div>
                    <div class="col-sm-1" style="z-index:55;">
                      <div class="form-group">
                        <div class="">
                          <label for="no_salido">No ha salido:</label>
                          <select id="no_salido" class="form-control">
                            <option value="0">Todos</option>
                            <option value="1">No ha salido</option>
                          </select>

                        </div>
                      </div>
                    </div>

                    <div class="col-sm-2" style="z-index:55;">

                      <button class="btn btn-info " style="margin-top:30px" onclick="reload_visitas_sub()"><i class="fa fa-sync"></i></button>
                    </div>
                  </div>

                </div>

              </div>
              <br><br><br><br><br>
              <table id="table_reporte_sub" class="table table-striped table-hover table-bordered" width="100%">
              <?php } else {
              ?>
                <table id="table_reporte" class="table table-actions table-striped table-bordered nowrap mb-0" width="100%">
                <?php
              }
                ?>

                <thead>
                  <tr>
                    <th class=" text-center">ID</th>
                    <th class=" text-center">Oficina</th>
                    <th class=" text-center">Dependencia</th>
                    <th class=" text-center">Autoriza</th>
                    <th class=" text-center">Fecha</th>
                    <th class=" text-center">Entrada</th>
                    <th class=" text-center">Salida</th>
                    <th class=" text-center">Puerta</th>
                    <th class=" text-center">No. Gafete</th>
                    <th class=" text-center">Fotografia</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                </table>

          </div>
        </div>
      </div>
      <script>
        set_dates();
        $.ajax({
          type: "POST",
          url: "quinta/php/back/empleado/get_oficina.php",
          dataType: 'html',
          data: {
            id_puerta: 0
          },
          success: function(data) {
            $("#oficina_visita_").html(data);
          }
        });

        function set_dates() {
          var today = new Date();
          var dd = String(today.getDate()).padStart(2, '0');
          var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
          var yyyy = today.getFullYear();


          today = dd + '-' + mm + '-' + yyyy;

          var days_ago1 = new Date();
          days_ago1.setDate(days_ago1.getDate() - 5);
          var dd1 = String(days_ago1.getDate()).padStart(2, '0');
          var mm1 = String(days_ago1.getMonth() + 1).padStart(2, '0'); //January is 0!
          var yyyy1 = days_ago1.getFullYear();
          days_ago = dd1 + '-' + mm1 + '-' + yyyy1;

          $('#ini').val(today);
          $('#fin').val(today);
        }
      </script>
  <?php
  }
} else {
  header("Location: index.php");
}
  ?>