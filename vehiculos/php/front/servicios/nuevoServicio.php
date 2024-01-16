<script type="module" src="vehiculos/js/servicios/nuevoServicio.js?t=<?php echo time() ?>"></script>

<div id="nuevoServicio">
    <div class="row">
        <div class="col-7 bg-white rounded">
            <div class="modal-header">
                <h4 class="modal-title text-dark"><strong>Generar Nuevo Servicio</strong></h4>
                <ul class="list-inline ml-auto mb-0">
                    <li class="list-inline-item">
                        <span class="link-muted h3 text-dark" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times"></i>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="modal-body">
                <form class="nuevoServicio">
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label>Nro. de oficio {{tipoVehiculos}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-file-text" aria-hidden="true"></i></span>
                                </div>
                                <input type="text" class="form-control" id="numOficio" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label>Tipo de Servicio</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-cog" aria-hidden="true"></i></span>
                                </div>
                                <select class="custom-select" id="tipoServicio">
                                    <option value="0" disabled selected>- Seleccionar -</option>
                                    <option v-for="t in tipoServicio" v-bind:value="t.id_item">
                                        {{ t.descripcion }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label>Persona que entrega vehículo a taller</label>
                            <div class="input-group w-100">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
                                </div>
                                <select class="custom-select" id="personaEntrega" style="width: 90%">
                                    <option value="0" disabled selected>- Seleccionar -</option>
                                    <option v-for="p in personas" v-bind:value="p.id_persona">
                                        {{ p.nombre }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label>Tipo de Vehiculo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa-solid fa-gears"></i></i></span>
                                </div>
                                <select class="custom-select" id="tipoVehiculo" @change="tipoVehiculo($event)">
                                    <option value="0" disabled selected>- Seleccionar -</option>
                                    <option value="1">
                                        Propio
                                    </option>
                                    <option value="2">
                                        Arrendado
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label>Placas</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-car" aria-hidden="true"></i></span>
                                </div>
                                <select class="custom-select" id="placa" style="width: 50%">
                                    <option disabled selected>- Seleccionar -</option>
                                    <option v-for="p in placas" v-bind:value="p.id_vehiculo">
                                        {{ p.nro_placa }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class=" form-row">
                        <div class="col-md-12 mb-3">
                            <label>Km Actual</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                </div>
                                <input type="number" class="form-control" rows="1" id="kmActual"></input>
                            </div>
                        </div>
                    </div>

                    <div class=" form-row">
                        <div class="col-md-12 mb-3">
                            <label>Descripción de la solicitud de servicio</label>
                            <div class="input-group">
                                <textarea placeholder="Agregue Descripción" class="form-control form-control-sm" rows="3" id="descripcion" name="descripcion" required></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-12 mb-3">
                <button class="btn btn-info btn-block btn-sm" @click="nuevoServicio"><i class="fa fa-check-circle"></i> Nuevo Servicio </button>
            </div>
        </div>

        <div class="col-4 mx-3">
            <div class="mt-5 bg-white rounded">
                <div class="media align-items-center my-3"><i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3"><i class="fa fa-address-card" aria-hidden="true"></i></i>
                    <div class="media-body"><small class="mb-0">Placas</small>
                        <h5 class="font-weight-semibold">{{vehiculo.nro_placa}}</h5>
                    </div>
                </div>

                <div class="media align-items-center my-3 "><i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3 fa fa-car-tilt"></i>
                    <div class="media-body"><small class="mb-0">Chasís</small>
                        <h5 class="font-weight-semibold">{{vehiculo.chasis}}</h5>
                    </div>
                </div>

                <div class="media align-items-center my-3 "><i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3"><i class="fa fa-truck" aria-hidden="true"></i></i>
                    <div class="media-body"><small class="mb-0">Motor</small>
                        <h5 class="font-weight-semibold">{{vehiculo.motor}}</h5>
                    </div>
                </div>

                <div class="media align-items-center my-3 "><i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3"><i class="fa fa-calendar" aria-hidden="true"></i></i>
                    <div class="media-body"><small class="mb-0">Modelo</small>
                        <h5 class="font-weight-semibold">{{vehiculo.modelo}}</h5>
                    </div>
                </div>

                <div class="media align-items-center my-3 "><i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3"><i class="fab fa-monero"></i></i>
                    <div class="media-body"><small class="mb-0">Marca</small>
                        <h5 class="font-weight-semibold">{{vehiculo.nombre_marca}}</h5>
                    </div>
                </div>

                <div class="media align-items-center my-3 "><i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3 fa fa-card-side"><i class="fas fa-shuttle-van"></i></i>
                    <div class="media-body"><small class="mb-0">Tipo</small>
                        <h5 class="font-weight-semibold">{{vehiculo.nombre_tipo}}</h5>
                    </div>
                </div>

                <div class="media align-items-center my-3 "><i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3"><i class="fas fa-toggle-on"></i></i>
                    <div class="media-body"><small class="mb-0">Estado</small>
                        <h5 class="font-weight-semibold">{{vehiculo.nombre_estado}}</h5>
                    </div>
                </div>

                <div class="media align-items-center my-3 "><i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3 fa fa-user"></i>
                    <div class="media-body"><small class="mb-0">Persona Asignada</small>
                        <h5 class="font-weight-semibold">{{vehiculo.nombre_persona_asignado}}</h5>
                    </div>
                </div>

                <div class="media align-items-center my-3 "><i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3 fa fa-gas-pump"></i>
                    <div class="media-body"><small class="mb-0">Tipo de Combustible</small>
                        <h5 class="font-weight-semibold">{{vehiculo.nombre_tipo_combustible}}</h5>
                    </div>
                </div>

                <div class="media align-items-center my-3 "><i class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3 fa fa-clock"></i>
                    <div class="media-body"><small class="mb-0">Ultimo Kilometraje</small>
                        <h5 class="font-weight-semibold">{{vehiculo.km_actual}}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .modal-content {
        background-color: transparent;
        border: 0;
    }
</style>