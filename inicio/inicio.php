
<?php
if (function_exists('verificar_session') && verificar_session()){
/*
  $data = array();
  //$d = saber_dia('2019-11-29');
  $result= array();
  $evento=$_SESSION['Evento'];
  $punto=$_POST['punto'];
  $result = acreditacion::get_reporte_totales($evento,$punto);

  foreach ($result as $row) {
    $dia='';$c1=0;$c2=0;$c3=0;
    $pnt=$row['Pnt_id'];
    $pnt_des=acreditacion::get_punto_por_id($pnt);
    $invitacion=acreditacion::get_reporte_totales_por_tipo($evento,1,$row['fecha'],$pnt);
    $acreditacion=acreditacion::get_reporte_totales_por_tipo($evento,2,$row['fecha'],$pnt);
    $emergente=acreditacion::get_reporte_totales_por_tipo($evento,3,$row['fecha'],$pnt);

    if($invitacion['conteo']!=0){
      $c1=$invitacion['conteo'];
    }
    if($acreditacion['conteo']!=0){
      $c2=$acreditacion['conteo'];
  }
    if($emergente['conteo']!=0){
      $c3=$emergente['conteo'];
  }
    $sub_array = array(
      'day'=>$pnt_des['Pnt_des'],
      'fecha'=>fecha_dmy($row['fecha']),
      'total'=>$row['conteo'],
      'conteo1'=>$c1,
      'conteo2'=>$c2,
      'conteo3'=>$c3
    );
    $data[]=$sub_array;
  }

  //echo json_encode($data);

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

    echo json_encode($results);*/
  ?>

<script src="inicio/js/funciones.js"></script>
<script src="inicio/js/source_table.js"></script>
<script>
$(document).ready(function(){
  get_chart();
  get_totales();

  setInterval( function () {
    get_chart();
    get_totales();
  }, 10000 );
})
</script>


<?php

?>

<div class="u-content">

  <div class="u-body">
    <?php

    ?>
    <!-- Doughnut Chart -->


    <!-- End Doughnut Chart -->

    <!-- Overall Income -->

      <?php if($_SESSION['Usu_niv']=='A' || $_SESSION['Usu_niv']=='S'){?>
        <div class="card mb-4 overflow-hidden">
      <!-- Card Header -->
      <header class="card-header d-flex align-items-center">
        <h2 class="h3 card-header-title">Sistema de Acreditaciones</h2>

        <!-- Card Header Icon -->
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item">
            <span class="link-muted h3" onclick="get_chart()" data-toggle="tooltip" title="Recargar">
              <i class="fa fa-sync"></i>
            </span>
          </li>

        </ul>
        <!-- End Card Header Icon -->
      </header>


      <!-- End Card Header -->

      <!-- Card Body -->
      <div class="card-body card-body-slide">
        <div class="tab-content" id="overallIncomeTabs">
          <!-- Tab Content -->
          <div class="tab-pane fade show active" id="overallIncomeTab1" role="tabpanel">
            <div class="row">
              <div class="col-sm-6 col-xl-3 mb-4">
                <div class="card overflow-hidden">
                  <div class="card-body card-body-slide media align-items-center px-xl-3 bg-light">
                    <div class="u-doughnut u-doughnut--70 mr-3 mr-xl-2">
                      <canvas id="acreditaciones_graph" class="js-doughnut-chart" width="70" height="70"
                              data-set=""
                              data-colors='[
                                "#2972fa",
                                "#022e59"
                              ]'></canvas>

                      <div id="acreditaciones_per" class="u-doughnut__label text-muted data_"></div>
                    </div>

                    <div class="media-body">
                      <h5 class="h4 text-muted text-uppercase mb-2">
                        Ingresos <!--<i class="fa fa-arrow-up text-white ml-1"></i>-->
                      </h5>
                      <span id="acreditaciones_cant" class="h1 mb-0 data_ text-muted font-weight-bold"></span>
                    </div>
                  </div>
                </div>
                <br>
                <div class="card overflow-hidden">
                  <div class="card-body card-body-slide media align-items-center px-xl-3 bg-light">
                    <div class="u-doughnut u-doughnut--70 mr-3 mr-xl-2">
                      <canvas id="invitados_graph" class="js-doughnut-chart" width="70" height="70"
                              data-set=""
                              data-colors='[
                                "#2972fa",
                                "#022e59"
                              ]'></canvas>

                      <div id="invitados_per" class="u-doughnut__label text-muted data_"></div>
                    </div>

                    <div class="media-body">
                      <h5 class="h4 text-muted text-uppercase mb-2">
                        Total <!--<i class="fa fa-arrow-up text-white ml-1"></i>-->
                      </h5>
                      <span id="invitados_cant" class="h1 mb-0 data_ text-muted font-weight-bold"></span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Chart -->
              <div class="col-sm-6 col-xl-9" style="min-height: 300px;">
                <canvas class="js-overall-income-chart" width="1000" height="300"></canvas>
              </div>
              <!-- End Chart -->

                          </div>
          </div>
          <!-- End Tab Content -->

          <!-- Tab Content -->

          <!-- End Tab Content -->
        </div>

      </div>
    </div>
    <div class="card mb-4 overflow-hidden">
      <header class="card-header d-flex align-items-center">
        <h2 class="h3 card-header-title">Sistema de Acreditaciones</h2>

        <!-- Card Header Icon -->
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item">
            <span class="link-muted h3" onclick="reload_totales_dashboard()" data-toggle="tooltip" title="Recargar">
              <i class="fa fa-sync"></i>
            </span>
          </li>

        </ul>
        <!-- End Card Header Icon -->
      </header>


      <!-- End Card Header -->

      <!-- Card Body -->
      <div class="card-body card-body-slide">
        <table id="tb_conteo" class="table table-bordered table-striped" width="100%">
          <thead>
            <th class="text-center">Día</th>
            <th class="text-center">Punto</th>
            <th class="text-center">Fecha</th>
            <th class="text-center">Total</th>
            <th class="text-center">Invitaciones</th>
            <th class="text-center">Acreditaciones</th>
            <th class="text-center">Emergentes</th>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
    <?php }
    else{
    ?>
    <div class="card mb-4 overflow-hidden">
      <div class="col-lg-12 d-none d-lg-flex flex-column align-items-center justify-content-center card-body-slide">
        <img class="img-fluid position-relative u-z-index-3 mx-5 logo_saas" src="./assets/svg/mockups/LOGO_SAAS.png" alt="Image description">
      </div>
    </div>
  <?php }?>

      <!-- End Card Body -->

    <!-- End Overall Income -->

    <!-- Current Projects -->

    <!-- End Current Projects -->
  </div>
  <?php
  }
  else{
    header("Location: index");
  }

  ?>
