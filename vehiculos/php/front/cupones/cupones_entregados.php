<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php

if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(1162)) {

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <script src="assets/js/plugins/vue/vue.js"></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>

    <script src="vehiculos/js/cargar.js"></script>
    <div class="u-content">

      <div class="u-body">

        <div class="card mb-4">

          <!-- Card Header -->
          <header class="card-header d-md-flex align-items-center">
            <h2 class="h3 card-header-title">Entrega de Combustible</h2>

            <?php if (evaluar_flag($_SESSION['id_persona'], 1162, 114, 'flag_insertar') == 1) { ?>
              <ul class="list-inline ml-auto mb-0">
                <li class="list-inline-item" title="Nuevo Cupon de combustible">
                  <span class="link-muted h3" data-toggle="modal" data-target="#modal-remoto-lg" href="vehiculos/php/front/cupones/solicitar_cupones.php">
                    <i class="fa fa-plus"></i>
                  </span>
                </li>
              </ul>
            <?php } ?>

          </header>

          <iframe id="pdf_preview_estado" hidden></iframe>
          <div class="card-body card-body-slide">
            <input id="txCupon" class="form-control col-sm-1" style="width:100px; height: 15px" name="txCupon" type="text" placeholder="Cupon" hidden></input>
            <div id="_data"></div>
          </div>



          <!-- End Card Body -->
        </div>

      </div>
      <script>
        get_cupones_entregados();
      </script>

  <?php } else {
    include('inc/401.php');
  }
} else {
  header("Location: index.php");
}
  ?>