<?php
include_once '../../back/functions.php';
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(3549)){
    $tipo=0;
    set_time_limit(0);
    $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
    foreach($datos AS $d){
      $bodega = $d['id_bodega_insumo'];
    }

    $totales = array();
    $totales = insumo::get_totales_marca_estado_by_desc($bodega,$tipo,5339);
    $data = array();

    foreach ($totales as $t){
      $total=0;
      $sub_array = array(
        'estado'=>$t['estado'],
        'MOTOROLA'=>$t['MOTOROLA'],
        'CHICOM'=>$t['Chicom'],
        'HYTERA'=>$t['HYTERA'],
        'HYT'=>$t['HYT'],
        'BOAFENG'=>$t['BOAFENG'],
        'KENWOOD'=>$t['KENWOOD'],
        'VERTEX'=>$t['VERTEX'],
        'TOTAL'=>$total+$t['MOTOROLA']+$t['Chicom']+$t['HYTERA']+$t['HYT']+$t['BOAFENG']+$t['KENWOOD']+$t['VERTEX']
      );

      $data[]=$sub_array;
    }

    echo json_encode($data);
?>

<div class="col-md-12 mb-4 mb-md-0" style="min-height: 300px;">
<canvas class="js-overall-income-chart6" width="1000" height="300"></canvas>
</div>

<div class="card mb-4 overflow-hidden">
      <!-- Card Header -->
      <header class="card-header d-flex align-items-center">
        <h2 class="h3 card-header-title">Radios</h2>

        <!-- Card Header Icon -->
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item">
            <span class="link-muted h3" onclick="get_chart_radios()" data-toggle="tooltip" title="Recargar">
              <i class="fa fa-sync"></i>
            </span>
          </li>

        </ul>
        <!-- End Card Header Icon -->
      </header>
<div class="card-body card-body-slide">
  <div class="tab-content" id="overallIncomeTabs">
    <!-- Tab Content -->
    <div class="tab-pane fade show active" id="overallIncomeTab1" role="tabpanel">
      <div class="row">
        <!-- Chart -->
        <div class="col-md-12 mb-4 mb-md-0" style="min-height: 300px;">
          <canvas class="js-overall-income-chart1" width="1000" height="300"></canvas>

        </div>

      </div>
    </div>
        <!-- End Tab Content -->

        <!-- Tab Content -->

        <!-- End Tab Content -->
      </div>

    </div>
    <!-- End Card Body -->
  </div>

  <!-- 2 -->

  <div class="card mb-4 overflow-hidden">
        <!-- Card Header -->
        <header class="card-header d-flex align-items-center">
          <h2 class="h3 card-header-title">Radios</h2>

          <!-- Card Header Icon -->
          <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
              <span class="link-muted h3" onclick="get_chart_radios()" data-toggle="tooltip" title="Recargar">
                <i class="fa fa-sync"></i>
              </span>
            </li>

          </ul>
          <!-- End Card Header Icon -->
        </header>
  <div class="card-body card-body-slide">
    <div class="tab-content" id="overallIncomeTabs">
      <!-- Tab Content -->
      <div class="tab-pane fade show active" id="overallIncomeTab1" role="tabpanel">
        <div class="row">
          <!-- Chart -->
          <div class="col-md-12 mb-4 mb-md-0" style="min-height: 300px;">
            <canvas class="js-overall-income-chart2" width="1000" height="300"></canvas>

          </div>

        </div>
      </div>
          <!-- End Tab Content -->

          <!-- Tab Content -->

          <!-- End Tab Content -->
        </div>

      </div>
      <!-- End Card Body -->
    </div>

    <!-- 2 -->

    <div class="card mb-4 overflow-hidden">
          <!-- Card Header -->
          <header class="card-header d-flex align-items-center">
            <h2 class="h3 card-header-title">Radios</h2>

            <!-- Card Header Icon -->
            <ul class="list-inline ml-auto mb-0">
              <li class="list-inline-item">
                <span class="link-muted h3" onclick="get_chart_radios()" data-toggle="tooltip" title="Recargar">
                  <i class="fa fa-sync"></i>
                </span>
              </li>

            </ul>
            <!-- End Card Header Icon -->
          </header>
    <div class="card-body card-body-slide">
      <div class="tab-content" id="overallIncomeTabs">
        <!-- Tab Content -->
        <div class="tab-pane fade show active" id="overallIncomeTab1" role="tabpanel">
          <div class="row">
            <!-- Chart -->
            <div class="col-md-12 mb-4 mb-md-0" style="min-height: 300px;">
              <canvas class="js-overall-income-chart3" width="1000" height="300"></canvas>

            </div>

          </div>
        </div>
            <!-- End Tab Content -->

            <!-- Tab Content -->

            <!-- End Tab Content -->
          </div>

        </div>
        <!-- End Card Body -->
      </div>
      <!-- 2 -->

      <div class="card mb-4 overflow-hidden">
            <!-- Card Header -->
            <header class="card-header d-flex align-items-center">
              <h2 class="h3 card-header-title">Radios</h2>

              <!-- Card Header Icon -->
              <ul class="list-inline ml-auto mb-0">
                <li class="list-inline-item">
                  <span class="link-muted h3" onclick="get_chart_radios()" data-toggle="tooltip" title="Recargar">
                    <i class="fa fa-sync"></i>
                  </span>
                </li>

              </ul>
              <!-- End Card Header Icon -->
            </header>
      <div class="card-body card-body-slide">
        <div class="tab-content" id="overallIncomeTabs">
          <!-- Tab Content -->
          <div class="tab-pane fade show active" id="overallIncomeTab1" role="tabpanel">
            <div class="row">
              <!-- Chart -->
              <div class="col-md-12 mb-4 mb-md-0" style="min-height: 300px;">
                <canvas class="js-overall-income-chart4" width="1000" height="300"></canvas>

              </div>

            </div>
          </div>
              <!-- End Tab Content -->

              <!-- Tab Content -->

              <!-- End Tab Content -->
            </div>

          </div>
          <!-- End Card Body -->
        </div>
        <!-- 2 -->

        <div class="card mb-4 overflow-hidden">
              <!-- Card Header -->
              <header class="card-header d-flex align-items-center">
                <h2 class="h3 card-header-title">Radios</h2>

                <!-- Card Header Icon -->
                <ul class="list-inline ml-auto mb-0">
                  <li class="list-inline-item">
                    <span class="link-muted h3" onclick="get_chart_radios()" data-toggle="tooltip" title="Recargar">
                      <i class="fa fa-sync"></i>
                    </span>
                  </li>

                </ul>
                <!-- End Card Header Icon -->
              </header>
        <div class="card-body card-body-slide">
          <div class="tab-content" id="overallIncomeTabs">
            <!-- Tab Content -->
            <div class="tab-pane fade show active" id="overallIncomeTab1" role="tabpanel">
              <div class="row">
                <!-- Chart -->
                <div class="col-md-12 mb-4 mb-md-0" style="min-height: 300px;">
                  <canvas class="js-overall-income-chart5" width="1000" height="300"></canvas>

                </div>

              </div>
            </div>
                <!-- End Tab Content -->

                <!-- Tab Content -->

                <!-- End Tab Content -->
              </div>

            </div>
            <!-- End Card Body -->
          </div>

          <!-- 2 -->

          <div class="card mb-4 overflow-hidden">
                <!-- Card Header -->
                <header class="card-header d-flex align-items-center">
                  <h2 class="h3 card-header-title">Radios</h2>

                  <!-- Card Header Icon -->
                  <ul class="list-inline ml-auto mb-0">
                    <li class="list-inline-item">
                      <span class="link-muted h3" onclick="get_chart_radios()" data-toggle="tooltip" title="Recargar">
                        <i class="fa fa-sync"></i>
                      </span>
                    </li>

                  </ul>
                  <!-- End Card Header Icon -->
                </header>
          <div class="card-body card-body-slide">
            <div class="tab-content" id="overallIncomeTabs">
              <!-- Tab Content -->
              <div class="tab-pane fade show active" id="overallIncomeTab1" role="tabpanel">
                <div class="row">
                  <!-- Chart -->
                  <div class="col-md-12 mb-4 mb-md-0" style="min-height: 300px;">


                  </div>

                </div>
              </div>
                  <!-- End Tab Content -->

                  <!-- Tab Content -->

                  <!-- End Tab Content -->
                </div>

              </div>
              <!-- End Card Body -->
            </div>
<?php }
}
?>
