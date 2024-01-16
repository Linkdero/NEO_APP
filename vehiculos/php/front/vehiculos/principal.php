<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php

if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1162)){

?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<script src="vehiculos/js/cargar.js"></script>
<div class="u-content">

  <div class="u-body">

    <div class="card mb-4">

      <!-- Card Header -->
      <header class="card-header d-md-flex align-items-center">
        <h2 class="h3 card-header-title" >Vales de combustible</h2>

          <?php if(evaluar_flag($_SESSION['id_persona'],1162,109,'flag_insertar')==1 ) { ?>
            <ul class="list-inline ml-auto mb-0">
              <li class="list-inline-item"  title="Nuevo vale de combustible">
                <span class="link-muted h3" data-toggle="modal" data-target="#modal-remoto-lg" href="vehiculos/php/front/vales/nuevo_vale_combustible.php">
                  <i class="fa fa-plus"></i>
                </span>
              </li>
            </ul>
          <?php } ?>

      </header>

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

  <?php }
  else{
    include('inc/401.php');
  }
  }
  else{
    header("Location: index.php");
  }
  ?>