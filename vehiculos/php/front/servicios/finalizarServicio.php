<style>
    #informacion p {
        color: white !important;
        font-weight: bold;
    }
</style>
<div id="finalizarServicio">
    <div class="modal-header bg-info">
        <h3 class="modal-title text-white"><i class="bg-info text-white far fa-edit mr-3"></i><strong>Finalización del Servicio: <?php echo $_GET["id"] ?></strong></h3>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <input type="hidden" value="<?php echo $_GET["id"] ?>" id="idServicio">
    </div>
    <div class="modal-body bg-white">
        <form>
            <div class="form-group justify-content-center align-items-center text-center">
                <div class="alert alert-info row" role="alert">
                    <div class="col-12">
                        <span class="font-weight-bold">Requerimientos del Servicio:</span>
                    </div>
                    <div class="col-12">
                        <div v-if="solicitado[1] == '>'" class="text-white" v-html="'<'+solicitado" id="informacion"></div>
                        <div v-else v-html="solicitado"></div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="form-row">
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label>Persona que recibe vehículo a taller</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend w-100">
                                    <span class="input-group-text"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
                                    <select class="custom-select" id="personaRecibe" style="width: 90%">
                                        <option value="0" disabled selected>- Seleccionar -</option>
                                        <option v-for="p in personas" v-bind:value="p.id_persona">
                                            {{ p.nombre }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" form-row">
                    <div class="col-md-12 mb-3">
                        <label>Descripción de lo realizado en el servicio</label>
                        <div class="input-group">
                            <textarea placeholder="Agregue Descripción" class="form-control form-control-sm" rows="3" id="descripcion" name="descripcion" required></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer bg-white">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-sm btn-info text-right" @click="finalizar(<?php echo $_GET["id"] ?>)"><i class="fa fa-check"></i> Guardar</button>
    </div>
</div>
<script type="module" src="vehiculos/js/servicios/finalizarServicioModel.js?t=<?php echo time(); ?>"></script>