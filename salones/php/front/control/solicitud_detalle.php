<?php
$params = $_POST["params"];
?>
<script src="salones/js/functions.js"></script>
<div class="modal-header">
    <h3 class="modal-title"><b>Motivo: </b><?php echo $params["motivo"] ?></h5>
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>

</div>
<div class="modal-body">
    <div class="">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="">
                        <div class="row">
                            <label for="salon"><b>Salón: </b><?php echo $params["nombre"] ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <div id="div_fecha_inicio">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="fechainicio"><b>Fecha Inicio: </b><?php echo $params["inicio"] ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <div id="div_fecha_fin">
                        <div class="row">
                            <label for="fechafin"><b>Fecha Fin: </b><?php echo $params["fin"] ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="">
                        <div class="row" id="row_mobiliario">
                            <label for="mobiliario"><b>Mobiliario: </b><?php echo $params["mobiliario"] ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="">
                        <div class="row" id="row_equipo">
                            <label for="equipo"><b>Audiovisuales: </b><?php echo $params["audiovisuales"] ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="">
                        <div class="row" id="row_solicitante">
                            <label for="solicitante"><b>Solicitante: </b><?php echo $params["solicitante"] ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <div id="div_estado_label">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="estado"><b>Estado: </b></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="form-group">
                    <div id="div_estado_badge">
                        <div class="row">
                            <?php echo $params["badge"] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($params["estado"] == 1) : ?>
    <div class="modal-footer">
        <button type="button" class="btn btn-success" value='<?php echo $params['id_solicitud'] ?>' onclick="update_estado_solicitud(2,this.value)"><i class="fa fa-check"></i> Aprobar </button>
        <button type="button" class="btn btn-danger" value='<?php echo $params['id_solicitud'] ?>' onclick="update_estado_solicitud(0,this.value)"><i class="fa fa-times"></i> Rechazar </button>
    </div>
<?php endif; ?>
<?php if ($params["estado"] == 2) : ?>
    <div class="modal-footer">
        <button type="button" class="btn btn-info" value='<?php echo $params['id_solicitud'] ?>' onclick="update_estado_solicitud(3,this.value)"><i class="fa fa-check"></i> Finalizar </button>
        <button type="button" class="btn btn-primary" value='<?php echo $params['id_solicitud'] ?>' onclick=""><i class="fa fa-edit"></i> Modificar </button>
        <button type="button" class="btn btn-danger" value='<?php echo $params['id_solicitud'] ?>' onclick="update_estado_solicitud(0,this.value)"><i class="fa fa-times"></i> Eliminar </button>
    </div>
<?php endif; ?>