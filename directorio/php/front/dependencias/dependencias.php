<?php if (function_exists('verificar_session') && verificar_session()) : ?>
  <?php if (usuarioPrivilegiado_acceso()->accesoModulo(1875)) :
    $clase = new Directorio;
    $departamentos = $clase->get_departamentos();
    //$dependencias=$clase->get_dependencias_by_nombre();
  ?>
    <script>
      $(document).ready(function() {
        $("#id_departamento").change(function() {
          get_dependencias_by_departamento();
          reload_dependencias()
        });
        get_dependencias_by_departamento();
        $("#id_dependencia_").change(function() {
          //get_dependencias_by_departamento();
          reload_dependencias()
        });
        //get_dependencias_by_departamento();
        $("#id_dependencia_").select2({
          placeholder: "Seleccionar una dirección",
          allowClear: true
        });

      })
    </script>

    <script src="directorio/js/source_dependencias.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>



    <div class="u-content">
      <div class="u-body">
        <div class="row">
          <div class="col-md-12 mb-12 mb-md-0">
            <div class="card h-100">
              <header class="card-header d-flex align-items-center">
                <h2 class="h3 card-header-title">Directorio Dependencias</h2>
                <ul class="list-inline ml-auto mb-0">
                  <li class="list-inline-item" title="Recargar">
                    <span class="link-muted h3" onclick="">
                      <i class="fa fa-plus" data-toggle="tooltip" data-placement="left" title="Agregar"></i>
                    </span>
                  </li>
                  <li class="list-inline-item" title="Recargar">
                    <span class="link-muted h3" onclick="reload_dependencias()">
                      <i class="fa fa-sync" data-toggle="tooltip" data-placement="left" title="Recargar"></i>
                    </span>
                  </li>
                </ul>
              </header>
              <div class="card-body card-body-slide">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-sm-2">
                          <select id="id_departamento" class="form-control">
                            <option value='0'>TODOS</option>
                            <?php
                            foreach ($departamentos as $depto) {
                              echo '<option value="' . $depto['id_departamento'] . '">' . $depto['nombre'] . '</option>';
                            }
                            ?>
                          </select>
                        </div>
                        <!--<div class="col-sm-2">
                                                <select id="id_municipio" class="form-control">
                                                </select>
                                              </div>-->
                        <div class="col-sm-2">

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="">
                  <table id="tb_dependencias" class="table table-sm table-bordered table-striped" width="100%">
                    <thead>
                      <tr>
                        <th class="text-center">Ubicación</th>
                        <th class="text-center">Dependencia</th>
                        <th class="text-center">Cargo</th>
                        <th class="text-center">Funcionario</th>
                        <th class="text-center">Teléfono</th>
                        <th class="text-center">Dirección</th>
                        <th class="text-center">Detalle</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>


    <?php else : ?>


    <?php endif; ?>

  <?php else : ?>


  <?php endif; ?>