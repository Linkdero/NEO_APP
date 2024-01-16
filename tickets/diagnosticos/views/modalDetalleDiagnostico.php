<div id="appDetalleDiagnostico">
    <div class="modal-header" style="background-color: #1e87f0; color: #fff; border-bottom: 1px solid #1e87f0;">
        <h4 class="modal-title">{{tituloModulo}}:
            <?php echo $_POST["id"] ?><i class="fa-solid fa-circle-info mx-1"></i>
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
        <hr>
        <div class="card-body-slide">
            <div class="row">
                <div class="col-sm-6">
                    <label>Dirección:</label>
                    <div>
                        <i class="fa fa-home mx-1"></i>
                        <strong> {{detalleDiagnostico.dir_funcional}} </strong>
                    </div>
                </div>

                <div class="col-sm-6">
                    <label>Persona Solicita:</label>
                    <div>
                        <i class="fa fa-user-check mx-1"></i>
                        <strong> {{detalleDiagnostico.nombre}} </strong>
                    </div>
                    <div class="row">
                        <foto-empleado :evento="evento"></foto-empleado>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div v-bind:class="{ 'step': true, 'active': detalleDiagnostico.id_estado == 6 }">
                    <div class="step-circle"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                    <div class="step-text">Mantenimiento<i v-if="detalleDiagnostico.id_estado == 6"
                            class="fas fa-sync-alt fa-spin text-info mx-1"></i> </div>
                    <div class="step-line"></div>
                </div>
                <div v-bind:class="{ 'step': true, 'active': detalleDiagnostico.id_estado == 3 }">
                    <div class="step-circle"><i class="fa-solid fa-check"></i></div>
                    <div class="step-text">Finalizado<i v-if="detalleDiagnostico.id_estado == 3"
                            class="fas fa-sync-alt fa-spin text-info mx-1"></i></div>
                    <div class="step-line"></div>
                </div>
                <div v-bind:class="{ 'step': true, 'active': detalleDiagnostico.id_estado == 7 }">
                    <div class="step-circle"><i class="fa-solid fa-user-check"></i></div>
                    <div class="step-text">Entregado</div>
                    <div class="step-line"></div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-6">
                    <small class="text-muted">Fue Generado: </small>
                    <h5>{{detalleDiagnostico.fecha_solicitado}} </h5>
                </div>

                <div class="col-sm-6">
                    <small class="text-muted">Fue Terminado: </small>
                    <h5>{{ detalleDiagnostico.fecha_finalizado || 'Pendiente en Mantenimiento' }}<i
                            v-if="!detalleDiagnostico.fecha_finalizado" class="fas fa-sync-alt fa-spin text-info"></i>
                    </h5>
                </div>
                <div class="col-sm-12">
                    <small class="">Descripción del Problema: </small>
                    <h5 v-if="detalleDiagnostico.descripcion" v-html="detalleDiagnostico.descripcion"></h5>
                    <h5 v-else> Pendiente en Mantenimiento<i class="fas fa-sync-alt fa-spin text-info mx-1"></i></h5>
                </div>
            </div>
            <hr>
            <div class="row">

                <table v-if="tipoTabla ==1" id="tablRequeri"
                    class="table responsive table-sm table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 65%;">Bien</th>
                            <th class="text-center" style="width: 25%;">Responsable</th>
                            <th class="text-center" style="width: 10%;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" style="width: 65%;">
                                {{detalleDiagnostico.bien_descripcion_completa}}</td>
                            <td class="text-center" style="width: 25%;"> {{detalleDiagnostico.tecnico}}</td>
                            <td class="text-center" style="width: 10%;">
                                <button @click="getGenerarImpresion()" title="Impresión"
                                    v-if="detalleDiagnostico.id_estado == 3 || detalleDiagnostico.id_estado == 7"
                                    type="button" class="btn btn-outline-info btn-sm"><i
                                        class="fa-sharp fa-solid fa-print"></i></button>
                                <button
                                    v-if="detalleDiagnostico.id_estado != 3 && detalleDiagnostico.id_estado != 4 && detalleDiagnostico.id_estado != 7"
                                    type="button" class="btn btn-outline-danger btn-sm"
                                    @click="setAnularDiagnostico()"><i class="fas fa-trash"></i></button>
                                <button
                                    v-if="detalleDiagnostico.id_estado != 3 && detalleDiagnostico.id_estado != 4 && detalleDiagnostico.id_estado != 7"
                                    type="button" class="btn btn-outline-success btn-sm"
                                    @click="subirArchivo(<?php echo $_POST["id"] ?>)"><i
                                        class="fa-sharp fa-solid fa-upload"></i></button>
                                <button @click="getBitacoraImpresiones(<?php echo $_POST["id"] ?>)"
                                    title="Bitacora Impresiones"
                                    v-if="detalleDiagnostico.id_estado == 3 || detalleDiagnostico.id_estado == 7"
                                    type="button" class="btn btn-outline-info btn-sm"><i
                                        class="fa-sharp fa-regular fa-table"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table v-if="tipoTabla == 2" class="table responsive table-sm table-bordered table-striped"
                    width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">Correlativo</th>
                            <th class="text-center">Gafete</th>
                            <th class="text-center">Responsable</th>
                            <th class="text-center">#Bien</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in bitacoraImpresiones" :key="item.id">
                            <td class="text-center">{{ item.correlativo }}</td>
                            <td class="text-center">{{ item.id_persona }}</td>
                            <td class="text-center">{{ item.nombre }}</td>
                            <td class="text-center">{{ item.bien }}</td>
                            <td class="text-center">{{ item.fecha }}</td>
                            <td class="text-center">
                                <button @click="getGenerarImpresion(item.fecha)" title="Re-impresión"
                                    v-if="detalleDiagnostico.id_estado == 3 || detalleDiagnostico.id_estado == 7"
                                    type="button" class="btn btn-outline-info btn-sm"><i
                                        class="fa-sharp fa-solid fa-print"></i></button>
                                <button @click="getDetalleDiagnostico(<?php echo $_POST["id"] ?>)"
                                    v-if="detalleDiagnostico.id_estado == 3 || detalleDiagnostico.id_estado == 7"
                                    type="button" class="btn btn-outline-info btn-sm"><i
                                        class="fa-solid fa-arrow-turn-down-left fa-fade"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <hr>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <table id="tablDesc" class="table responsive table-sm table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th style="width: 50%;">
                                    Evaluación:
                                </th>
                                <th style="width: 50%;">
                                    Recomendación:
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    <span v-if="detalleDiagnostico.evaluacion"
                                        v-html="detalleDiagnostico.evaluacion"></span>
                                    <span v-else> Pendiente en Mantenimiento<i
                                            class="fas fa-sync-alt fa-spin text-info mx-1"></i></span>
                                </td>
                                <td style="width: 50%;">
                                    <span v-if="detalleDiagnostico.recomendacion"
                                        v-html="detalleDiagnostico.recomendacion"></span>
                                    <span v-else> Pendiente en Mantenimiento<i
                                            class="fas fa-sync-alt fa-spin text-info mx-1"></i></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>
    <div v-if="pdf">
        <iframe :src="pdf" style="width:100%; height:600px;"></iframe>
    </div>
    <iframe id="pdfContainer" style="display:none;"></iframe>
</div>

<style>
    .step-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .step {
        flex: 1;
        text-align: center;
        position: relative;
        cursor: pointer;
    }

    .step-text {
        font-weight: bold;
        margin-top: 10px;
    }

    .step-line {
        height: 5px;
        background-color: #34495e;
        /* Nuevo color */
        flex: 1;
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        margin-top: -17px;
        /* Ajuste realizado aquí */
        z-index: 0;
        /* Añadido z-index */
    }

    .step-circle {
        width: 35px;
        height: 35px;
        background-color: #34495e;
        /* Nuevo color */
        color: #fff;
        border-radius: 50%;
        border: 2px solid #34495e;
        /* Nuevo color */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        position: relative;
        z-index: 1;
        margin: 0 auto;
    }

    .step.active .step-circle {
        background-color: #007bff;
        /* Azul */
        border: 2px solid #007bff;
        /* Azul */
    }

    .step.active .step-line {
        background-color: #007bff;
        /* Azul */
    }
</style>
<script type="module" src="tickets/diagnosticos/src/getDetalleDiagnostico.js?t=<?php echo time(); ?>"></script>