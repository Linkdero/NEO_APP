<div id="appFinalizarDiagnostico">
    <div class="modal-header" style="background-color: #1e87f0; color: #fff; border-bottom: 1px solid #1e87f0;">
        <h4 class="modal-title">Finalizar Diagnostico:
            <?php echo $_POST["id"] ?> <i class="fa-sharp fa-solid fa-bug-slash mx-1"></i>
        </h4>
        <input type="hidden" id="idDiagnostico" value="<?php echo $_POST["id"] ?>">
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span id="cerrar" class="link-muted text-white" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-2">
                <h3 class="mb-0 d-inline-block">
                    <i class="fa-solid fa-bug mx-1"></i>
                    Evaluación
                </h3>
                <hr class="my-2">
                <textarea class="form-control" id="evaluacion" rows="4" v-model="evaluacion"></textarea>
            </div>

            <div class="col-md-12">
                <h3 class="mb-0 d-inline-block">
                    <i class="fa-solid fa-pen-to-square mx-1"></i>
                    Recomendación
                </h3>
                <hr class="my-2">
                <textarea class="form-control" id="recomendacion" rows="4" v-model="recomendacion"></textarea>
            </div>

        </div>
    </div>

    <div class="bg-light text-right py-1">
        <button type="button" class="btn btn-sm btn-danger mx-1" @click="setAnularDiagnostico()">Anular <i
                class="fa-solid fa-file-circle-xmark"></i></button>
        <button type="button" class="btn btn-sm btn-success mx-1" :disabled="!camposCompletos"
            @click="setFinalizarDiagnostico()">Finalizar <i class="fa fa-check"></i></button>
    </div>
</div>

<script src="assets/js/plugins/ckeditor/ckeditor.js"></script>
<script type="module" src="tickets/diagnosticos/src/setFinalizarDiagnostico.js?t=<?php echo time(); ?>"></script>
<script type="module" src="assets/js/pages/components.js?t=<?php echo time(); ?>"></script>