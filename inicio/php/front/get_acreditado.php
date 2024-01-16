
<?php
include_once '../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){

  $invitado=$_POST['invitado'];


?>

<script src="inicio/js/funciones.js"></script>


<script>
$(document).ready(function(){
  //get_agreditado(<?php echo "'".$invitado."'"?>);
  alert('');

});

</script>
<?php

?>


    <?php

    ?>
    <!-- Doughnut Chart -->

    <!-- End Overall Income -->

    <!-- Current Projects -->
    <div class="row">
      <!-- Current Projects -->

      <!-- End Current Projects -->

      <!-- Comments -->
      <div class="col-md-3"></div>
      <div class="col-md-6">
        <div class="card h-100 overflow-hidden">
          <header class="card-header d-flex align-items-center">
            <h2 class="h3 card-header-title">Persona Acreditada</h2>

            <!-- Card Header Icon -->
            <ul class="list-inline ml-auto mb-0">
              <li class="list-inline-item">
                <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
                  <i class="fa fa-times"></i>
                </span>
              </li>
            </ul>
            <!-- End Card Header Icon -->
          </header>





                  <!-- Comment -->
                  <div id="div_invitado_datos" class="overflow-hidden">

              <!-- End Tabs Content -->

          </div>


        </div>
      </div>
      <div class="col-md-3"></div>
      <!-- End Comments -->
    </div>
    <!-- End Current Projects -->
  </div>
  <?php
  }
  else{
    header("Location: index.php");
  }

  ?>
