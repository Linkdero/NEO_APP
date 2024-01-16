<link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
<link rel="stylesheet"
  href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css">

<script src="assets/js/plugins/select2/select2.min.js"></script>
<script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
<script src="assets/js/plugins/vue/vue.js"></script>

<script type="module" src="vehiculos/js/reportes/modelReportes.js?t=<?php echo time(); ?>"></script>

<script src="assets/js/plugins/vue/http-vue-loader.js"></script>
<script src="assets/js/plugins/ckeditor/ckeditor.js"></script>
<script src="assets/js/plugins/datepicker/js/bootstrap-datepicker.js" referrerpolicy="origin"></script>
<script src="assets/js/plugins/jspdf/jspdf.js"></script>
<script src="assets/js/plugins/jspdf/vehiculos/impresionServicio.js?t=<?php echo time(); ?>"></script>
<script src="assets/js/plugins/amcharts4/core.js"></script>
<script src="assets/js/plugins/amcharts4/charts.js"></script>
<script src="assets/js/plugins/amcharts4/themes/animated.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="u-content">
  <div class="u-body" id="appReportes">
    <div class="row">
      <div class="col-md-12 mb-12 mb-md-0">
        <div class="card">
          <div class="card-header d-md-flex align-items-center">
            <h2 class="h3 card-header-title">Control de Saldo</h2>
            <ul id="overallIncomeTabsControl" class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
              <li class="nav-item mr-4">
                <a class="nav-link active" @click="renderChart(1)" href="#movimientos" role="tab" aria-selected="false"
                  data-toggle="tab">
                  <span class="d-none d-md-inline">Ultimos 5 Movimientos</span>
                </a>
              </li>
              <li class="nav-item mr-4">
                <a class="nav-link " @click="renderChart(2)" href=" #insumos" role="tab" aria-selected="false"
                  data-toggle="tab">
                  <span class="d-none d-md-inline">Uso Cupones</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" @click="renderChart(3)" href=" #asignacion" role="tab" aria-selected="false"
                  data-toggle="tab">
                  <span class="d-none d-md-inline">Ingresos</span>
                </a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane fade show active slide_up_anim">
                <h5 class="h4 card-title row">
                  <div class="col-lg-4 col-md-6 col-sm-12 animation">
                    <div class="info-box">
                      <span class="info-box-icon py-2" :class="bg">
                        <i class="fa-solid fa-money-check-dollar"></i> </span>
                      <div class="info-box-content">
                        <span class="info-box-text">Saldo Total </span>
                        <span class="info-box-number"> Q{{ saldoTotal }}</span>
                        <div class="progress">
                          <div class="progress-bar" :class="bg " :style="{ width: porcentaje + '%' }"></div>
                        </div>
                        <div class="progress-description">
                          <i class="fas fa-caret-up " :class="text"></i> {{porcentaje}}% {{descripcion}}
                        </div>
                        <!-- <div class="additional-info">
                          <div class="additional-item">
                            <i class="fas fa-chart-line text-info"></i>
                            <span class="additional-text">Tendencia Positiva</span>
                          </div>
                          <div class="additional-item">
                            <i class="fas fa-users text-warning"></i>
                            <span class="additional-text">Nuevos Clientes</span>
                          </div>
                          <div class="additional-item">
                            <i class="fas fa-tags text-danger"></i>
                            <span class="additional-text">Promociones</span>
                          </div>
                        </div> -->
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12 animation">
                    <div class="info-box">
                      <span class="info-box-icon py-2" :class="bg2">
                        <i class="fa-solid fa-money-check-dollar"></i> </span>
                      <div class="info-box-content">
                        <span class="info-box-text">Cupones de 50 </span>
                        <span class="info-box-number"> Q{{ saldoTotal50 }}</span>
                        <div class="progress">
                          <div class="progress-bar" :class="bg2 " :style="{ width: porcentaje50 + '%' }"></div>
                        </div>
                        <div class="progress-description">
                          <i class="fas fa-caret-up " :class="text2"></i> {{porcentaje50}}% {{descripcion}}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12 animation">
                    <div class="info-box">
                      <span class="info-box-icon py-2" :class="bg3">
                        <i class="fa-solid fa-money-check-dollar"></i> </span>
                      <div class="info-box-content">
                        <span class="info-box-text">Cupones de 100 </span>
                        <span class="info-box-number"> Q{{ saldoTotal100 }}</span>
                        <div class="progress">
                          <div class="progress-bar" :class="bg3 " :style="{ width: porcentaje100 + '%' }"></div>
                        </div>
                        <div class="progress-description">
                          <i class="fas fa-caret-up " :class="text3"></i> {{porcentaje100}}% {{descripcion3}}
                        </div>
                      </div>
                    </div>
                  </div>
                </h5>
                <div class="row">

                  <div v-show="tipoGrafica == 1" class="col-6">
                    <canvas id="ultimosMovimientos"></canvas>
                  </div>

                  <div v-show="tipoGrafica == 2" class="col">
                    <table class="table table-bordered text-center table-sm table-striped">
                      <thead>
                        <tr class="font-weight-bold text-dark">
                          <th scope="col">Cupones 50</th>
                          <th scope="col">Cupones 100</th>
                          <th scope="col">Fecha</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="t in totales">
                          <td>Q{{t.monto50}}</td>
                          <td>Q{{t.monto100}}</td>
                          <td>{{t.fecha_procesado}}</td>
                        </tr>
                      </tbody>
                    </table>

                  </div>

                  <div v-show="tipoGrafica == 2" class="col">
                    <canvas id="usoCupones"></canvas>
                  </div>

                  <div v-show="tipoGrafica == 3" class="col">
                    <canvas id="ingresos"></canvas>
                  </div>

                  <div v-show="tipoGrafica == 3" class="col">
                    <table class="table table-bordered text-center table-sm table-striped">
                      <thead>
                        <tr class="font-weight-bold text-dark">
                          <th scope="col">Total</th>
                          <th scope="col">Fecha</th>
                          <th scope="col">Descripción</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="i in ingresos">
                          <td>Q{{i.total}}</td>
                          <td>{{i.fecha}}</td>
                          <td>{{i.descripcion}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  <iframe id="pdf_preview_v" hidden></iframe>
  <script src="vehiculos/js/reportes/funciones.js"></script>
  <style>
    /* Estilos personalizados */
    .info-box {
      border: 1px solid #d2d6de;
      border-radius: 10px;
      box-shadow: none;
      display: block;
      margin-bottom: 20px;
      position: relative;
    }

    .info-box-icon {
      background: #17a2b8;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      min-width: 60px;
    }

    .info-box-content {
      border-radius: 0 10px 10px 0;
      padding: 20px;
      position: relative;
    }

    .info-box-text {
      font-size: 16px;
      color: #666;
    }

    .info-box-number {
      font-size: 28px;
      font-weight: bold;
      color: #333;
      margin-top: 10px;
    }

    .progress {
      margin-top: 15px;
    }

    .progress-bar {
      height: 6px;
      border-radius: 3px;
    }

    .progress-description {
      font-size: 14px;
      color: #888;
      margin-top: 10px;
    }

    .additional-info {
      margin-top: 20px;
    }

    .additional-item {
      display: flex;
      align-items: center;
      margin-bottom: 8px;
    }

    .additional-item i {
      font-size: 18px;
      margin-right: 10px;
    }

    .additional-text {
      font-size: 14px;
    }

    .animation {
      transition: transform 0.2s;
    }

    .animation:hover {
      transform: scale(1.02);
    }
  </style>