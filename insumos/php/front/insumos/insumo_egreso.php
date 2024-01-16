<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
    if (usuarioPrivilegiado_acceso()->accesoModulo(3549)) {

?>
        <!DOCTYPE html>
        <html>

        <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8">
            <script src="assets/js/plugins/jspdf/jspdf.js"></script>
            <script src="assets/js/plugins/jspdf/insumos/insumo_movimiento.js"></script>
            <script src="insumos/js/funciones_egreso.js"></script>
            <script src="insumos/js/cargar.js"></script>
            <script src="insumos/js/source_modal.js"></script>
            <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
            <meta http-equiv="Pragma" content="no-cache" />
            <meta http-equiv="Expires" content="0" />
        </head>
        <div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-8 col-lg-6">
                            <div class="input-group">
                                <input id="id_persona" type="number" maxlength="4" class="form-control" onkeyup="onkeyup_enter(event, 4353,0)" autocomplete="off" autofocus placeholder="No. Gafete">
                                <div class="input-group-append">
                                    <span class="btn btn-info" data-toggle="modal" data-target="#modal-remoto-lg" href="insumos/php/front/empleados/get_empleados.php">
                                        <i class="fa fa-plus-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <input id="id_persona_id" hidden></input>
                    </div>
                    <div class="card shadow-sm mb-2">
                        <div class="card-body">
                            <div Class="row" style="height:110px">
                                <div class="col-sm-12 ">
                                    <div id="datos" class="form-group">
                                        Escriba el Número de Gafete del Empleado
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-8 col-lg-6">
                            <div class="input-group">
                                <input id="id_persona_diferente" type="number" maxlength="4" class="form-control form-corto" onkeyup="onkeyup_enter(event, 5555,0)" autocomplete="off" autofocus placeholder="No. Gafete">
                                <div class="input-group-append">
                                    <span class="btn btn-info" data-toggle="modal" data-target="#modal-remoto-lg" href="insumos/php/front/empleados/get_empleados.php">
                                        <i class="fa fa-plus-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <input id="id_persona_diferente_id" hidden></input>
                    </div>
                    <div class="card shadow-sm mb-2">
                        <div class="card-body">
                            <div Class="row" style="height:110px">
                                <div class="col-sm-12 ">
                                    <div id="datos_diferente" class="form-group">
                                        Escriba el Número de Gafete del Empleado
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <span class="h3 mb-2" id="desc_"></span>
            </div>
            <div class="row mt-2">

                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-8 col-lg-6">
                            <div class="input-group">
                                <input id="id_persona_autoriza" type="number" maxlength="4" class="form-control" onkeyup="get_empleado_autoriza(event, 5555,0)" autocomplete="off" autofocus placeholder="No. Gafete Autoriza">
                                <div class="input-group-append">
                                    <span class="btn btn-info" data-toggle="modal" data-target="#modal-remoto-lg" href="insumos/php/front/empleados/get_empleados_autoriza.php">
                                        <i class="fa fa-plus-circle"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <input id="id_persona_autoriza_id" hidden></input>
                    </div>
                    <div class="card shadow-sm mb-2">
                        <div class="card-body">
                            <div Class="row" style="height:110px">
                                <div class="col-sm-12 ">
                                    <div id="datos-autoriza" class="form-group">
                                        Persona Autoriza
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <input type="text" placeholder="Documento" id="nro_documento" class="form-control">
                </div>

            </div>

            <div id="tran_form" class="mt-4">
                <div class="form-group row">
                    <label for="serie" class="sr-only">Insumo</label>
                    <div class="input-group col-sm-10 col-md-11 col-lg-11 mb-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                        </div>
                        <input type="text" class="form-control" id="serie" placeholder="Insumo" onchange="agregar_insumo();" autocomplete="off">
                    </div>
                    <div class="col-sm-2 col-md-1 col-lg-1">
                        <span class="btn btn-info btn-block" data-toggle="modal" data-target="#modal-remoto-lgg3" href="insumos/php/front/insumos/get_all_insumos.php">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
                <table id="tb_movimientos_empleado_egreso" class="table table-hover table-bordered table-responsive" width="100%">
                    <thead>
                        <th class="text-center">Marca</th>
                        <th class="text-center">Modelo</th>
                        <th class="text-center">Serie</th>
                        <th class="text-center">Existencia</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">
                            <span id="btn_clear" onclick="clear_data()" class="btn btn-danger btn-sm">
                                <i class="fa fa-times"></i>
                            </span>
                        </th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div>
                <span class="btn btn-block btn-info" id="btn_save_e" style="float:right;display:none" onclick="crear_egreso()"><i class="fa fa-save"></i> Guardar</span>
            </div>
        </div>


<?php } else {
        include('inc/401.php');
    }
} else {
    header("Location: index.php");
}
?>