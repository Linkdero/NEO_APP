
<?php
if (function_exists('verificar_session') && verificar_session()){




?>

<script src="inicio/js/funciones.js"></script>


<script>
$(document).ready(function(){
  get_usuario();

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
    <div class="row">
      <!-- Current Projects -->
      <div class="col-md-6 mb-4 mb-md-0">
        <div class="card h-100 overflow-hidden ">
          <header class="card-header d-flex align-items-center">
            <h2 class="h3 card-header-title">Datos del Evento</h2>

            <!-- Card Header Icon -->
            <ul class="list-inline ml-auto mb-0">


            </ul>
            <!-- End Card Header Icon -->
          </header>

          <div class="card-body p-0 animacion_right_to_left">
            <div class="list-group list-group-flush">
               <!-- Notification -->
               <span class="list-group-item list-group-item-action">
                 <div class="media align-items-center">
                   <img class="img-fluid u-avatar--sm mx-auto mb-2" src="./assets/img/brands-sm/location-pin.svg">

                   <div class="media-body">
                     <div class="d-flex align-items-center" style="padding-left:15px">
                       <h4 id="evento" class="mb-0  data_ font-weight-bold"></h4>

                     </div>


                   </div>
                 </div>
               </span>

               <span class="list-group-item list-group-item-action">
                 <div class="media align-items-center">
                   <img class="img-fluid u-avatar--sm mx-auto mb-2" src="./assets/img/brands-sm/calendar.svg">

                   <div class="media-body">
                     <div class="d-flex align-items-center" style="padding-left:15px">
                       <h4 id="fecha" class="mb-0  data_ font-weight-bold"></h4>

                     </div>


                   </div>
                 </div>
               </span>
             </div>

          </div>

          <footer class="card-footer">
            <div class="form-group mb-4">
              <label for="email">Código de Barra</label>
              <span class="form-icon-wrapper">
                <span class="form-icon form-icon--left">
                  <i class="fa fa-barcode form-icon__item"></i>
                </span>

              <input id="bar_code_e" class="form-control form-icon-input-left" autofocus name="bar_code" type="text" placeholder="Código" autocomplete="off" onchange="edit_acreditacion();">
            </div>
          </footer>
        </div>
      </div>
      <!-- End Current Projects -->

      <!-- Comments -->
      <div class="col-md-6">
        <div class="card h-100 overflow-hidden">
          <header class="card-header d-flex align-items-center">
            <h2 class="h3 card-header-title">Persona Acreditada</h2>

            <!-- Card Header Icon -->
            <ul class="list-inline ml-auto mb-0">


            </ul>
            <!-- End Card Header Icon -->
          </header>





                  <!-- Comment -->
                  <div id="div_invitado" class="overflow-hidden">

              <!-- End Tabs Content -->

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
