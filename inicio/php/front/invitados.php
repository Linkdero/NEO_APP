
<?php
if (function_exists('verificar_session') && verificar_session()){

?>


<script src="inicio/js/source_table.js"></script>

<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">




<script>
$(document).ready(function(){


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
            <h2 class="h3 card-header-title">Persona Acreditada</h2>

            <!-- Card Header Icon -->
            <ul class="list-inline ml-auto mb-0">
              <li class="list-inline-item">
                <span class="link-muted h3" onclick="reload_invitados()" data-toggle="tooltip" title="Recargar">
                  <i class="fa fa-sync"></i>
                </span>
              </li>

            </ul>
            <!-- End Card Header Icon -->
          </header>
          <div class="card-body p-0 animacion_right_to_left col-sm-12">





                  <!-- Comment -->
                  <div class="col-sm-12">
                  <div id="div_invitado" >
                    <br>
                    <table id="tb_invitados" class="table table-striped" width="100%">
                      <thead>
                        <th>Fotografía</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Procedencia</th>
                        <th>Estado</th>
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
