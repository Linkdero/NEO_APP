<?php
include_once '../../../../inc/functions.php';
sec_session_start();
?>

<?php if (function_exists('verificar_session') && verificar_session() == true) : ?>
    <?php
    include_once '../../back/functions.php';
    $id_dependencia = $_GET["id"];
    if (true) {
        $data = array();
        $DIRECTORIO = new Directorio();
        $dependencia = $DIRECTORIO::get_dependencia_by_id($id_dependencia);
        $nombre = "{$dependencia[0]['dependencia']} ({$dependencia[0]['departamento']}, {$dependencia[0]['municipio']})";
        $table = "
            <link href='assets/js/plugins/x-editable/bootstrap-editable.css' rel='stylesheet'/>
            <script src='assets/js/plugins/x-editable/bootstrap-editable.min.js'></script>
            <script src='assets/js/plugins/x-editable/bootstrap-editable.js'></script>
            <script src='directorio/js/source_detalle.js'></script>
            <script src='directorio/js/funciones.js'></script>
            <div class='modal-header'>
                <h3 class='modal-title'>Detalle de contactos para: <b>{$nombre}</b></h5>
                <input type='hidden' value={$id_dependencia} id='id_dependencia'>
                <ul class='list-inline ml-auto mb-0'>
                    <li class='list-inline-item'>
                        <span class='link-muted h3' class='close' data-dismiss='modal' aria-label='Close'>
                            <i class='fa fa-times'></i>
                        </span>
                    </li>
                </ul>
            </div>
            <div class='modal-body'>
                <div class='card col-sm-12'>
                    <div class='card-header'>
                        <ul class='nav nav-tabs card-header-tabs' id='graph-list' role='tablist'>
                            <li class='nav-item'>
                                <a class='nav-link active' data-toggle='tab' href='#listado'>Listado</a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' active data-toggle='tab' href='#agregar' onclick=''>Agregar</a>
                            </li>
                        </ul>
                        <div class='tab-content'>
                            <div id='listado' class='container tab-pane active'>
                                <div style='margin-top:5vh;'>
                                    <table id='tb_detalle_directorio' class='table table-sm table-bordered table-striped' width='100%'>
                                        <thead>
                                            <tr>
                                                <th class='text-left'>ID</th>
                                                <th class='text-left'>Número</th>
                                                <th class='text-center'>Nombre</th>
                                                <th class='text-center'>Puesto</th>
                                                <th class='text-center'>Fecha Actualizada</th>
                                                <th class='text-center'>Acción</th>
                                            </tr>
                                        </thead>
                                    </table>      
                                </div>
                            </div>
                            <div id='agregar' class='container tab-pane fade'>
                                <form autocomplete='off'>
                                    <input type='hidden' id='dependencia' value={$id_dependencia}>
                                    <div style='margin-top:5vh;'>
                                        <div class='row'>
                                            <div class='col-sm-4'>
                                                <div class='form-group'>
                                                    <label for = 'numero'>Número</label>
                                                    <div class = 'input-group'>
                                                        <input type='text' class='form-control ' id='numero' name='numero' required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='col-sm-4'>
                                                <div class='form-group'>
                                                    <label for = 'nombre'>Nombre</label>
                                                    <div class = 'input-group'>
                                                        <input type= 'text' class='form-control ' id='nombre' name='nombre' required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='col-sm-4'>
                                                <div class='form-group'>
                                                    <label for='puesto'>Puesto</label>
                                                    <div class='input-group'>
                                                        <input type='text' class='form-control ' id='puesto' name='puesto' required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='col-sm-12'>
                                                <button class='btn btn-block btn-sm btn-info' onclick='save_cell(event)'>
                                                    <i class='fa fa-save'></i>
                                                    Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";

        echo $table;
    } else {
        echo "  <div class='modal-header'>
                    <h3 class='modal-title'>Detalle de contactos para: <b>{$nombre}</b></h5>
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