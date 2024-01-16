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
        $telefonos = $DIRECTORIO::get_telefonos_dependencia($id_dependencia);
        $nombre = $dependencia[0]["persona"];
        $direccion = $dependencia[0]["direccion"];
        $input_telefonos = "<div class='col-sm-12'>
                                <div id='div-telefonos' class='row'>";
        foreach ($telefonos as $key => $value) {
            $cont = $key + 1;
            $input_telefonos .= "<div class='col-sm-12'>
                                        <div class='form-group'>
                                            <label for='telefono'>Teléfono {$cont}</label>
                                            <div class='input'>
                                                <input type='text' class='form-control' id={$value['id_telefono']} name='telefonos' value='" . $value['numero'] . "'/>
                                            </div>
                                        </div>
                                    </div>";
        }
        $input_telefonos .= "</div>
                                </div>";

        $form = "<div class='modal-header'>
                        <h3 class='modal-title'>Modificación de Dependencia</h5>
                        <input type='hidden' id='id_dependencia' value={$id_dependencia}>
                        <ul class='list-inline ml-auto mb-0'>
                            <li class='list-inline-item'>
                                <span class='link-muted h3' class='close' data-dismiss='modal' aria-label='Close'>
                                    <i class='fa fa-times'></i>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class='modal-body'>
                        <form id='form-dependencia'>
                            <div class='row'>
                                <div class='col-sm-12'>
                                    <div class='form-group'>
                                        <label for='funcionario'>Funcionario</label>
                                        <div class='input'>
                                            <input type='text' class='form-control' id='funcionario' value='" . $nombre . "'/>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-sm-12'>
                                    <div class='form-group'>
                                        <label for='direccion'>Dirección</label>
                                        <div class='input'>
                                            <input type='text' class='form-control' id='direccion' value='" . $direccion . "'/>
                                        </div>
                                    </div>
                                </div>
                                {$input_telefonos}
                                <div class='col-sm-12' style='margin-top:2vh;' id='btn-add'>
                                    <div class='form-group'>
                                        <span class='btn btn-sm btn-personalizado outline' onclick='agregar_telefono();'>
                                            <label for='direccion'>Agregar Teléfono</label>
                                            <i class='fa fa-plus' data-toggle='tooltip' data-placement='left' title='' data-original-title='Agregar Teléfono'></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class='modal-footer'>
                        <button class='btn btn-block btn-sm btn-info' onclick='save_dependencia();'><i class='fa fa-save'></i> Guardar</button>
                    </div>
                    <script src='directorio/js/funciones.js'></script>";
        echo $form;
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