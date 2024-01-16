<div id="appDiagnosticoNuevo">
    <div class="modal-header" style="background-color: #1e87f0; color: #fff; border-bottom: 1px solid #1e87f0;">
        <h4 class="modal-title">Nuevo Diagnóstico <i class="fa-sharp fa-regular fa-folder-gear mx-1"></i></h4>
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span id="cerrar" class="link-muted text-white" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>
    </div>
    <div class="modal-body p-0">
        <div class="row m-0">

            <div class="col-9 p-4 rounded" style="background-color: #f5f5f5; border-right: 1px solid #e0e0e0;">
                <!-- Sección de Datos del Solicitante -->
                <div class="col-12 mb-4">
                    <h3 class="mb-0 d-inline-block" style="color: #4a90e2;">Datos Solicitante <i
                            class="fa-sharp fa-regular fa-user-secret mx-1"></i></h3>
                    <hr class="my-2" style="border-color: #4a90e2;">
                </div>

                <div class="form-row mb-4">
                    <div class="col-md-6">
                        <listado-direcciones :tipo="1"></listado-direcciones>
                    </div>
                    <div class="col-md-6">
                        <listado-empleados :tipo="1" :evento="evento"></listado-empleados>
                    </div>
                </div>
                <hr>

                <!-- Sección de Información del Bien y Detalle del Técnico -->
                <div class="row">

                    <!-- Información del Bien -->
                    <div class="col-md-5 mb-4">
                        <h3 class="mb-0 d-inline-block" style="color: #4a90e2;">Información del Bien <i
                                class="fa-solid fa-address-book mx-1"></i></h3>
                        <hr class="my-2" style="border-color: #4a90e2;">
                        <div class="form-row">
                            <div class="col">
                                <listado-bienes :tipo="1" :evento="evento"></listado-bienes>
                            </div>
                        </div>
                    </div>

                    <!-- Detalle del Técnico -->
                    <div class="col-md-7">
                        <h3 class="mb-0 d-inline-block" style="color: #4a90e2;">Detalle del Técnico <i
                                class="fa-solid fa-address-book mx-1"></i></h3>
                        <hr class="my-2" style="border-color: #4a90e2;">
                        <div class="form-row">
                            <div class="col-12">
                                <h5 class="font-weight-semibold mb-3">PROBLEMA:</h5>
                                <textarea class="form-control" id="descripcionBien" rows="5" v-model="problema"
                                    style="background-color: #fff;"></textarea>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col animation shadow-on-hover" style="background-color: #fff; border-left: 1px solid #e0e0e0;">
                <div class="row">
                    <h5 for="descripcionBien" class="font-weight-semibold my-2 mx-2">INFORMACIÓN COMPLETA:</h5>
                </div>

                <!-- Dirección Solicitante -->
                <div class="d-flex align-items-center my-2 mx-2">
                    <div class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-2">
                        <i class="fa-solid fa-street-view"></i>
                    </div>
                    <div class="flex-grow-1">
                        <small>Dirección Solicitante:</small>
                        <h5 class="font-weight-semibold" id="informacionDireccion"></h5>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2 mx-2">
                    <i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-2">
                        <i class="fa fa-address-card" aria-hidden="true"></i>
                    </i>
                    <div>
                        <small>Empleado Solicitante:</small>
                        <h5 class="font-weight-semibold" id="informacionEmpleado"></h5>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2 mx-2">
                    <i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-2">
                        <i class="fa-solid fa-barcode"></i> </i>
                    <div>
                        <small># Bien:</small>
                        <h5 class="font-weight-semibold" id="informacionNumeroBien"></h5>
                    </div>
                </div>

                <div class="d-flex align-items-center my-2 mx-2">
                    <i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-2">
                        <i class="fa-sharp fa-solid fa-file-lines"></i> </i>
                    <div>
                        <small>Descripcion Bien:</small>
                        <h5 class="font-weight-semibold" id="informacionBien"></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer bg-light border-top-0">
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-danger">Cancelar</button>
        <button :disabled="!camposCompletos" type="button" class="btn btn-sm btn-info text-right" @click="generarDiagnostico()"><i
                class="fa fa-check"></i> Guardar</button>
    </div>
</div>
<script type="module" src="tickets/diagnosticos/src/setNuevoDiagnostico.js?t=<?php echo time(); ?>"></script>
<style>
    .animation {
        transition: transform 3s;
    }

    .animation:hover {
        transform: scale(1.02);
    }

    .shadow-on-hover {
        transition: box-shadow 1s;
        /* Agrega una transición para un efecto suave */
    }

    .shadow-on-hover:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
        /* Ajusta los valores según tus preferencias */
    }
</style>