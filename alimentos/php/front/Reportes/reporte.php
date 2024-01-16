<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7844)){

  //   $comidas = array();    
  //   $comidas = $clase->get_repo_alimentosGen($ini,$fin);

  //   $data = array();
  //  foreach ($comidas as $comida){ 
  //    //if($comida['de_oficina']){

  //      $sub_array = array(
  //        //'oficina' => $visita['de_oficina'],
  //        'fecha' => date_format(new DateTime($comida['fecha']), 'd-m-Y'),
  //        'desayuno' => $comida['desayuno'],
  //        'almuerzo' => $comida['almuerzo'],
  //        'cena' => $comida['cena'],
  //      );
  //      $data[] = $sub_array;

    //$visita = new visita;
    //$datos_puerta = $visita->get_data_by_ip($_SERVER["REMOTE_ADDR"]);
    //$puertas = $visita->get_puertas();

  $tipo_repo = 1;

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <meta charset="ISO-8859-1">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <script src="alimentos/js/cargar.js"></script>
    <script src="alimentos/js/functions.js"></script>

    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modalBody" class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <div class="u-content">
        <div class="u-body">
            <div class="card mb-4">

                <header class="card-header d-md-flex align-items-center">
                    <h2 class="h3 card-header-title" >Reporte de Alimentos Servidos</h2>
                      <!-- Nav Tabs -->
                      <ul id="overallIncomeTabsControl" class="nav nav-tabs card-header-tabs ml-md-auto mt-2 mt-md-0">
                        <li class="nav-item mr-2">
                          <a class="nav-link active" href="#overallIncomeTab1" onclick="get_alimentos_por_fecha()" role="tab" aria-selected="true" data-toggle="tab">
                            <span class="d-none d-md-inline">Por Fecha</span>

                          </a>
                        </li>
                        <li class="nav-item mr-2">
                          <a class="nav-link" href="#overallIncomeTab1" onclick="get_alimentos_por_direccion()" role="tab" aria-selected="true" data-toggle="tab">
                            <span class="d-none d-md-inline">Por Direccion</span>

                          </a>
                        </li>
                        <li class="nav-item mr-2">
                          <a class="nav-link" href="#overallIncomeTab1" onclick="get_alimentos_por_colaborador()" role="tab" aria-selected="true" data-toggle="tab">
                            <span class="d-none d-md-inline">Por Colaboradores</span>

                          </a>
                        </li>

                      </ul>
                      <!-- End Nav Tabs -->
                </header>

                <div class="card-body card-body-slide">
                  
                  <iframe id="impresion" hidden></iframe>
                    <div class="row" style="position:absolute;width:100%">
                      <div class="col-sm-12">
                        <div class="row">
                          <div class="col-sm-2>">
                            <div class="form-group ">
                              <label for="oficina_visita">Inicio:</label>
                              <span class="form-icon-wrapper" style="z-index:55;">
                                <span class="form-icon form-icon--left"><i class="far fa-calendar-check form-icon__item"></i>
                                </span>
                                <input id="ini" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value="" data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"></input>
                            </div>
                          </div>
                          <div class="col-sm-2>">
                            <div class="form-group ">
                              <label for="oficina_visita">Final:</label>
                              <span class="form-icon-wrapper" style="z-index:55;">
                                <span class="form-icon form-icon--left"><i class="far fa-calendar-check form-icon__item"></i>
                                </span>
                                <input id="fin" class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" value=""  data-provide="datepicker" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"></input>
                            </div>
                          </div>
                          <div class="col-sm-5" style="z-index:55;">
                            <div class="form-group">
                              <div class="">
                                <label for="direccion_rrhh">Direccion:</label>
                                <select id="direccion_rrhh" class="form-control" >
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-2" style="z-index:55;">
                            <div class="form-group">
                              <div class="">
                                <label for="id_comedor">Comedor:</label>
                                <select id="id_comedor" class="form-control">
                                <option value="0">Todos</option>
                                <option value="1">Comedor 1</option>
                                <option value="2">Comedor 2</option>
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-1"style="z-index:55;">
                            <button class="btn btn-info " style="margin-top:30px" onclick="reload_reporte_alimentacion()"><i class="fa fa-sync"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br><br><br><br>

                    <!-- esta es la tabla que contiene la informacion -->
                    <div id="__data"></div>

                </div>
            </div>
        </div>

        <script>
          get_alimentos_por_fecha();
        </script>

        <script>

          set_dates();
          function set_dates(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            firstDay = '01-' + mm + '-' + yyyy;
            today = dd + '-' + mm + '-' + yyyy;

            $('#ini').val(firstDay);
            $('#fin').val(today);

          }

/*           $.ajax({
              type: "POST",
              url: "alimentos/php/back/alimento/get_direcciones.php",
              dataType: 'html',
              data: { id_dir: 0 },
              success:function(data) {
                $("#direccion_rrhh").html(data);
              }
          }); */

          $.ajax({
              type: "POST",
              url: "alimentos/php/back/alimento/get_direcciones.php",
              data: { id_dir: 0 },
              success:function(data) {
                $("#direccion_rrhh").html(data);
              }
          });
          

        </script>

<?php
  }
}else{
    header("Location: index.php");
}
?>
