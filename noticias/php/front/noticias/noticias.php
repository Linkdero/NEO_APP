<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
  <?php
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7847)){
    date_default_timezone_set('America/Guatemala');
    $redes_ = array();
    $redes_ = noticia::get_redes_sociales();
    $usuarios = noticia::get_usuarios();
  ?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <script src="noticias/js/source.js"></script>
    <script src='assets/js/plugins/datatables/pdfmake.min.js'></script>
    <script src='assets/js/plugins/datatables/vfs_fonts.js'></script>
    <div class="modal fade" id="noticiaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="bodyPost" class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <div class="u-content">
      <div class="u-body">
        <div class="card mb-4">
          <header class="card-header d-md-flex align-items-center">
            <h2 class="h3 card-header-title" >Reporte Noticias</h2>
              <!-- Card Header Icon -->
              <ul class="list-inline ml-auto mb-0">
                <li class="list-inline-item" title="Nueva Noticia" data-toggle="modal" data-target="#modal-remoto" href="noticias/php/front/noticias/noticia_nueva.php">
                  <span id="new" class="link-muted h3">
                    <i class="fa fa-plus"></i>
                  </span>
                </li>
                <li class="list-inline-item" title="Recargar">
                  <span id="reload" class="link-muted h3" >
                    <i class="fa fa-sync"></i>
                  </span>
                </li>
              </ul>
          </header>
          <div class="card-body card-body-slide">
              <script src="noticias/js/funciones.js"></script>
              <div class="row" style="position:absolute;width:100%">
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-sm-2>">
                      <div class="form-group ">
                        <label for="oficina_visita">Inicio:</label>
                          <input id="ini" class="form-control" type="date" value="<?php echo date('Y-m-d', time()); ?>"></input>
                        </div>
                      </div>
                      <div class="col-sm-2>">
                        <div class="form-group ">
                          <label for="oficina_visita">Final:</label>
                            <input id="fin" class="form-control" type="date" value="<?php echo date('Y-m-d', time()); ?>"></input>
                          </div>
                        </div>
                        <div class="col-sm-2" style="z-index:55;">
                          <div class="form-group">
                              <div class="">
                                  <label for="redes_">Red Social:</label>
                                  <select id="redes_" class="form-control" >
                                    <option value="0">Todas las redes</option>
                                    <?php
                                      foreach($redes_ AS $r){
                                        echo '<option value="'.$r['id_item'].'">'.$r['descripcion'].'</option>';
                                      }
                                    ?>
                                  </select>
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-2" style="z-index:55;">
                          <div class="form-group">
                              <div class="">
                                  <label for="categoria_">Categoria:</label>
                                  <select id="categoria_" class="form-control" >
                                    <option value="0">Todos</option>
                                    <option value="1">Positiva</option>
                                    <option value="2">Negativa</option>
                                  </select>
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-2" style="z-index:55;">
                          <div class="form-group">
                              <div class="">
                                  <label for="usuario">Usuario:</label>
                                  <select id="usuario" class="form-control" >
                                    <option value="0">Todos</option>
                                    <?php
                                      foreach($usuarios AS $usuario){
                                        echo '<option value="'.$usuario['id_persona'].'">'.$usuario['nombre'].' '.$usuario['apellido'].'</option>';
                                      }
                                    ?>
                                  </select>
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-2"style="z-index:55;">
                          <button class="btn btn-info " style="margin-top:30px" onclick="reload_noticias()"><i class="fa fa-sync"></i></button>
                        </div>
                      </div>

                    </div>
                  </div>
                <br><br><br><br><br>
                <table id="tb_noticias" class="table table-striped table-hover table-bordered" width="100%">
                  <thead>
                    <tr>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Alias</th>
                        <th class="text-center">Fecha</th>
                        <th class="text-center">Usuario</th>
                        <th class="text-center">Fuente</th>
                        <th class="text-center">Categoria</th>
                        <th class="text-center">Propietario</th>
                        <th class="text-center">Aprobación</th>
                        <th class="text-center">Ubicación</th>
                        <th class="text-center">Observaciones</th>
                        <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                </table>
          </div>
        </div>
      </div>
      <script>
        $("#reload").click(() => {
          reload_noticias();
        });

      </script>
    <?php
  }
}else{
    header("Location: index.php");
}
?>
