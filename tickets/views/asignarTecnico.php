<script type="module" src="tickets/src/asignarTecnico.js?t=<?php echo time();?>"></script>

<div id="appAsignarTecnico">
    <div class="modal-header bg-info">
        <input type="hidden" id="id_ticket" value='<?php echo $_GET["id"] ?>'>
        <h4 class="modal-title text-white"><strong>Asignar Técnicos al Ticket# <?php echo $_GET["id"] ?></strong></h4>
        <input type="hidden" id="tipos" value='<?php echo $_GET["tipo"] ?>'>
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span class="link-muted h3 text-white" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>
    </div>

    <div class="modal-body">
        <form class="jsValidacionAsignarTecnico">
            <div class="row" style="z-index:5555">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="">
                            <div class="">
                                <label for="tecnicos">Seleccionar técnico:*</label>
                                <div class=" input-group  has-personalizado">
                                    <select class="jsTecnicos form-control form-control-sm" id="tecnicos" multiple required>
                                        <option v-for="t in tecnicos" v-bind:value="t.id_persona">
                                            {{ t.primer_nombre }} {{ t.segundo_nombre }} {{ t.tercer_nombre }}
                                            {{ t.primer_apellido }} {{ t.segundo_apellido }} {{ t.tercer_apellido }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <br><br>
                <div class="col-sm-12">
                    <button class="btn btn-info btn-block btn-sm" @click="asignarTecnico"><i class="fa fa-check-circle"></i> Asignar Técnicos</button>
                </div>
            </div>
        </form>

    </div>
</div>