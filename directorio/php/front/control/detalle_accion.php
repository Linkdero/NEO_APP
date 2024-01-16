<?php
include_once '../../../../inc/functions.php';
sec_session_start();
?>

<?php if (function_exists('verificar_session') && verificar_session() == true) : ?>
    <?php
    include_once '../../back/functions.php';
    $id_persona = $_GET["id_persona"];
    if (true) {
        $data = array();
        $DIRECTORIO = new Directorio();
        $telefonos = $DIRECTORIO::get_directorio_by_id($id_persona);
        $nombre = $DIRECTORIO::get_directorio_by_id($id_persona);
        $table = "
            <link href='assets/js/plugins/x-editable/bootstrap-editable.css' rel='stylesheet'/>
            <script src='assets/js/plugins/x-editable/bootstrap-editable.min.js'></script>
            <script src='assets/js/plugins/x-editable/bootstrap-editable.js'></script>
            <script src='directorio/js/source_detalle_log.js'></script>
            <script src='directorio/js/funciones.js'></script>
            <div class='modal-header'>
                            <h3 class='modal-title'>Detalle de acciones de: <b>{$nombre['nombre']}</b></h5>
                            <input type='hidden' value={$id_persona} id='id_persona'>
                            <ul class='list-inline ml-auto mb-0'>
                            
                                <li class='list-inline-item'>
                                    <span class='link-muted h3' class='close' data-dismiss='modal' aria-label='Close'>
                                        <i class='fa fa-times'></i>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class='modal-body'>

                            <table id='tb_detalle_telefono' class='table table-sm table-bordered table-striped' width='100%'>
                            <div class='row'>
                            <div class='col-sm-3'>
                                <p id='filter1'><label><b>Acción:</b></label><br></p>
                            </div>
                            <div class='col-sm-3'>
                                <p>
                                <label><b>Mes:</b></label>
                                <select id='month' class='form-control' onchange='reload_detalle()'>
                                <option value='1'>Enero</option>
                                <option value='2'>Febrero</option>
                                <option value='3'>Marzo</option>
                                <option value='4'>Abril</option>
                                <option value='5'>Mayo</option>
                                <option value='6'>Junio</option>
                                <option value='7'>Julio</option>
                                <option value='8'>Agosto</option>
                                <option value='9'>Septiembre</option>
                                <option value='10'>Octubre</option>
                                <option value='11'>Noviembre</option>
                                <option value='12'>Diciembre</option>
                              </select><br></p>
                            </div>
                            <div class='col-sm-3'>
                            <p id='year'>
                            <label><b>Año:</b></label>
                            <select id=year class='form-control' onchange='reload_detalle()'>
                            <option value='2021'>2021</option>
                            <option value='2020'>2020</option>
                          </select><br>
                            </div>
                                <thead>
                                    <tr>
                                        <th class='text-center'>Detalle</th>  
                                        <th class='text-center'>Módulo</th>
                                        <th class='text-center'>Tipo Acción</th>
                                        <th class='text-center'>Valor Anterior</th>
                                        <th class='text-center'>Valor Nuevo</th>
                                        <th class='text-center'>Fecha</th>
                                    </tr>
                                </thead>
                            </table>
                            <br/>
                        </div>
                        ";

        echo $table;
    } else {
        echo "  <div class='modal-header'>
                    <h3 class='modal-title'>Detalle de contactos para: <b>{$telefonos['Nombre_Completo']}</b></h5>
                                <ul class='list-inline ml-auto mb-0'>
                                    <li class='list-inline-item'>
                                        <span class='link-muted h3' class='close' data-dismiss='modal' aria-label='Close'>
                                            <i class='fa fa-times'></i>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class='modal-body'>
                                <h1>No cuenta con permisos para ver el detalle!</h1>
                            </div>";
    }

    ?>
<?php else : ?>
    <script type='text/javascript'>
        window.location = 'principal.php';
    </script>";
<?php endif; ?>