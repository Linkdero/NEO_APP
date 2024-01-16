<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php

if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1162)){

    $clase= new insumo();

    // $datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
    // $bodega;
    // foreach($datos AS $d){
    //   $bodega = $d['id_bodega_insumo'];
    // }

?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<script src="vehiculos/js/cargar.js"></script>

<div class="u-content">

  <div class="u-body">
    <!-- Doughnut Chart -->

    <!-- End Doughnut Chart -->

    <!-- Overall Income -->
    <div class="card mb-4">

      <!-- Card Header -->
      <header class="card-header d-md-flex align-items-center">
        <h2 class="h3 card-header-title" >Vales de combustible</h2>

        <!-- Nav Tabs -->
        <ul id="overallIncomeTabsControl" class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
          <li class="nav-item mr-4">
            <a class="nav-link active" href="#overallIncomeTab1" onclick="get_vales_combustible()" role="tab" aria-selected="true" data-toggle="tab">
              <span class="d-none d-md-inline "></span>

            </a>
          </li>
        </ul>
        <!-- End Nav Tabs -->
      </header>
      <!-- End Card Header -->

      <!-- Card Body -->
      <iframe id="pdf_preview_estado" hidden></iframe>

      <div class="card-body card-body-slide">
        <div id="_data"></div>
      </div>

      <!-- End Card Body -->
    </div>

  </div>
  <script>
  get_vales_combustible();

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