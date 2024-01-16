<div id="listadoAsignados">
    <div class="modal-header bg-success">
        <h3 class="modal-title text-white"><i class="fa-solid fa-user-group mr-3"></i><strong>{{titulo}}
            </strong></h3>
        <button type="button" class="close text-white" onclick="cerrar()">
            <span aria-hidden="true">&times;</span>
        </button>
        <input id="idPersona" type="hidden" value="">
        <input id="click" type="hidden" value="" @click="vehiculosAsignados()">

    </div>
    <div class="modal-body bg-white" v-show="tipo == 1">
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active slide_up_anim">
                    <table id="tblListadoAsignados" class="table table-actions table-striped table-bordered nowrap mb-0"
                        width="100%">
                        <thead>
                            <tr>
                                <th class=" text-center">Gafete</th>
                                <th class=" text-center">Nombre</th>
                                <th class=" text-center">Dirección</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-body bg-white" v-show="tipo == 2">
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active slide_up_anim">
                    <table id="tblVehiculosAsignados"
                        class="table table-actions table-striped table-bordered nowrap mb-0" width="100%">
                        <thead>
                            <tr>
                                <th class=" text-center">ID</th>
                                <th class=" text-center">Placa</th>
                                <th class=" text-center">Marca</th>
                                <th class=" text-center">Linea</th>
                                <th class=" text-center">Modelo</th>
                                <th class=" text-center">Color</th>
                                <th class=" text-center">Autorizado Por</th>
                                <th class=" text-center">Fecha Asignación</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="vehiculos/js/vehiculos/listadoAsignados.js?t=<?php echo time(); ?>"></script>
<script>
    function cerrar() {
        $('#modal-remoto-lgg3').modal('hide');
    }
</script>