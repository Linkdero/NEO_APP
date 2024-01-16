<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php

if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7846)){

?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />

<script src="quinta/js/cargar.js"></script>

<div class="u-content">

  <div class="u-body">
    <!-- Doughnut Chart -->

    <!-- End Doughnut Chart -->

    <!-- Overall Income -->
    <div class="card mb-4">

      <!-- Card Header -->
      <header class="card-header d-md-flex align-items-center">
        <h2 class="h3 card-header-title" >Control de Ingreso Peatonal</h2>

        <!-- Nav Tabs -->

        <!-- End Nav Tabs -->
      </header>
      <!-- End Card Header -->

      <!-- Card Body -->

      <div class="card-body card-body-slide">
        <div class="">
          <div class="form-group">
            <div class="row">
            <div class="col-sm-3">
              <div class="input-group">
                <input id="id_persona" maxlength="4" class="form-control form-corto"  onkeyup="" autocomplete="off" autofocus placeholder="No. Gafete">

              </div>

          </div>
          <div class="col-sm-3">
            <span class="h3" id="desc_"></span>
          </div>
          <input id="id_persona_id" hidden></input>
          </div>
        </div>



          <div id="tran_form">

            <div class="row">


              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">
                    <div Class="row" style="height:110px">

                      <div class="col-sm-12 ">
                        <div id="datos" class="form-group">
                          Escriba el Número de Gafete del Empleado
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <br>
      </div>
      <!-- End Card Body -->
    </div>

  </div>
  <script>


  </script>
    <!-- End Overall Income -->

    <!-- Current Projects -->
  <?php }
  else{
    //include('inc/401.php');
  }
  }
  else{
    header("Location: index.php");
  }
  ?>
