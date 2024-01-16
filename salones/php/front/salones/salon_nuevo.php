<?php include_once '../../../../inc/functions.php'; ?>
<?php if (array_key_exists("id", $_GET)) : ?>
    <?php
    include_once '../../functions.php';
    $id_salon = $_GET["id"];
    $salon = new Salon();
    $data = $salon::get_salon_by_id($id_salon);
    $estado = ($data["estado"] == 1) ? "checked" : "";

    ?>
    <div class="modal-header">
        <h3 class="modal-title">Modificar Salón</h5>
            <ul class="list-inline ml-auto mb-0">
                <li class="list-inline-item">
                    <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </span>
                </li>
            </ul>
    </div>
    <div class="modal-body">
        <form class="validation_nuevo_salon">
            <div class="row">
                <input type="hidden" id="id" value="<?php echo $data["id_salon"]; ?>" />
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="">
                            <div class="row">
                                <label for="nombre">Nombre</label>
                                <div class="input-group  has-personalizado">
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $data["nombre"]; ?>" placeholder="Nombre del Salón" required autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="">
                            <div class="row">
                                <label for="ubicacion">Equipo Disponible</label>
                                <div class="input-group  has-personalizado">
                                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo $data["ubicacion"]; ?>" placeholder="Equipo Disponible" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="">
                            <div class="row">
                                <label for="capacidad">Capacidad Aproximada</label>
                                <div class="input-group  has-personalizado">
                                    <input type="text" class="form-control" id="capacidad" name="capacidad" value="<?php echo $data["capacidad"]; ?>" placeholder="Capacidad" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="">
                            <div class="row" id="row_estado">
                                <label for="">Salon Disponible</label>
                                <div class="input-group  has-personalizado">
                                    <label class="css-input switch switch-success">
                                        <input name="check" id="chk_estado" data-id="" onchange="mostrar_motivo()" data-name="" type="checkbox" <?php echo $estado; ?> /><span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-block btn-sm btn-info" onclick="save_salon(1)"><i class="fa fa-save"></i> Guardar</button>
        </form>
        <script>
            function mostrar_motivo() {
                if (!$('#chk_estado').is(':checked')) {
                    $('#row_estado').append(`<label id="label_motivo" for="motivo">Motivo</label>
                                                <div class="input-group has-personalizado" id="div_motivo" style="margin: 10px 0px 10px 0px; ">
                                                    <input type="text" class="form-control" id="motivo" name="motivo" required/>
                                                </div>`);
                } else {
                    $('#div_motivo').remove();
                    $('#label_motivo').remove();
                    $('#motivo-error').remove();
                }
            }
        </script>
    </div>
<?php else : ?>
    <div class="modal-header">
        <h3 class="modal-title">Nuevo Salón</h5>
            <ul class="list-inline ml-auto mb-0">
                <li class="list-inline-item">
                    <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </span>
                </li>
            </ul>
    </div>
    <div class="modal-body">
        <form class="validation_nuevo_salon">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="">
                            <div class="row">
                                <label for="nombre">Nombre</label>
                                <div class="input-group  has-personalizado">
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del Salón" required autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="">
                            <div class="row">
                                <label for="ubicacion">Equipo Disponible</label>
                                <div class="input-group  has-personalizado">
                                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Equipo disponible" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="">
                            <div class="row">
                                <label for="capacidad">Capacidad Aproximada</label>
                                <div class="input-group  has-personalizado">
                                    <input type="text" class="form-control" id="capacidad" name="capacidad" placeholder="Capacidad" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-block btn-sm btn-info" onclick="save_salon(0)"><i class="fa fa-save"></i> Guardar</button>
        </form>
    </div>
<?php endif; ?>