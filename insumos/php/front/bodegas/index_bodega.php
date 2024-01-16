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
        <h2 class="h3 card-header-title" >Bodegas</h2>

        <!-- Nav Tabs -->

        <!-- End Nav Tabs -->
      </header>
      <!-- End Card Header -->

      <!-- Card Body -->
      <iframe id="pdf_preview_estado" hidden></iframe>
      <div class="block ">
          <div class="block-header bg-muted" >

              <ul class="block-options slide_up_anim" id="b_1">


              </ul>

              <h3 class="block-title text-white" id="titulo"></h3>
          </div>
        </div>
      <div class="card-body card-body-slide">

        <div id="_bodegas">
          mensaje
        </div>
      </div>
      <!-- End Card Body -->
    </div>

  </div>
  <script>
  get_bodegas();

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
