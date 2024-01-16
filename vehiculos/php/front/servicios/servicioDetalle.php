<div id="detalleServicio">
    <input type="hidden" value="<?php echo $_GET["id_servicio"] ?>" id="noServi">
    <div class="card-body-slide bg-dark">
        <div class="modal-header bg-white">
            <h5 class="modal-title" id="exampleModalLabel">Detalle del Requerimiento #<?php echo $_GET["id_servicio"] ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="container-u bg-white py-2 text-center">
            <div class="row mx-2 mt-3">
                <div class="col-md-5 d-flex justify-content-center align-items-center">
                    <img src="vehiculos/php/front/img/autoMovil.jpg" alt="Imagen del automóvil" class="img-fluid">
                </div>
                <div class="col-md-7 justify-content-center">
                    <div class="row">
                        <h1 class="col-12 mx-3 text-center">
                            <small>
                                {{detalleVehiculo.nombre_tipo }}
                                <strong>
                                    {{detalleVehiculo.nombre_linea }}

                                </strong>
                                <span class="badge badge-info">
                                    {{detalleVehiculo.nombre_marca}}
                                </span>
                                <?php if ($_GET["estado"] == 5489) {
                                    echo '<button type="button" class="btn btn-outline-success btn-sm" onclick="impresionServicio(' . $_GET['id_servicio'] . ', ' . $_GET["estado"] . ')">Imprimir Servicio</button>';
                                }
                                ?>
                                <?php if ($_GET["estado"] == 5487 && $_GET["servicio"] == 1) {
                                    echo '<button type="button" class="btn btn-outline-info btn-sm" onclick="impresionServicio(' . $_GET['id_servicio'] . ', ' . $_GET["estado"] . ')">Imprimir Servicio</button>';
                                }
                                ?>
                            </small>
                        </h1>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <div class="animation border border-info" @mouseover="addBounceEffect()" @mouseout="removeBounceEffect()" ref="animation">
                                <h5 class="bg-info text-white p-2 cabecera">
                                    <i class=" fa-solid fa-user" ref="icon"></i> Propietario:
                                </h5>
                                <p>{{ detalleVehiculo.propietario }}</p>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="animation border border-info">
                                <h5 class="bg-info text-white p-2 cabecera">
                                    <i class=" fa-solid fa-input-numeric"></i> No.Placa:
                                </h5>
                                <div>
                                    <p>{{ detalleVehiculo.nro_placa }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="animation border border-info">
                                <h5 class="bg-info text-white p-2 cabecera">
                                    <i class=" fa-solid fa-truck-moving"></i> Tipo:
                                </h5>
                                <p>{{ detalleVehiculo.nombre_tipo }}</p>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="animation border border-info">
                                <h5 class="bg-info text-white p-2 cabecera">
                                    <i class=" fa-solid fa-copyright"></i> Marca:
                                </h5>
                                <p>{{ detalleVehiculo.nombre_marca }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-sm-3">
                            <div class="animation border border-info">
                                <h5 class="bg-info text-white p-2 cabecera"> <i class=" fa-solid fa-motorcycle"></i> Linea:</h5>
                                <p>{{detalleVehiculo.nombre_linea}}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="animation border border-info">

                                <h5 class="bg-info text-white p-2 cabecera"> <i class=" fa-solid fa-calendar-days"></i> Modelo:</h5>
                                <p>{{detalleVehiculo.modelo}}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="animation border border-info">

                                <h5 class="bg-info text-white p-2 cabecera"> <i class=" fa-sharp fa-solid fa-palette"></i> Color:</h5>
                                <p> {{detalleVehiculo.nombre_color}}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="animation border border-info">

                                <h5 class="bg-info text-white p-2 cabecera"> <i class=" fa-solid fa-toggle-on"></i> Estado:</h5>
                                <p> {{detalleVehiculo.nombre_estado}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-3">
                            <div class="animation border border-info">

                                <h5 class="bg-info text-white p-2 cabecera"> <i class=" fa-solid fa-engine"></i> Motor:</h5>
                                <p>{{detalleVehiculo.motor}}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="animation border border-info">

                                <h5 class="bg-info text-white p-2 cabecera"> <i class=" fa-sharp fa-solid fa-gas-pump"></i> Combustible:</h5>
                                <p>{{detalleVehiculo.nombre_tipo_combustible}}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="animation border border-info">
                                <h5 class="bg-info text-white p-2 cabecera"> <i class=" fa-solid fa-car-rear"></i> Chasis:</h5>
                                <p>{{detalleVehiculo.chasis}}</p>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="animation border border-info">
                                <h5 class="bg-info text-white p-2 cabecera"> <i class=" fa-solid fa-gear fa-spin"></i> Encargado:</h5>
                                <p v-if="this.detalle.id_mecanico_asignado != null"> {{mecanicos.nombre}}</p>
                                <p v-else> Pendiente</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row mx-2">
                <div class="col-md-4">
                    <div class="animation border border-info">
                        <h5 class="bg-info text-white p-2 cabecera">Detalle del Vehiculo</h5>
                        <p>{{detalleVehiculo.observaciones}}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="animation border border-info">
                        <h5 class="bg-info text-white p-2 cabecera">Descripción Solicitado</h5>
                        <p v-if="servicio.desc_solicitado[0] == '>' ||servicio.desc_solicitado[1] == '>' ||servicio.desc_solicitado[2] == '>'" v-html="'<'+servicio.desc_solicitado"></p>
                        <p v-else class="text-dark" v-html="servicio.desc_solicitado"></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="animation border border-info">
                        <h5 class="bg-info text-white p-2 cabecera">Descripción Realizado</h5>
                        <p v-if="detalle.descripcion_realizado[0] == '>' || detalle.descripcion_realizado[2] == '>' || detalle.descripcion_realizado[3] == '>'" v-html="'<'+detalle.descripcion_realizado"></p>
                        <p v-else class="text-dark" v-html="detalle.descripcion_realizado"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animation {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s;
    }

    .animation:hover {
        transform: scale(1.02);
    }

    .cabecera {
        border-top-right-radius: 9px;
        border-top-left-radius: 9px;
    }
</style>

<script type="module" src="vehiculos/js/servicios/detalleServicio.js?t=<?php echo time(); ?>"></script>
<script src="vehiculos/js/servicios/funciones.js"></script>