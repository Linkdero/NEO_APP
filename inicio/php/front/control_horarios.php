
<?php
if (function_exists('verificar_session') && verificar_session()){

?>


<script src="inicio/js/source_table.js"></script>

<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    








<script>
$(document).ready(function(){
  var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();


    today = dd + '-' + mm + '-' + yyyy;
  $('#ini').val(today);
  reload_horarios();

});

</script>
<?php

?>

<div class="u-content">


  <div class="u-body">
    <?php

    ?>
    <!-- Doughnut Chart -->

    <!-- End Overall Income -->

    <!-- Current Projects -->
    <div class="">
      <!-- Current Projects -->

      <!-- End Current Projects -->

      <!-- Comments -->
      <div class="col-md-12">
        <div class="card h-100" >
          <header class="card-header d-flex align-items-center">
            <h2 class="h3 card-header-title">Control de Horarios</h2>

            <!-- Card Header Icon -->
            <ul class="list-inline ml-auto mb-0">
              <li class="list-inline-item">
                <span class="link-muted h3" onclick="reload_horarios()" data-toggle="tooltip" title="Recargar">
                  <i class="fa fa-sync"></i>
                </span>
              </li>

            </ul>
            <!-- End Card Header Icon -->
          </header>
          <div class="card-body p-0 animacion_right_to_left col-sm-12">





                  <!-- Comment -->
                  <div class="col-sm-12">
                    <div class="row" style="position:absolute;width:100%; margin-top:15px">
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
    <div class="col-sm-2 text-left">
      <div class="col-sm-2"style="z-index:55;">
        <button class="btn btn-info " onclick="reload_horarios()"><i class="fa fa-sync"></i></button>
      </div>
    </div>

    </div>
    </div>

  </div>
                  <div id="div_invitado" >
                    <br>
                    <table id="tb_horarios" class="table table-striped" width="100%">
                      <thead>
                        <th>Gafete</th>
                        <th>Dirección</th>
                        <th>Nombres y Apellidos</th>
                        <th>Fecha</th>
                        <th>Hora entrada</th>
                        <th>Inicio almuerzo</th>
                        <th>Final almuerzo</th>
                        <th>Salida</th>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>

              <!-- End Tabs Content -->
            </div>
              <br>
          </div>
        </div>


        </div>
      </div>
      <!-- End Comments -->
    </div>
    <!-- End Current Projects -->
  </div>
  <?php
  }
  else{
    header("Location: index");
  }

  ?>
