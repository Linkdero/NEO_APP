<div id="AppTicketNuevo">
    <div class="modal-header bg-info">
        <h4 class="modal-title text-white"><strong>Nuevo Ticket</strong></h4>
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span id="cerrar" class="link-muted h3 text-white" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>
    </div>
    <div class="modal-body card-body-slide">
        <form class="jsValidacionDirecciones">
            <!-- Seleccione Direccion, Departamento, Requerimientos -->
            <div class="row" style="z-index:5555">
                <direccioneslist :tipo="<?php echo $_POST["tipo"] ?>"></direccioneslist>
            </div>

            <div class="row">
                <!-- Descripción -->

                <div class="col-sm-12">

                    <strong>
                        Agregue Descripción de la Solicitud: 
                    </strong>
                    <br>
                    <div class="form-group">
                        <div class=" input-group  has-personalizado">
                            <textarea placeholder="Agregue Descripción" class="form-control form-control-sm" rows="3"
                                id="descripcion" name="descripcion" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <button class="btn btn-info btn-block btn-sm" @click="ticketNuevo('<?php echo $_POST["tipo"] ?>')"
                    id="nuevoTick"><i class="fa fa-check-circle"></i> Abrir Nuevo Ticket</button>
            </div>
        </form>
    </div>
</div>

<script type="module" src="tickets/src/ticketNuevoModel1.2.js?t=<?php echo time(); ?>"></script>
<script src="assets/js/pages/components.js"></script>