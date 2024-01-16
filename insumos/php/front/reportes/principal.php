<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php

if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){

?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<script src="insumos/js/cargar.js"></script>

<div class="u-content">

  <div class="u-body">
    <!-- Doughnut Chart -->

    <!-- End Doughnut Chart -->

    <!-- Overall Income -->
    <div class="card mb-4">

      <!-- Card Header -->
      <header class="card-header d-md-flex align-items-center">
        <h2 class="h3 card-header-title" >Reporte de Insumos</h2>

        <!-- Nav Tabs -->
        <ul id="overallIncomeTabsControl" class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
          <li class="nav-item mr-4">
            <a class="nav-link active" href="#overallIncomeTab1" onclick="get_all_totales()" role="tab" aria-selected="true" data-toggle="tab">
              <span class="d-none d-md-inline">Totales</span>

            </a>
          </li>
          <li class="nav-item mr-4">
            <a class="nav-link" href="#overallIncomeTab1" onclick="get_all_totales_por_direccion()" role="tab" aria-selected="true" data-toggle="tab">
              <span class="d-none d-md-inline">Totales por Dirección</span>

            </a>
          </li>
          <li class="nav-item mr-4">
            <a class="nav-link" href="#overallIncomeTab1" onclick="get_empleados_asignados()" role="tab" aria-selected="true" data-toggle="tab">
              <span class="d-none d-md-inline">Empleados</span>

            </a>
          </li>

        </ul>
        <!-- End Nav Tabs -->
      </header>
      <!-- End Card Header -->

      <!-- Card Body -->
      <iframe id="pdf_preview_solvencia" hidden></iframe>
      <div class="block ">
          <div class="block-header bg-muted" >

              <ul class="block-options slide_up_anim" id="b_1">

                <li>
                    <button type="button" onclick="reload_totales_insumos()" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="fa fa-sync  text-white"></i></button>
                </li>

              </ul>



              <h3 class="block-title text-white" id="titulo"></h3>
          </div>
        </div>
      <div class="card-body card-body-slide">

        <div id="__data">
        </div>
      </div>
      <!-- End Card Body -->
    </div>

  </div>
  <script>
  get_all_totales();

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
