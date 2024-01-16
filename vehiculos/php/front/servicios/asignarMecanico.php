<div id="asignarMecanico">
    <div class="modal-header bg-info">
        <h3 class="modal-title text-white"><i class="bg-info text-white far fa-edit mr-3"></i><strong>Asignación Mecánico:</strong></h3>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body bg-white">
        <asignarmecanico></asignarmecanico>
    </div>
    <div class="modal-footer bg-white">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-sm btn-info text-right" @click="asignar(<?php echo $_GET["id"] ?>)"><i class="fa fa-check"></i> Guardar</button>
    </div>
</div>
<script type="module" src="vehiculos/js/servicios/asignarModel.js?t=<?php echo time(); ?>"></script>
<script src="vehiculos/js/components.js?t=<?php echo time(); ?>"></script>
<script src="assets/js/pages/components.js?t=<?php echo time(); ?>"></script>